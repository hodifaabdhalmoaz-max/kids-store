<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add security enhancements to users table
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('last_login_ip');
            $table->timestamp('password_changed_at')->nullable()->after('is_active');
            $table->integer('login_attempts')->default(0)->after('password_changed_at');
            $table->timestamp('locked_at')->nullable()->after('login_attempts');
        });
        
        // Create user_activities table for audit logs
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('action');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('action');
            $table->index('created_at');
        });
        
        // Create failed_jobs table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
        
        // Create personal_access_tokens table
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        
        // Add security enhancements to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('payment_details')->nullable()->after('status');
            $table->string('transaction_id')->nullable()->after('payment_details');
            $table->string('payment_gateway_response')->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove security enhancements from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_at',
                'last_login_ip',
                'is_active',
                'password_changed_at',
                'login_attempts',
                'locked_at'
            ]);
        });
        
        // Drop user_activities table
        Schema::dropIfExists('user_activities');
        
        // Drop failed_jobs table
        Schema::dropIfExists('failed_jobs');
        
        // Drop personal_access_tokens table
        Schema::dropIfExists('personal_access_tokens');
        
        // Remove security enhancements from transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_details',
                'transaction_id',
                'payment_gateway_response'
            ]);
        });
    }
};
