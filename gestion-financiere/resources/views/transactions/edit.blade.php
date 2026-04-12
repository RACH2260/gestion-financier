@extends('layouts.app')

@section('title', 'Modifier transaction')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Modifier la transaction</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description', $transaction->description) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant *</label>
                            <div class="input-group">
                                <input type="number" name="amount" step="0.01" class="form-control" value="{{ old('amount', $transaction->amount) }}" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="income" {{ old('type', $transaction->type) == 'income' ? 'selected' : '' }}>Revenu</option>
                                <option value="expense" {{ old('type', $transaction->type) == 'expense' ? 'selected' : '' }}>Dépense</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Méthode de paiement</label>
                        <select name="payment_method" class="form-select">
                            <option value="">Sélectionner</option>
                            <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="bank" {{ old('payment_method', $transaction->payment_method) == 'bank' ? 'selected' : '' }}>Virement bancaire</option>
                            <option value="mobile" {{ old('payment_method', $transaction->payment_method) == 'mobile' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="check" {{ old('payment_method', $transaction->payment_method) == 'check' ? 'selected' : '' }}>Chèque</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $transaction->notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
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
