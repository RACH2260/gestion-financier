hello
@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
    }
    .main-content { background-color: #f4f7fe; min-height: 100vh; padding-top: 2rem; }
    .stat-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .stat-card:hover { transform: translateY(-5px); }
    .icon-shape {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .card-title { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; opacity: 0.8; }
    .card-amount { font-size: 1.5rem; font-weight: 800; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .btn-action { border-radius: 10px; padding: 10px 20px; font-weight: 600; }
</style>

<div class="main-content">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark">Tableau de bord</h3>
                    <p class="text-muted small">Content de vous revoir, {{ Auth::user()->name }} !</p>
                </div>
                <button class="btn btn-primary btn-action shadow-sm">
                    <i class="fas fa-plus me-2"></i> Nouvelle opération
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="card-title">Revenus totaux</span>
                            <div class="icon-shape"><i class="fas fa-arrow-trend-up"></i></div>
                        </div>
                        <div class="card-amount">0 FCFA</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="card-title">Dépenses totales</span>
                            <div class="icon-shape"><i class="fas fa-arrow-trend-down"></i></div>
                        </div>
                        <div class="card-amount">0 FCFA</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="card-title">Solde actuel</span>
                            <div class="icon-shape"><i class="fas fa-wallet"></i></div>
                        </div>
                        <div class="card-amount">0 FCFA</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="card-title">Dépenses du mois</span>
                            <div class="icon-shape"><i class="fas fa-calendar-day"></i></div>
                        </div>
                        <div class="card-amount">0 FCFA</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card table-card h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-bold">Évolution des finances</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px; background: #fcfcfc; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px dashed #ddd;">
                            <span class="text-muted italic">Espace pour ton graphique Chart.js</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card table-card mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="brand-icon mb-3 mx-auto" style="background: #eef2ff; color: #4e54c8; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h6 class="fw-bold">Actions rapides</h6>
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-primary btn-action mb-2 shadow-sm">Nouvelle transaction</button>
                            <button class="btn btn-light btn-action border text-primary">Voir toutes les transactions</button>
                        </div>
                    </div>
                </div>
                
                <div class="card table-card">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-circle-info me-2 text-info"></i>Analyse intelligente</h6>
                        <p class="small text-muted mb-0">Vos dépenses ont baissé de 12% par rapport au mois dernier. Continuez ainsi !</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="card table-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Dernières transactions</h5>
                        <a href="#" class="btn btn-sm btn-link text-decoration-none fw-bold">Tout voir</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Description</th>
                                    <th class="border-0">Catégorie</th>
                                    <th class="border-0 text-center">Date</th>
                                    <th class="border-0 text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-transparent">
                                    <td class="fw-bold">Aucune transaction</td>
                                    <td>---</td>
                                    <td class="text-center">---</td>
                                    <td class="text-end fw-bold">0 FCFA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection