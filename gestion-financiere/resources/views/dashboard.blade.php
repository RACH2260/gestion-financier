@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="row">
    <!-- Cartes de statistiques -->
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Revenus totaux</h6>
                        <h3>{{ number_format($totalIncome ?? 0, 0, ',', ' ') }} FCFA</h3>
                    </div>
                    <i class="fas fa-arrow-up fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Dépenses totales</h6>
                        <h3>{{ number_format($totalExpense ?? 0, 0, ',', ' ') }} FCFA</h3>
                    </div>
                    <i class="fas fa-arrow-down fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Solde</h6>
                        <h3 class="{{ ($balance ?? 0) < 0 ? 'text-warning' : '' }}">
                            {{ number_format($balance ?? 0, 0, ',', ' ') }} FCFA
                        </h3>
                    </div>
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Dépenses du mois</h6>
                        <h3>{{ number_format($monthlyExpense ?? 0, 0, ',', ' ') }} FCFA</h3>
                    </div>
                    <i class="fas fa-calendar fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i> Évolution des finances</h5>
            </div>
            <div class="card-body">
                <canvas id="financeChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5><i class="fas fa-bell"></i> Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Nouvelle transaction
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> Voir toutes les transactions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Dernières transactions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Catégorie</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($recentTransactions ?? []) as $transaction)
                             <tr>
                                 <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                                 <td>{{ $transaction->description }}</td>
                                 <td>{{ $transaction->category->name ?? 'Non catégorisé' }}</td>
                                 <td class="{{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                     {{ $transaction->type == 'income' ? '+' : '-' }}
                                     {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                 </td>
                             </tr>
                            @empty
                             <tr>
                                 <td colspan="4" class="text-center">Aucune transaction pour le moment</td>
                             </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_column($chartData ?? [], 'month')),
            datasets: [
                {
                    label: 'Revenus',
                    data: @json(array_column($chartData ?? [], 'income')),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Dépenses',
                    data: @json(array_column($chartData ?? [], 'expense')),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
