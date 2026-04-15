@extends('layouts.app')

@section('title', 'Détail budget')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-chart-simple"></i> Détail du budget</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Période :</div>
                    <div class="col-md-8">
                        @if($budget->period == 'monthly')
                            Mensuel
                        @elseif($budget->period == 'quarterly')
                            Trimestriel
                        @else
                            Annuel
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Année :</div>
                    <div class="col-md-8">{{ $budget->year }}</div>
                </div>
                @if($budget->month)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Mois :</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::create()->month($budget->month)->format('F') }}</div>
                </div>
                @endif
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Budget prévu :</div>
                    <div class="col-md-8">{{ number_format($budget->amount, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Dépenses réalisées :</div>
                    <div class="col-md-8 text-danger">{{ number_format($budget->getSpentAttribute(), 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Reste :</div>
                    <div class="col-md-8">
                        <span class="{{ $budget->getRemainingAttribute() < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ number_format($budget->getRemainingAttribute(), 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Progression :</div>
                    <div class="col-md-8">
                        @php
                            $percent = min(100, ($budget->getSpentAttribute() / $budget->amount) * 100);
                        @endphp
                        <div class="progress">
                            <div class="progress-bar {{ $percent > 100 ? 'bg-danger' : ($percent > 80 ? 'bg-warning' : 'bg-success') }}"
                                 style="width: {{ $percent }}%">
                                {{ round($percent) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Statut :</div>
                    <div class="col-md-8">
                        @if($budget->getRemainingAttribute() < 0)
                            <span class="badge bg-danger">Budget dépassé</span>
                        @elseif($percent > 80)
                            <span class="badge bg-warning">Attention</span>
                        @else
                            <span class="badge bg-success">Dans les limites</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('budgets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <div>
                        <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-warning">
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
                Voulez-vous vraiment supprimer ce budget ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
