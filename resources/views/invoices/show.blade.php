@extends('layouts.app')

@section('title', 'Détail facture')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-file-invoice"></i> Détail de la facture</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">N° Facture :</div>
                    <div class="col-md-8">{{ $invoice->number }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Client :</div>
                    <div class="col-md-8">{{ $invoice->client_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Email :</div>
                    <div class="col-md-8">{{ $invoice->client_email ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Téléphone :</div>
                    <div class="col-md-8">{{ $invoice->client_phone ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Adresse :</div>
                    <div class="col-md-8">{{ $invoice->client_address ?? '-' }}</div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Montant HT :</div>
                    <div class="col-md-8">{{ number_format($invoice->amount, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">TVA :</div>
                    <div class="col-md-8">{{ number_format($invoice->tax, 0, ',', ' ') }} %</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Total TTC :</div>
                    <div class="col-md-8"><strong>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</strong></div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Date d'émission :</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Date d'échéance :</div>
                    <div class="col-md-8">
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                        @if($invoice->due_date < now() && $invoice->status != 'paid')
                            <span class="badge bg-danger ms-2">En retard</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Statut :</div>
                    <div class="col-md-8">
                        @if($invoice->status == 'paid')
                            <span class="badge bg-success">Payée le {{ \Carbon\Carbon::parse($invoice->paid_date)->format('d/m/Y') }}</span>
                        @elseif($invoice->status == 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @elseif($invoice->status == 'overdue')
                            <span class="badge bg-danger">En retard</span>
                        @else
                            <span class="badge bg-secondary">{{ $invoice->status }}</span>
                        @endif
                    </div>
                </div>
                @if($invoice->notes)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Notes :</div>
                    <div class="col-md-8">{{ $invoice->notes }}</div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <div>
                        @if($invoice->status != 'paid')
                        <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Marquer cette facture comme payée ?')">
                                <i class="fas fa-check-circle"></i> Marquer payée
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">
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
@endsection
