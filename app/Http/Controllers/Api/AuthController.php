<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);
        
        // Log user registration
        $this->auditService->log('user_registered', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'User registered successfully',
        ]);
    }

    /**
     * Login user and create token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Log failed login attempt
            $this->auditService->log('login_failed', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        }
        
        $user = User::where('email', $request->email)->firstOrFail();
        
        // Revoke previous tokens
        $user->tokens()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Log successful login
        $this->auditService->log('login_success', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Login successful',
        ]);
    }

    /**
     * Logout user (revoke token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Log logout
        $this->auditService->log('logout', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
        ]);
        
        auth()->user()->tokens()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user(),
            ],
        ]);
    }

    /**
     * Refresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Revoke previous tokens
        $user->tokens()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
            ],
            'message' => 'Token refreshed successfully',
        ]);
    }
}
