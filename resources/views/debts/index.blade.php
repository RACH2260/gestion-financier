@extends('layouts.app')

@section('title', 'Dettes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-hand-holding-usd"></i> Dettes</h2>
    <a href="{{ route('debts.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle dette
    </a>
</div>

<!-- Résumé - Calcul direct sans variable stats -->
<div class="row mb-4">
    @php
        $totalDettes = $debts->sum('amount');
        $totalRestant = $debts->sum('remaining');
        $totalPaye = $totalDettes - $totalRestant;
    @endphp
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total des dettes</h6>
                <h4>{{ number_format($totalDettes, 0, ',', ' ') }} FCFA</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Reste à payer</h6>
                <h4>{{ number_format($totalRestant, 0, ',', ' ') }} FCFA</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Déjà payé</h6>
                <h4>{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</h4>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Créancier</th>
                        <th>Description</th>
                        <th>Montant total</th>
                        <th>Reste à payer</th>
                        <th>Échéance</th>
                        <th>Progression</th>
                        <th>Statut</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($debts ?? [] as $debt)
                    @php
                        $progress = $debt->amount > 0 ? round(($debt->amount - $debt->remaining) / $debt->amount * 100) : 0;
                    @endphp
                    <tr>
                        <td>{{ $debt->creditor }}</td>
                        <td>{{ $debt->description }}</td>
                        <td>{{ number_format($debt->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="text-warning fw-bold">{{ number_format($debt->remaining, 0, ',', ' ') }} FCFA</td>
                        <td class="{{ $debt->due_date < now() && $debt->status != 'paid' ? 'text-danger' : '' }}">
                            {{ \Carbon\Carbon::parse($debt->due_date)->format('d/m/Y') }}
                         </td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                                    {{ $progress }}%
                                </div>
                            </div>
                         </td>
                        <td>
                            @if($debt->status == 'paid')
                                <span class="badge bg-success">Payée</span>
                            @elseif($debt->status == 'active')
                                <span class="badge bg-warning">Active</span>
                            @elseif($debt->status == 'overdue')
                                <span class="badge bg-danger">En retard</span>
                            @else
                                <span class="badge bg-secondary">{{ $debt->status }}</span>
                            @endif
                         </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('debts.show', $debt->id) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('debts.edit', $debt->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $debt->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Suppression -->
                            <div class="modal fade" id="deleteModal{{ $debt->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer cette dette ?
                                            <br>
                                            <strong>{{ $debt->creditor }}</strong> - {{ number_format($debt->amount, 0, ',', ' ') }} FCFA
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
                         </td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                            Aucune dette trouvée
                            <br>
                            <a href="{{ route('debts.create') }}" class="btn btn-sm btn-primary mt-2">
                                Ajouter une dette
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
