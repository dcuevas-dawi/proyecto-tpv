<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {
        // Get actual date
        $today = now()->format('Y-m-d');
        $startDate = $today;
        $endDate = $today;
        $period = 'daily';

        $data = $this->getAccountingData($startDate, $endDate, $period);

        return view('accounting.index', compact('data', 'startDate', 'endDate', 'period'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'period' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period');

        $data = $this->getAccountingData($startDate, $endDate, $period);

        return view('accounting.index', compact('data', 'startDate', 'endDate', 'period'));
    }

    private function getAccountingData($startDate, $endDate, $period)
    {
        // Define the date expression based on the period (always for SQLite)
        $dateExpression = "";
        $periodFormat = "";

        switch ($period) {
            case 'daily':
                $dateExpression = "strftime('%Y-%m-%d', closed_at)";
                $periodFormat = 'd/m/Y';
                break;
            case 'weekly':
                $dateExpression = "strftime('%Y-W%W', closed_at)";
                $periodFormat = 'semana';
                break;
            case 'monthly':
                $dateExpression = "strftime('%Y-%m', closed_at)";
                $periodFormat = 'mes';
                break;
            case 'quarterly':
                $dateExpression = "strftime('%Y-', closed_at) || ((CAST(strftime('%m', closed_at) AS INTEGER) + 2) / 3)";
                $periodFormat = 'trimestre';
                break;
            case 'yearly':
                $dateExpression = "strftime('%Y', closed_at)";
                $periodFormat = 'año';
                break;
        }

        // Query using Eloquent with conditions for SQLite
        $query = Order::where('status', 'cerrado');

        $query->whereRaw("strftime('%Y-%m-%d', closed_at) >= ?", [$startDate])
            ->whereRaw("strftime('%Y-%m-%d', closed_at) <= ?", [$endDate]);

        $results = $query->selectRaw("{$dateExpression} as period_key")
            ->selectRaw('SUM(total_price) as total_sales')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('period_key')
            ->orderBy('period_key')
            ->get();

        // Process results
        $formattedResults = [];
        $totalSales = 0;
        $totalOrders = 0;

        foreach ($results as $row) {
            $label = $this->formatPeriodLabel($row->period_key, $period, $periodFormat);

            $formattedResults[] = [
                'period' => $label,
                'sales' => $row->total_sales,
                'count' => $row->order_count
            ];

            $totalSales += $row->total_sales;
            $totalOrders += $row->order_count;
        }

        return [
            'data' => $formattedResults,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'period_type' => $period
        ];
    }

    /**
     * Formats the period label according to type
     */
    private function formatPeriodLabel($periodKey, $periodType, $format)
    {
        try {
            switch ($periodType) {
                case 'daily':
                    return Carbon::parse($periodKey)->format($format);

                case 'weekly':
                    if (is_numeric($periodKey)) {
                        $year = substr($periodKey, 0, 4);
                        $week = substr($periodKey, 4, 2);
                        return "Semana {$week}, {$year}";
                    }
                    return "Semana {$periodKey}";

                case 'monthly':
                    if (strpos($periodKey, '-') !== false) {
                        list($year, $month) = explode('-', $periodKey);
                        return Carbon::createFromDate($year, $month, 1)->format('M Y');
                    }
                    return "Mes {$periodKey}";

                case 'quarterly':
                    if (strpos($periodKey, '-') !== false) {
                        list($year, $quarter) = explode('-', $periodKey);
                        return "Q{$quarter} {$year}";
                    }
                    return "Trimestre {$periodKey}";

                case 'yearly':
                    return "Año {$periodKey}";

                default:
                    return $periodKey;
            }
        } catch (\Exception $e) {
            // If there's an error, return the original value
            return $periodKey;
        }
    }
}
