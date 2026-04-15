@extends('layouts.app')

@section('title', 'Détail transaction')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-info-circle"></i> Détail de la transaction</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Date :</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Description :</div>
                    <div class="col-md-8">{{ $transaction->description }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Montant :</div>
                    <div class="col-md-8">
                        <span class="{{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }} fw-bold">
                            {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Type :</div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                            {{ $transaction->type == 'income' ? 'Revenu' : 'Dépense' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Catégorie :</div>
                    <div class="col-md-8">
                        <span class="badge" style="background-color: {{ $transaction->category->color ?? '#6c757d' }}">
                            {{ $transaction->category->name ?? 'Non catégorisé' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Méthode de paiement :</div>
                    <div class="col-md-8">{{ $transaction->payment_method ?? 'Non spécifié' }}</div>
                </div>
                @if($transaction->notes)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Notes :</div>
                    <div class="col-md-8">{{ $transaction->notes }}</div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <div>
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimer cette transaction ?
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
@endsection
