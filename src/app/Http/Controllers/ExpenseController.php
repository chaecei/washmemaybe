<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // Show all expenses
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        $totalExpenses = $expenses->sum('amount');
        $mode = 'index';

        
    // Prepare data for chart (example: by month)
        $monthlyExpenses = Expense::selectRaw('MONTH(date) as month, SUM(amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_amount', 'month');

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        return view('expenses', compact('expenses', 'totalExpenses', 'monthlyExpenses', 'months', 'mode'));
    }

    // Show form to create expense
    public function create()
    {
        $mode = 'create';
        return view('expenses', compact('mode'));
    }

    // Store new expense
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    // Show form to edit an existing expense
    public function edit(Expense $expense)
    {
        $mode = 'edit';
        return view('expenses', compact('expense', 'mode'));
    }

    // Update an existing expense
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    // Delete an expense
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
