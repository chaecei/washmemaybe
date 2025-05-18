<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $categoryLabels = Expense::pluck('category')->toArray();

        $minYear = min(
            Order::min(DB::raw('YEAR(created_at)')) ?? now()->year,
            Expense::min(DB::raw('YEAR(date)')) ?? now()->year
        );

        $maxYear = max(
            Order::max(DB::raw('YEAR(created_at)')) ?? now()->year,
            Expense::max(DB::raw('YEAR(date)')) ?? now()->year,
            now()->year
        );
        $month = $request->input('month'); // nullable
        $week = $request->input('week');

        if ($month && !$week) {
            $week = 1;
        }
        
        $months = collect(range(1, 12))
            ->map(fn($month) => Carbon::create()->month($month)->format('F'))
            ->values()   // this resets the keys to 0,1,2,...
            ->toArray();

        if ($month) {
            $expensesQuery = Expense::whereYear('date', $year)
        ->whereMonth('date', $month);

        $earningsQuery = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month);

        if ($week) {
            $weekRanges = [
                1 => [1, 7],
                2 => [8, 14],
                3 => [15, 21],
                4 => [22, 31],
            ];


            [$startDay, $endDay] = $weekRanges[$week];

            $expensesQuery->whereDay('date', '>=', $startDay)
                        ->whereDay('date', '<=', $endDay);

            $earningsQuery->whereDay('created_at', '>=', $startDay)
                        ->whereDay('created_at', '<=', $endDay);

            // Adjust labels for the week instead of full month
            $labels = collect(range($startDay, $endDay));
        } else {
            $daysInMonth = Carbon::create($year, (int) $month, 1)->daysInMonth;
            $labels = collect(range(1, $daysInMonth));
        }

        // Group by day still works whether weekly or full month
        $expensesData = $expensesQuery->selectRaw('DAY(date) as day, SUM(amount) as total')
                                    ->groupBy('day')
                                    ->orderBy('day')
                                    ->get();

        $earningsData = $earningsQuery->selectRaw('DAY(created_at) as day, SUM(grand_total) as total')
                                    ->groupBy('day')
                                    ->orderBy('day')
                                    ->get();

        $dailyExpenses = $labels->map(function ($day) use ($expensesData) {
            $match = $expensesData->firstWhere('day', $day);
            return $match ? $match->total : 0;
        });

        $dailyEarnings = $labels->map(function ($day) use ($earningsData) {
            $match = $earningsData->firstWhere('day', $day);
            return $match ? $match->total : 0;
        });

        $expenses = $dailyExpenses;
        $earnings = $dailyEarnings;

        $totalEarnings = $dailyEarnings->sum();
        $totalExpenses = $dailyExpenses->sum();

        // ✅ NEW: Group expenses by category (for pie chart/bar chart)
        $categoryBreakdown = Expense::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->when($week, function ($query) use ($weekRanges, $week) {
                [$startDay, $endDay] = $weekRanges[$week];
                $query->whereDay('date', '>=', $startDay)
                    ->whereDay('date', '<=', $endDay);
            })
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();
    } else {
            // Expenses grouped by month in the selected year
            $expensesData = Expense::whereYear('date', $year)
                ->selectRaw('MONTH(date) as month, SUM(amount) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            

            // Earnings grouped by month in the selected year
            $earningsData = Order::whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = collect(range(1, 12));

            $expenses = $labels->map(function ($m) use ($expensesData) {
                $match = $expensesData->firstWhere('month', $m);
                return $match ? $match->total : 0;
            });

            $earnings = $labels->map(function ($m) use ($earningsData) {
                $match = $earningsData->firstWhere('month', $m);
                return $match ? $match->total : 0;
            });

            $totalEarnings = $earningsData->sum('total');
            $totalExpenses = $expensesData->sum('total');
        }

        $profit = $totalEarnings - $totalExpenses;

        return view('reports', compact(
            'year', 'month', 'months',
            'labels', 'expenses', 'earnings',
            'totalEarnings', 'totalExpenses', 'profit',
            'minYear', 'maxYear'
        ));
    }
}
