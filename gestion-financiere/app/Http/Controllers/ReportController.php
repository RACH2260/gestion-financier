<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Debt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function monthly(Request $request)
    {
        $user = Auth::user();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // Revenus du mois
        $incomes = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $totalIncome = $incomes->sum('amount');

        // Dépenses du mois
        $expenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $totalExpense = $expenses->sum('amount');

        // Dépenses par catégorie
        $expensesByCategory = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        // Factures du mois
        $invoices = Invoice::where('user_id', $user->id)
            ->whereMonth('issue_date', $month)
            ->whereYear('issue_date', $year)
            ->get();

        $pendingInvoices = $invoices->where('status', 'pending')->sum('total');

        // Solde
        $balance = $totalIncome - $totalExpense;

        return view('reports.monthly', compact(
            'month', 'year', 'incomes', 'expenses',
            'totalIncome', 'totalExpense', 'balance',
            'expensesByCategory', 'invoices', 'pendingInvoices'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $expensesByCategory = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        $data = [
            'user' => $user,
            'month' => $month,
            'year' => $year,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'expensesByCategory' => $expensesByCategory,
            'generated_at' => now()
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('rapport_mensuel_' . $month . '_' . $year . '.pdf');
    }
}
