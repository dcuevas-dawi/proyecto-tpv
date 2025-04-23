<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    // Show the accounting report form
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

    // Process the accounting report
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
        // Define the cutoff hour for the business day (6am)
        // Using 6am as the cutoff hour to consider that a day ends
        $cutoffHour = 6;

        // We will use SQLite date functions to group the data
        $dateExpression = "";
        $periodFormat = "";

        switch ($period) {
            case 'daily':
                // We consider that the business day goes until 6am of the next day
                $dateExpression = "date(datetime(closed_at, '-{$cutoffHour} hours'))";
                $periodFormat = 'd/m/Y';
                break;
            case 'weekly':
                $dateExpression = "strftime('%Y-W%W', datetime(closed_at, '-{$cutoffHour} hours'))";
                $periodFormat = 'semana';
                break;
            case 'monthly':
                $dateExpression = "strftime('%Y-%m', datetime(closed_at, '-{$cutoffHour} hours'))";
                $periodFormat = 'mes';
                break;
            case 'quarterly':
                $dateExpression = "strftime('%Y-', datetime(closed_at, '-{$cutoffHour} hours')) || ((CAST(strftime('%m', datetime(closed_at, '-{$cutoffHour} hours')) AS INTEGER) + 2) / 3)";
                $periodFormat = 'trimestre';
                break;
            case 'yearly':
                $dateExpression = "strftime('%Y', datetime(closed_at, '-{$cutoffHour} hours'))";
                $periodFormat = 'año';
                break;
        }

        // Query using Eloquent with conditions for SQLite
        $query = Order::where('status', 'cerrado');

        // Adjust the filter dates to include the first hours of the next day
        $endDateForQuery = Carbon::parse($endDate)->addDay()->format('Y-m-d');

        $query->whereRaw("strftime('%Y-%m-%d', closed_at) >= ?", [$startDate])
            ->whereRaw("strftime('%Y-%m-%d', closed_at) <= ?", [$endDateForQuery]);

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

    // Format the period label based on the type
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
