@extends('layouts.app')

@section('title', 'Nouvelle dette')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle"></i> Nouvelle dette</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('debts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nom du créancier *</label>
                        <input type="text" name="creditor" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant total *</label>
                            <div class="input-group">
                                <input type="number" name="amount" step="0.01" class="form-control" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type de dette</label>
                            <select name="type" class="form-select">
                                <option value="supplier">Fournisseur</option>
                                <option value="loan">Prêt</option>
                                <option value="tax">Impôt</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début *</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'échéance *</label>
                            <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Taux d'intérêt (%)</label>
                        <input type="number" name="interest" step="0.01" class="form-control" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('debts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
