@extends('layouts.app')

@section('title', 'Analyse financière')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-brain"></i> Analyse financière intelligente</h2>
</div>

<!-- Cartes de synthèse -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary p-3 rounded-circle me-3">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Tendance</h6>
                        <h4 class="mb-0">
                            @php
                                $trend = $trends['trend'] ?? 'stable';
                            @endphp
                            @if($trend == 'positive')
                                <span class="text-success">📈 Positive</span>
                            @elseif($trend == 'negative')
                                <span class="text-danger">📉 Négative</span>
                            @else
                                <span class="text-warning">➡️ Stable</span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success p-3 rounded-circle me-3">
                        <i class="fas fa-chart-simple fa-2x text-white"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Variation dépenses</h6>
                        @php
                            $isIncreasing = $spending_analysis['is_increasing'] ?? false;
                            $variation = $spending_analysis['variation'] ?? 0;
                        @endphp
                        <h4 class="mb-0 {{ $isIncreasing ? 'text-danger' : 'text-success' }}">
                            {{ $isIncreasing ? '+' : '' }}{{ $variation }}%
                        </h4>
                        <small class="text-muted">par rapport au mois dernier</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info p-3 rounded-circle me-3">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Variation revenus</h6>
                        @php
                            $incomeIncreasing = $income_analysis['is_increasing'] ?? false;
                            $incomeVariation = $income_analysis['variation'] ?? 0;
                        @endphp
                        <h4 class="mb-0 {{ $incomeIncreasing ? 'text-success' : 'text-danger' }}">
                            {{ $incomeIncreasing ? '+' : '' }}{{ $incomeVariation }}%
                        </h4>
                        <small class="text-muted">par rapport au mois dernier</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommandations -->
<div class="card mb-4">
    <div class="card-header bg-warning">
        <h5><i class="fas fa-lightbulb"></i> Recommandations personnalisées</h5>
    </div>
    <div class="card-body">
        @php
            $recommendationsList = $recommendations ?? [];
        @endphp
        @forelse($recommendationsList as $rec)
            <div class="alert alert-{{ $rec['type'] ?? 'info' }} mb-3">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas {{ $rec['icon'] ?? 'fa-info-circle' }} fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $rec['title'] ?? 'Recommandation' }}</h6>
                        <p class="mb-2">{{ $rec['message'] ?? '' }}</p>
                        @if(isset($rec['details']) && is_array($rec['details']))
                            <div class="mb-2">
                                @foreach($rec['details'] as $detail)
                                    <span class="badge bg-secondary me-1">{{ $detail }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if(isset($rec['action_url']))
                            <a href="{{ $rec['action_url'] }}" class="btn btn-sm btn-outline-primary">
                                {{ $rec['action'] ?? 'Voir' }} <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <p class="mb-0">Excellent ! Aucune recommandation urgente pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Prévisions -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i> Tendances (3 derniers mois)</h5>
            </div>
            <div class="card-body">
                @php
                    $trendsData = $trends['data'] ?? [];
                @endphp
                @if(count($trendsData) > 0)
                    <canvas id="trendsChart" height="250"></canvas>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Pas assez de données pour afficher les tendances</p>
                        <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                            Ajouter des transactions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-simple"></i> Prévisions de trésorerie</h5>
            </div>
            <div class="card-body">
                @php
                    $forecastList = $forecast['forecast'] ?? [];
                @endphp
                @if(count($forecastList) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Solde prévu</th>
                                    <th>Statut</th>
                                 </tr>
                            </thead>
                            <tbody>
                                @foreach($forecastList as $pred)
                                <tr>
                                    <td>{{ $pred['month'] ?? '-' }}</td>
                                    <td class="{{ ($pred['status'] ?? '') == 'critical' ? 'text-danger' : (($pred['status'] ?? '') == 'warning' ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($pred['balance'] ?? 0, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td>
                                        @if(($pred['status'] ?? '') == 'critical')
                                            <span class="badge bg-danger">Critique</span>
                                        @elseif(($pred['status'] ?? '') == 'warning')
                                            <span class="badge bg-warning">Attention</span>
                                        @else
                                            <span class="badge bg-success">Bon</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        Revenu mensuel moyen: {{ number_format($forecast['avg_monthly_income'] ?? 0, 0, ',', ' ') }} FCFA<br>
                        Dépense mensuelle moyenne: {{ number_format($forecast['avg_monthly_expense'] ?? 0, 0, ',', ' ') }} FCFA
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Pas assez de données pour les prévisions</p>
                        <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                            Ajouter des transactions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top catégories dépenses -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-chart-pie"></i> Top 3 catégories les plus dépensières</h5>
    </div>
    <div class="card-body">
        @php
            $topCategories = $spending_analysis['top_categories'] ?? collect();
        @endphp
        @if(count($topCategories) > 0)
            <div class="row">
                @foreach($topCategories as $category => $data)
                    <div class="col-md-4">
                        <div class="card bg-light mb-3">
                            <div class="card-body text-center">
                                <h4 class="text-danger">{{ $category }}</h4>
                                <h3>{{ number_format($data['total'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                                <p class="mb-0">
                                    {{ $data['count'] ?? 0 }} transaction(s)<br>
                                    Moyenne: {{ number_format($data['average'] ?? 0, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <p class="text-muted">Ajoutez des dépenses pour voir les analyses par catégorie</p>
                <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                    Ajouter une dépense
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@php
    $trendsData = $trends['data'] ?? [];
@endphp
@if(count($trendsData) > 0)
<script>
    const ctx = document.getElementById('trendsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_column($trendsData, 'month')),
            datasets: [
                {
                    label: 'Revenus',
                    data: @json(array_column($trendsData, 'income')),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Dépenses',
                    data: @json(array_column($trendsData, 'expense')),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endif
@endpush
