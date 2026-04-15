<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FinancialAnalysisService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Analyse complète des finances
     */
    public function analyze()
    {
        return [
            'spending_analysis' => $this->analyzeSpending(),
            'income_analysis' => $this->analyzeIncome(),
            'recommendations' => $this->generateRecommendations(),
            'trends' => $this->analyzeTrends(),
            'cash_flow_forecast' => $this->forecastCashFlow(),
            'alerts' => $this->generateAlerts()
        ];
    }

    /**
     * Analyse des dépenses
     */
    public function analyzeSpending()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;

        // Dépenses du mois courant
        $currentExpenses = Transaction::where('user_id', $this->user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // Dépenses du mois dernier
        $lastExpenses = Transaction::where('user_id', $this->user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $lastMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // Dépenses par catégorie
        $expensesByCategory = Transaction::where('user_id', $this->user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                    'average' => $items->avg('amount')
                ];
            });

        // Catégories les plus dépensières
        $topCategories = $expensesByCategory->sortByDesc('total')->take(3);

        // Variation par rapport au mois dernier
        $variation = $lastExpenses > 0
            ? (($currentExpenses - $lastExpenses) / $lastExpenses) * 100
            : 0;

        return [
            'current_total' => $currentExpenses,
            'last_total' => $lastExpenses,
            'variation' => round($variation, 2),
            'by_category' => $expensesByCategory,
            'top_categories' => $topCategories,
            'is_increasing' => $variation > 0
        ];
    }

    /**
     * Analyse des revenus
     */
    public function analyzeIncome()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;

        $currentIncome = Transaction::where('user_id', $this->user->id)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $lastIncome = Transaction::where('user_id', $this->user->id)
            ->where('type', 'income')
            ->whereMonth('date', $lastMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $variation = $lastIncome > 0
            ? (($currentIncome - $lastIncome) / $lastIncome) * 100
            : 0;

        return [
            'current_total' => $currentIncome,
            'last_total' => $lastIncome,
            'variation' => round($variation, 2),
            'is_increasing' => $variation > 0
        ];
    }

    /**
     * Générer des recommandations intelligentes
     */
    public function generateRecommendations()
    {
        $recommendations = [];
        $spending = $this->analyzeSpending();
        $income = $this->analyzeIncome();

        // Recommandation 1: Si les dépenses augmentent
        if ($spending['is_increasing'] && $spending['variation'] > 10) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fa-chart-line',
                'title' => 'Augmentation des dépenses',
                'message' => "Vos dépenses ont augmenté de {$spending['variation']}% ce mois-ci. " .
                             "Analysez les catégories suivantes :",
                'details' => $spending['top_categories']->keys()->toArray(),
                'action' => 'Voir les transactions',
                'action_url' => route('transactions.index', ['type' => 'expense'])
            ];
        }

        // Recommandation 2: Identifier les catégories coûteuses
        foreach ($spending['top_categories'] as $category => $data) {
            if ($data['total'] > 100000) { // Seuil de 100 000 FCFA
                $recommendations[] = [
                    'type' => 'info',
                    'icon' => 'fa-tag',
                    'title' => "Dépenses élevées en {$category}",
                    'message' => "Vous avez dépensé " . number_format($data['total'], 0, ',', ' ') .
                                 " FCFA en {$category} ce mois-ci. " .
                                 "Essayez de réduire ces dépenses de 10% pour économiser " .
                                 number_format($data['total'] * 0.1, 0, ',', ' ') . " FCFA.",
                    'action' => 'Analyser',
                    'action_url' => route('transactions.index', ['category' => $category])
                ];
            }
        }

        // Recommandation 3: Si le solde est négatif
        $balance = $income['current_total'] - $spending['current_total'];
        if ($balance < 0) {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Solde négatif',
                'message' => "Votre solde est négatif de " . number_format(abs($balance), 0, ',', ' ') .
                             " FCFA. Réduisez vos dépenses ou augmentez vos revenus.",
                'action' => 'Voir les recommandations',
                'action_url' => '#'
            ];
        }

        // Recommandation 4: Suggestions d'économies
        $potentialSavings = $spending['current_total'] * 0.15;
        if ($potentialSavings > 50000) {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'fa-piggy-bank',
                'title' => 'Potentiel d\'économie',
                'message' => "En réduisant vos dépenses de 15%, vous pourriez économiser " .
                             number_format($potentialSavings, 0, ',', ' ') . " FCFA par mois.",
                'action' => 'Voir les conseils',
                'action_url' => '#'
            ];
        }

        // Recommandation 5: Factures impayées
        $unpaidInvoices = \App\Models\Invoice::where('user_id', $this->user->id)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('total');

        if ($unpaidInvoices > 0) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fa-file-invoice',
                'title' => 'Factures impayées',
                'message' => "Vous avez " . number_format($unpaidInvoices, 0, ',', ' ') .
                             " FCFA de factures en retard. Relancez vos clients.",
                'action' => 'Voir les factures',
                'action_url' => route('invoices.index')
            ];
        }

        // Recommandation 6: Dettes
        $activeDebts = \App\Models\Debt::where('user_id', $this->user->id)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->sum('remaining');

        if ($activeDebts > 0) {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => 'fa-hand-holding-usd',
                'title' => 'Dettes en retard',
                'message' => "Vous avez " . number_format($activeDebts, 0, ',', ' ') .
                             " FCFA de dettes en retard. Contactez vos créanciers.",
                'action' => 'Voir les dettes',
                'action_url' => route('debts.index')
            ];
        }

        return $recommendations;
    }

    /**
     * Analyse des tendances (3 derniers mois)
     */
    public function analyzeTrends()
    {
        $trends = [];

        for ($i = 2; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $income = Transaction::where('user_id', $this->user->id)
                ->where('type', 'income')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $expense = Transaction::where('user_id', $this->user->id)
                ->where('type', 'expense')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $trends[] = [
                'month' => $month->format('F'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense
            ];
        }

        // Calculer la tendance
        $firstBalance = $trends[0]['balance'] ?? 0;
        $lastBalance = $trends[2]['balance'] ?? 0;

        return [
            'data' => $trends,
            'trend' => $lastBalance > $firstBalance ? 'positive' : ($lastBalance < $firstBalance ? 'negative' : 'stable'),
            'evolution' => $firstBalance > 0 ? (($lastBalance - $firstBalance) / $firstBalance) * 100 : 0
        ];
    }

    /**
     * Prévision de trésorerie (3 mois)
     */
    public function forecastCashFlow()
    {
        $avgIncome = Transaction::where('user_id', $this->user->id)
            ->where('type', 'income')
            ->where('date', '>=', now()->subMonths(3))
            ->avg('amount');

        $avgExpense = Transaction::where('user_id', $this->user->id)
            ->where('type', 'expense')
            ->where('date', '>=', now()->subMonths(3))
            ->avg('amount');

        $currentBalance = $this->user->balance;

        $forecast = [];
        $balance = $currentBalance;

        for ($i = 1; $i <= 3; $i++) {
            $balance += ($avgIncome - $avgExpense);
            $forecast[] = [
                'month' => now()->addMonths($i)->format('F'),
                'balance' => $balance,
                'status' => $balance < 0 ? 'critical' : ($balance < 50000 ? 'warning' : 'good')
            ];
        }

        return [
            'avg_monthly_income' => $avgIncome,
            'avg_monthly_expense' => $avgExpense,
            'current_balance' => $currentBalance,
            'forecast' => $forecast
        ];
    }

    /**
     * Générer des alertes intelligentes
     */
    public function generateAlerts()
    {
        $alerts = [];
        $spending = $this->analyzeSpending();
        $forecast = $this->forecastCashFlow();

        // Alerte si les dépenses dépassent 80% du budget
        $budget = \App\Models\Budget::where('user_id', $this->user->id)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->first();

        if ($budget && $spending['current_total'] > $budget->amount * 0.8) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Vous avez déjà utilisé " . round(($spending['current_total'] / $budget->amount) * 100) .
                             "% de votre budget mensuel.",
                'created_at' => now()
            ];
        }

        // Alerte sur les prévisions
        foreach ($forecast['forecast'] as $pred) {
            if ($pred['status'] == 'critical') {
                $alerts[] = [
                    'type' => 'danger',
                    'message' => "Prévision: Votre trésorerie pourrait devenir négative en {$pred['month']}.",
                    'created_at' => now()
                ];
                break;
            }
        }

        return $alerts;
    }
}
