<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();
        return view('expenses', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Expense::create($request->only('item_name', 'price'));

        // Redirect with a success message
        return redirect()->route('expenses')->with('success', 'Expense added successfully!');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $expense->update($request->only('item_name', 'price'));

        // Redirect with a success message
        return redirect()->route('expenses')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        // Redirect with a success message
        return redirect()->route('expenses')->with('success', 'Expense deleted successfully!');
    }
}