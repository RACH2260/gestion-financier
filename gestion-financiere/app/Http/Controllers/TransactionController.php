<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Transaction::where('user_id', $userId)->with('category');

        // Filtres
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(20);
        $categories = Category::where('user_id', $userId)->get();

        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');

        return view('transactions.index', compact('transactions', 'categories', 'totalIncome', 'totalExpense'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        Transaction::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
        ]);

        // Vérifier le budget
        $this->checkBudget();

        // Vérifier la trésorerie
        $this->checkCashFlow();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction enregistrée avec succès');
    }

    // UNE SEULE METHODE show() - PAS DE DOUBLON
    public function show($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->with('category')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    // UNE SEULE METHODE edit()
    public function edit($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    // UNE SEULE METHODE update()
    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction modifiée avec succès');
    }

    // UNE SEULE METHODE destroy()
    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction supprimée avec succès');
    }

    private function checkBudget()
    {
        $userId = Auth::id();
        $currentBudget = Budget::where('user_id', $userId)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->first();

        if ($currentBudget) {
            $spent = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->sum('amount');

            if ($spent > $currentBudget->amount) {
                Alert::create([
                    'user_id' => $userId,
                    'type' => 'budget',
                    'message' => "Vous avez dépassé votre budget mensuel de " .
                                 number_format($spent - $currentBudget->amount, 0, ',', ' ') . " FCFA",
                    'data' => json_encode(['budget_id' => $currentBudget->id])
                ]);
            }
        }
    }

    private function checkCashFlow()
    {
        $userId = Auth::id();
        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        if ($balance < 0) {
            Alert::create([
                'user_id' => $userId,
                'type' => 'cashflow',
                'message' => "⚠️ Alerte critique: Votre trésorerie est négative (" .
                             number_format($balance, 0, ',', ' ') . " FCFA)",
                'data' => json_encode(['balance' => $balance])
            ]);
        } elseif ($balance < 50000) {
            Alert::create([
                'user_id' => $userId,
                'type' => 'cashflow',
                'message' => "⚠️ Attention: Votre trésorerie est faible (" .
                             number_format($balance, 0, ',', ' ') . " FCFA)",
                'data' => json_encode(['balance' => $balance])
            ]);
        }
    }
}
