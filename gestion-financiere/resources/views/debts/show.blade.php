@extends('layouts.app')

@section('title', 'Détail dette')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-hand-holding-usd"></i> Détail de la dette</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Créancier :</div>
                    <div class="col-md-8">{{ $debt->creditor }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Description :</div>
                    <div class="col-md-8">{{ $debt->description }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Montant total :</div>
                    <div class="col-md-8">{{ number_format($debt->amount, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Déjà payé :</div>
                    <div class="col-md-8 text-success">{{ number_format($debt->amount - $debt->remaining, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Reste à payer :</div>
                    <div class="col-md-8 text-warning fw-bold">{{ number_format($debt->remaining, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Progression :</div>
                    <div class="col-md-8">
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $debt->getProgressAttribute() }}%">
                                {{ round($debt->getProgressAttribute()) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Date de début :</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($debt->start_date)->format('d/m/Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Date d'échéance :</div>
                    <div class="col-md-8">
                        {{ \Carbon\Carbon::parse($debt->due_date)->format('d/m/Y') }}
                        @if($debt->due_date < now() && $debt->status != 'paid')
                            <span class="badge bg-danger ms-2">En retard</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Type :</div>
                    <div class="col-md-8">
                        @switch($debt->type)
                            @case('supplier') Fournisseur @break
                            @case('loan') Prêt @break
                            @case('tax') Impôt @break
                            @default Autre
                        @endswitch
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Statut :</div>
                    <div class="col-md-8">
                        @if($debt->status == 'paid')
                            <span class="badge bg-success">Payée</span>
                        @elseif($debt->status == 'active')
                            <span class="badge bg-warning">Active</span>
                        @else
                            <span class="badge bg-danger">En retard</span>
                        @endif
                    </div>
                </div>
                @if($debt->interest)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Taux d'intérêt :</div>
                    <div class="col-md-8">{{ $debt->interest }}%</div>
                </div>
                @endif
                @if($debt->notes)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Notes :</div>
                    <div class="col-md-8">{{ $debt->notes }}</div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('debts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <div>
                        <a href="{{ route('debts.edit', $debt->id) }}" class="btn btn-warning">
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
                Voulez-vous vraiment supprimer cette dette ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('debts.destroy', $debt->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
