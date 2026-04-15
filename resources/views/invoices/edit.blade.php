@extends('layouts.app')

@section('title', 'Modifier facture')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Modifier la facture</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">N° Facture</label>
                        <input type="text" name="number" class="form-control" value="{{ $invoice->number }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom du client *</label>
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $invoice->client_name) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email du client</label>
                            <input type="email" name="client_email" class="form-control" value="{{ old('client_email', $invoice->client_email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone du client</label>
                            <input type="text" name="client_phone" class="form-control" value="{{ old('client_phone', $invoice->client_phone) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse du client</label>
                        <textarea name="client_address" class="form-control" rows="2">{{ old('client_address', $invoice->client_address) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant HT *</label>
                            <div class="input-group">
                                <input type="number" name="amount" step="0.01" class="form-control" value="{{ old('amount', $invoice->amount) }}" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">TVA (%)</label>
                            <div class="input-group">
                                <input type="number" name="tax" step="0.01" class="form-control" value="{{ old('tax', $invoice->tax) }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'émission *</label>
                            <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'échéance *</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Payée</option>
                            <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
