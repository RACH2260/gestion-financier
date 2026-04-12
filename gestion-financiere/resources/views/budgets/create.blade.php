@extends('layouts.app')

@section('title', 'Nouveau budget')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle"></i> Définir un budget</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('budgets.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Montant du budget *</label>
                        <div class="input-group">
                            <input type="number" name="amount" step="0.01" class="form-control" required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Période *</label>
                            <select name="period" class="form-select" required>
                                <option value="monthly">Mensuel</option>
                                <option value="quarterly">Trimestriel</option>
                                <option value="yearly">Annuel</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Année *</label>
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mois (pour un budget mensuel)</label>
                        <select name="month" class="form-select">
                            <option value="">Annuel / Trimestriel</option>
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('budgets.index') }}" class="btn btn-secondary">
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
