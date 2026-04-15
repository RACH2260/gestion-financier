@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-exchange-alt"></i> Transactions</h2>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle transaction
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label>Type</label>
                <select name="type" class="form-select">
                    <option value="all">Tous</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Revenus</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépenses</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Catégorie</label>
                <select name="category" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Date début</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label>Date fin</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des transactions -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions ?? [] as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $transaction->category->color ?? '#6c757d' }}">
                                {{ $transaction->category->name ?? 'Non catégorisé' }}
                            </span>
                        </td>
                        <td class="{{ $transaction->type == 'income' ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                            {{ $transaction->type == 'income' ? '+' : '-' }}
                            {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td>
                            @if($transaction->payment_method)
                                @switch($transaction->payment_method)
                                    @case('cash') <i class="fas fa-money-bill"></i> Espèces @break
                                    @case('bank') <i class="fas fa-university"></i> Banque @break
                                    @case('mobile') <i class="fas fa-mobile-alt"></i> Mobile @break
                                    @default {{ $transaction->payment_method }}
                                @endswitch
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $transaction->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Suppression -->
                            <div class="modal fade" id="deleteModal{{ $transaction->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer cette transaction ?
                                            <br>
                                            <strong>{{ $transaction->description }}</strong>
                                            <br>
                                            Montant: {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                            Aucune transaction trouvée
                            <br>
                            <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary mt-2">
                                Ajouter une transaction
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $transactions->withQueryString()->links() ?? '' }}
    </div>
</div>
@endsection
