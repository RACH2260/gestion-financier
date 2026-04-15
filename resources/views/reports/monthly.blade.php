@extends('layouts.app')

@section('title', 'Rapport mensuel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar"></i> Rapport mensuel</h2>
    <div>
        <a href="{{ route('reports.export-pdf', ['month' => $month, 'year' => $year]) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Exporter PDF
        </a>
    </div>
</div>

<!-- Sélecteur de mois -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Mois</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Année</label>
                <select name="year" class="form-select">
                    @for($y = now()->year - 2; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Voir le rapport</button>
            </div>
        </form>
    </div>
</div>

<!-- Résumé -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Revenus</h6>
                <h3>{{ number_format($totalIncome, 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Dépenses</h6>
                <h3>{{ number_format($totalExpense, 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Solde</h6>
                <h3 class="{{ $balance < 0 ? 'text-warning' : '' }}">
                    {{ number_format($balance, 0, ',', ' ') }} FCFA
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Dépenses par catégorie -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Dépenses par catégorie</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Factures du mois -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Factures du mois</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->number }}</td>
                                <td>{{ $invoice->client_name }}</td>
                                <td>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                        <span class="badge bg-success">Payée</span>
                                    @else
                                        <span class="badge bg-warning">En attente</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucune facture</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pendingInvoices > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        Total des factures impayées: {{ number_format($pendingInvoices, 0, ',', ' ') }} FCFA
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Liste des transactions -->
<div class="card">
    <div class="card-header">
        <h5>Détail des transactions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomes as $income)
                    <tr class="table-success">
                        <td>{{ $income->date->format('d/m/Y') }}</td>
                        <td>{{ $income->description }}</td>
                        <td>{{ $income->category->name }}</td>
                        <td>Revenu</td>
                        <td class="text-success">+ {{ number_format($income->amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                    @foreach($expenses as $expense)
                    <tr class="table-danger">
                        <td>{{ $expense->date->format('d/m/Y') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->category->name }}</td>
                        <td>Dépense</td>
                        <td class="text-danger">- {{ number_format($expense->amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($expensesByCategory->toArray())),
            datasets: [{
                data: @json(array_values($expensesByCategory->toArray())),
                backgroundColor: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC489A', '#14B8A6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
