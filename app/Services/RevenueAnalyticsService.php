<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RevenueAnalyticsService
{
    /**
     * الحصول على تحليل الإيرادات للفترة المحددة
     */
    public function getRevenueAnalytics(string $period = 'this_week'): array
    {
        try {
            $dateRange = $this->getDateRange($period);
            $previousDateRange = $this->getPreviousDateRange($period);

            // البيانات الحالية
            $currentData = $this->calculatePeriodData($dateRange);
            
            // البيانات السابقة للمقارنة
            $previousData = $this->calculatePeriodData($previousDateRange);

            // حساب نسب التغيير
            $revenueChange = $this->calculatePercentageChange(
                $previousData['revenue'], 
                $currentData['revenue']
            );
            
            $ordersChange = $this->calculatePercentageChange(
                $previousData['orders_count'], 
                $currentData['orders_count']
            );

            return [
                'current' => $currentData,
                'previous' => $previousData,
                'changes' => [
                    'revenue' => $revenueChange,
                    'orders' => $ordersChange,
                ],
                'period' => $period,
                'date_range' => $dateRange,
                'chart_data' => $this->getChartData($period)
            ];

        } catch (\Exception $e) {
            Log::error('خطأ في تحليل الإيرادات: ' . $e->getMessage());
            return $this->getEmptyAnalytics();
        }
    }

    /**
     * حساب بيانات فترة محددة
     */
    private function calculatePeriodData(array $dateRange): array
    {
        // إجمالي الإيرادات (الطلبات المسلمة فقط)
        $revenue = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total') ?? 0;

        // إجمالي قيمة الطلبات (جميع الطلبات)
        $totalOrders = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total') ?? 0;

        // عدد الطلبات
        $ordersCount = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        // عدد الطلبات المسلمة
        $deliveredCount = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        // متوسط قيمة الطلب
        $averageOrderValue = $ordersCount > 0 ? $totalOrders / $ordersCount : 0;

        return [
            'revenue' => floatval($revenue),
            'total_orders' => floatval($totalOrders),
            'orders_count' => $ordersCount,
            'delivered_count' => $deliveredCount,
            'average_order_value' => round($averageOrderValue, 2)
        ];
    }

    /**
     * تحديد النطاق الزمني
     */
    private function getDateRange(string $period): array
    {
        $now = Carbon::now();

        return match($period) {
            'this_week' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek()
            ],
            'last_week' => [
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end' => $now->copy()->subWeek()->endOfWeek()
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ],
            'last_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end' => $now->copy()->subMonth()->endOfMonth()
            ],
            'this_year' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear()
            ],
            'last_year' => [
                'start' => $now->copy()->subYear()->startOfYear(),
                'end' => $now->copy()->subYear()->endOfYear()
            ],
            default => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek()
            ]
        };
    }

    /**
     * تحديد النطاق الزمني السابق للمقارنة
     */
    private function getPreviousDateRange(string $period): array
    {
        $now = Carbon::now();

        return match($period) {
            'this_week' => [
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end' => $now->copy()->subWeek()->endOfWeek()
            ],
            'last_week' => [
                'start' => $now->copy()->subWeeks(2)->startOfWeek(),
                'end' => $now->copy()->subWeeks(2)->endOfWeek()
            ],
            'this_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end' => $now->copy()->subMonth()->endOfMonth()
            ],
            'last_month' => [
                'start' => $now->copy()->subMonths(2)->startOfMonth(),
                'end' => $now->copy()->subMonths(2)->endOfMonth()
            ],
            'this_year' => [
                'start' => $now->copy()->subYear()->startOfYear(),
                'end' => $now->copy()->subYear()->endOfYear()
            ],
            'last_year' => [
                'start' => $now->copy()->subYears(2)->startOfYear(),
                'end' => $now->copy()->subYears(2)->endOfYear()
            ],
            default => [
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end' => $now->copy()->subWeek()->endOfWeek()
            ]
        };
    }

    /**
     * حساب نسبة التغيير
     */
    private function calculatePercentageChange(float $previous, float $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * الحصول على بيانات الرسم البياني
     */
    private function getChartData(string $period): array
    {
        $chartData = [
            'labels' => [],
            'revenue' => [],
            'orders' => []
        ];

        // تحديد عدد النقاط والفترة بناءً على النوع
        [$points, $interval, $format] = match($period) {
            'this_week', 'last_week' => [7, 'day', 'D'],
            'this_month', 'last_month' => [30, 'day', 'd'],
            'this_year', 'last_year' => [12, 'month', 'M'],
            default => [7, 'day', 'D']
        };

        $dateRange = $this->getDateRange($period);
        $start = $dateRange['start']->copy();

        for ($i = 0; $i < $points; $i++) {
            $periodStart = $start->copy();
            $periodEnd = $interval === 'day' 
                ? $periodStart->copy()->endOfDay()
                : $periodStart->copy()->endOfMonth();

            $chartData['labels'][] = $periodStart->format($format);

            // إيرادات الفترة
            $revenue = Order::where('status', 'delivered')
                ->whereBetween('created_at', [$periodStart, $periodEnd])
                ->sum('total') ?? 0;

            // طلبات الفترة
            $orders = Order::whereBetween('created_at', [$periodStart, $periodEnd])
                ->sum('total') ?? 0;

            $chartData['revenue'][] = floatval($revenue);
            $chartData['orders'][] = floatval($orders);

            $start->add(1, $interval);
        }

        return $chartData;
    }

    /**
     * إرجاع بيانات فارغة في حالة الخطأ
     */
    private function getEmptyAnalytics(): array
    {
        return [
            'current' => [
                'revenue' => 0,
                'total_orders' => 0,
                'orders_count' => 0,
                'delivered_count' => 0,
                'average_order_value' => 0
            ],
            'previous' => [
                'revenue' => 0,
                'total_orders' => 0,
                'orders_count' => 0,
                'delivered_count' => 0,
                'average_order_value' => 0
            ],
            'changes' => [
                'revenue' => 0,
                'orders' => 0,
            ],
            'period' => 'this_week',
            'date_range' => [
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek()
            ],
            'chart_data' => [
                'labels' => [],
                'revenue' => [],
                'orders' => []
            ]
        ];
    }

    /**
     * الحصول على ملخص الإيرادات الإجمالية
     */
    public function getOverallSummary(): array
    {
        return [
            'total_revenue' => Order::where('status', 'delivered')->sum('total') ?? 0,
            'total_orders' => Order::count(),
            'total_delivered' => Order::where('status', 'delivered')->count(),
            'total_pending' => Order::where('status', 'ordered')->count(),
            'total_cancelled' => Order::where('status', 'canceled')->count(),
        ];
    }
}
