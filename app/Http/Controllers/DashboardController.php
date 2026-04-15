<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Debt;
use App\Models\Alert;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Statistiques générales
        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Statistiques du mois
        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Dernières transactions
        $recentTransactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Factures impayées
        $pendingInvoices = Invoice::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Dettes actives
        $activeDebts = Debt::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Données pour le graphique (6 derniers mois)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $income = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $expense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $chartData[] = [
                'month' => $month->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense
            ];
        }

        // Alertes non lues
        $alerts = Alert::where('user_id', $userId)
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'monthlyIncome', 'monthlyExpense',
            'recentTransactions', 'pendingInvoices', 'activeDebts',
            'chartData', 'alerts'
        ));
    }
}
