@extends('layouts.app')

@section('title', 'Modifier dette')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Modifier la dette</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('debts.update', $debt->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nom du créancier *</label>
                        <input type="text" name="creditor" class="form-control" value="{{ old('creditor', $debt->creditor) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description', $debt->description) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant total *</label>
                            <div class="input-group">
                                <input type="number" name="amount" step="0.01" class="form-control" value="{{ old('amount', $debt->amount) }}" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reste à payer *</label>
                            <div class="input-group">
                                <input type="number" name="remaining" step="0.01" class="form-control" value="{{ old('remaining', $debt->remaining) }}" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début *</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $debt->start_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'échéance *</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $debt->due_date->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="supplier" {{ $debt->type == 'supplier' ? 'selected' : '' }}>Fournisseur</option>
                                <option value="loan" {{ $debt->type == 'loan' ? 'selected' : '' }}>Prêt</option>
                                <option value="tax" {{ $debt->type == 'tax' ? 'selected' : '' }}>Impôt</option>
                                <option value="other" {{ $debt->type == 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $debt->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paid" {{ $debt->status == 'paid' ? 'selected' : '' }}>Payée</option>
                                <option value="overdue" {{ $debt->status == 'overdue' ? 'selected' : '' }}>En retard</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Taux d'intérêt (%)</label>
                        <input type="number" name="interest" step="0.01" class="form-control" value="{{ old('interest', $debt->interest) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $debt->notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('debts.index') }}" class="btn btn-secondary">
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
