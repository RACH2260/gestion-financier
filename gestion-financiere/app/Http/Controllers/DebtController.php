<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $debts = Debt::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        // Calculer les statistiques directement dans le contrôleur
        $stats = [
            'total' => $debts->sum('amount'),
            'remaining' => $debts->sum('remaining'),
            'paid' => $debts->sum('amount') - $debts->sum('remaining')
        ];

        return view('debts.index', compact('debts', 'stats'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'creditor' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'start_date' => 'required|date',
        ]);

        Debt::create([
            'creditor' => $request->creditor,
            'description' => $request->description,
            'amount' => $request->amount,
            'remaining' => $request->amount,
            'due_date' => $request->due_date,
            'start_date' => $request->start_date,
            'type' => $request->type ?? 'other',
            'interest' => $request->interest ?? 0,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('debts.index')->with('success', 'Dette enregistrée avec succès');
    }

    public function show($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        return view('debts.show', compact('debt'));
    }

    public function edit($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'creditor' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'remaining' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'start_date' => 'required|date',
        ]);

        $debt->update($request->all());

        return redirect()->route('debts.index')->with('success', 'Dette modifiée avec succès');
    }

    public function destroy($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Dette supprimée avec succès');
    }
}
