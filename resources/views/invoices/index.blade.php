@extends('layouts.app')

@section('title', 'Factures')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice"></i> Factures</h2>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle facture
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Date émission</th>
                        <th>Échéance</th>
                        <th>Statut</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices ?? [] as $invoice)
                    <tr>
                        <td>{{ $invoice->number }}</td>
                        <td>{{ $invoice->client_name }}</td>
                        <td>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</td>
                        <td class="{{ $invoice->due_date < now() && $invoice->status != 'paid' ? 'text-danger' : '' }}">
                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success">Payée</span>
                            @elseif($invoice->status == 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($invoice->status == 'overdue')
                                <span class="badge bg-danger">En retard</span>
                            @else
                                <span class="badge bg-secondary">{{ $invoice->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $invoice->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Suppression -->
                            <div class="modal fade" id="deleteModal{{ $invoice->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer la facture {{ $invoice->number }} ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST">
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
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                            Aucune facture trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
