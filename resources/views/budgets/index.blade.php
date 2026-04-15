@extends('layouts.app')

@section('title', 'Budgets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-simple"></i> Budgets</h2>
    <a href="{{ route('budgets.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau budget
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Période</th>
                        <th>Année</th>
                        <th>Mois</th>
                        <th>Budget prévu</th>
                        <th>Dépenses réalisées</th>
                        <th>Reste</th>
                        <th>Progression</th>
                        <th>Statut</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgets ?? [] as $budget)
                    @php
                        $spent = $budget->getSpentAttribute();
                        $remaining = $budget->getRemainingAttribute();
                        $percent = $budget->amount > 0 ? min(100, ($spent / $budget->amount) * 100) : 0;
                    @endphp
                    <tr>
                        <td>
                            @if($budget->period == 'monthly') Mensuel
                            @elseif($budget->period == 'quarterly') Trimestriel
                            @else Annuel
                            @endif
                         </td>
                        <td>{{ $budget->year }}</td>
                        <td>
                            @if($budget->month)
                                {{ \Carbon\Carbon::create()->month($budget->month)->format('F') }}
                            @else
                                -
                            @endif
                         </td>
                        <td>{{ number_format($budget->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="text-danger">{{ number_format($spent, 0, ',', ' ') }} FCFA</td>
                        <td class="{{ $remaining < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ number_format($remaining, 0, ',', ' ') }} FCFA
                         </td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $percent > 100 ? 'bg-danger' : ($percent > 80 ? 'bg-warning' : 'bg-success') }}"
                                     style="width: {{ $percent }}%">
                                    {{ round($percent) }}%
                                </div>
                            </div>
                         </td>
                        <td>
                            @if($remaining < 0)
                                <span class="badge bg-danger">Dépassé</span>
                            @elseif($percent > 80)
                                <span class="badge bg-warning">Attention</span>
                            @else
                                <span class="badge bg-success">Bon</span>
                            @endif
                         </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('budgets.show', $budget->id) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $budget->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Suppression -->
                            <div class="modal fade" id="deleteModal{{ $budget->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer ce budget ?
                                            <br>
                                            <strong>{{ number_format($budget->amount, 0, ',', ' ') }} FCFA</strong> pour {{ $budget->year }}
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
                         </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                            Aucun budget défini
                            <br>
                            <a href="{{ route('budgets.create') }}" class="btn btn-sm btn-primary mt-2">
                                Définir un budget
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
