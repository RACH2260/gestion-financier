@extends('layouts.app')

@section('title', 'Nouvelle facture')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle"></i> Nouvelle facture</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">N° Facture</label>
                        <input type="text" name="number" class="form-control" value="INV-{{ date('Ymd') }}-{{ rand(100, 999) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom du client *</label>
                        <input type="text" name="client_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email du client</label>
                            <input type="email" name="client_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone du client</label>
                            <input type="text" name="client_phone" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse du client</label>
                        <textarea name="client_address" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant HT *</label>
                            <div class="input-group">
                                <input type="number" name="amount" step="0.01" class="form-control" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">TVA (%)</label>
                            <div class="input-group">
                                <input type="number" name="tax" step="0.01" class="form-control" value="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'émission *</label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'échéance *</label>
                            <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer la facture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
