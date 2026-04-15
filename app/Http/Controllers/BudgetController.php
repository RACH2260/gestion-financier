<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $budgets = Budget::where('user_id', Auth::id())->orderBy('year', 'desc')->orderBy('month', 'asc')->get();
        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        return view('budgets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,quarterly,yearly',
            'year' => 'required|numeric|min:2000|max:2100',
        ]);

        Budget::create([
            'amount' => $request->amount,
            'period' => $request->period,
            'year' => $request->year,
            'month' => $request->month,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('budgets.index')->with('success', 'Budget défini avec succès');
    }

    public function show($id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
        return view('budgets.show', compact('budget'));
    }

    public function edit($id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,quarterly,yearly',
            'year' => 'required|numeric|min:2000|max:2100',
        ]);

        $budget->update([
            'amount' => $request->amount,
            'period' => $request->period,
            'year' => $request->year,
            'month' => $request->month,
        ]);

        return redirect()->route('budgets.index')->with('success', 'Budget modifié avec succès');
    }

    public function destroy($id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget supprimé avec succès');
    }
}
