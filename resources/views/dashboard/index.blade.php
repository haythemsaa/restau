@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Bienvenue, {{ Auth::user()->name ?? 'John' }} 👋</h2>
                    <p class="text-muted mb-0">Voici un aperçu de votre activité aujourd'hui</p>
                </div>
                <div>
                    <button class="btn btn-primary me-2">
                        <i class="bi bi-download me-2"></i>Exporter
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-calendar3 me-2"></i>Cette semaine
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card shadow-soft">
                <div class="stat-icon primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-label">Total Clients</div>
                <div class="stat-value counter" data-target="1248">0</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up me-1"></i>12.5% vs mois dernier
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card shadow-soft">
                <div class="stat-icon success">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-label">Clients VIP</div>
                <div class="stat-value counter" data-target="84">0</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up me-1"></i>8.2% vs mois dernier
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card shadow-soft">
                <div class="stat-icon warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="stat-label">Clients à Risque</div>
                <div class="stat-value counter" data-target="12">0</div>
                <div class="stat-change negative">
                    <i class="bi bi-arrow-down me-1"></i>3.1% vs mois dernier
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card shadow-soft">
                <div class="stat-icon danger">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="stat-label">Emails Envoyés</div>
                <div class="stat-value counter" data-target="3567">0</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up me-1"></i>24.7% vs mois dernier
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8" data-aos="fade-right">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Évolution des Visites</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active">7j</button>
                            <button type="button" class="btn btn-outline-secondary">30j</button>
                            <button type="button" class="btn btn-outline-secondary">12m</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="visitsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4" data-aos="fade-left">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart text-success me-2"></i>Répartition Clients</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerDistribution" height="200"></canvas>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2" style="width: 12px; height: 12px;"></span>
                                <span>Regular</span>
                            </div>
                            <strong>1,082</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2" style="width: 12px; height: 12px;"></span>
                                <span>VIP</span>
                            </div>
                            <strong>84</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                                <span>Super VIP</span>
                            </div>
                            <strong>82</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <div class="col-12 col-lg-7" data-aos="fade-up">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history text-info me-2"></i>Activité Récente</h5>
                        <a href="#" class="text-decoration-none">Voir tout <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <td class="border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon primary" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-semibold">Nouveau Client VIP</div>
                                                <small class="text-muted">Marie Martin a été promue VIP</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end border-0">
                                        <small class="text-muted">Il y a 5 min</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon success" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">
                                                <i class="bi bi-envelope-check"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-semibold">Campagne Envoyée</div>
                                                <small class="text-muted">Newsletter Automne - 248 destinataires</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">Il y a 1h</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon warning" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">
                                                <i class="bi bi-chat-dots"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-semibold">Nouveau Avis</div>
                                                <small class="text-muted">5 étoiles - "Excellent service!"</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">Il y a 2h</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon danger" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-semibold">Client à Risque</div>
                                                <small class="text-muted">Jean Dupont - Pas de visite depuis 65 jours</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">Il y a 3h</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon primary" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">
                                                <i class="bi bi-gift"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-semibold">Anniversaire</div>
                                                <small class="text-muted">3 clients fêtent leur anniversaire ce mois-ci</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">Aujourd'hui</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5" data-aos="fade-up" data-aos-delay="100">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-check text-success me-2"></i>Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="btn btn-outline-primary text-start d-flex align-items-center">
                            <i class="bi bi-magic fs-4 me-3"></i>
                            <div>
                                <div class="fw-semibold">Générer du Contenu IA</div>
                                <small class="text-muted">Créez des posts pour les réseaux sociaux</small>
                            </div>
                        </button>

                        <button class="btn btn-outline-success text-start d-flex align-items-center">
                            <i class="bi bi-envelope fs-4 me-3"></i>
                            <div>
                                <div class="fw-semibold">Nouvelle Campagne Email</div>
                                <small class="text-muted">Créez et envoyez une campagne</small>
                            </div>
                        </button>

                        <button class="btn btn-outline-warning text-start d-flex align-items-center">
                            <i class="bi bi-people fs-4 me-3"></i>
                            <div>
                                <div class="fw-semibold">Gérer les Segments</div>
                                <small class="text-muted">Organisez vos clients par segment</small>
                            </div>
                        </button>

                        <button class="btn btn-outline-danger text-start d-flex align-items-center">
                            <i class="bi bi-star fs-4 me-3"></i>
                            <div>
                                <div class="fw-semibold">Répondre aux Avis</div>
                                <small class="text-muted">12 avis en attente de réponse</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightbulb text-warning me-2"></i>Conseil du Jour</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">💡 <strong>Fidélisez vos clients VIP</strong></p>
                    <p class="text-muted small mb-3">
                        Vos clients VIP représentent 35% de votre chiffre d'affaires.
                        Envoyez-leur une offre exclusive pour les remercier de leur fidélité !
                    </p>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-send me-2"></i>Créer une Campagne VIP
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Visits Chart
const visitsCtx = document.getElementById('visitsChart');
if (visitsCtx) {
    new Chart(visitsCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Visites',
                data: [45, 52, 48, 67, 73, 86, 92],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Customer Distribution Chart
const distributionCtx = document.getElementById('customerDistribution');
if (distributionCtx) {
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Regular', 'VIP', 'Super VIP'],
            datasets: [{
                data: [1082, 84, 82],
                backgroundColor: [
                    '#6366f1',
                    '#f59e0b',
                    '#10b981'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}
</script>
@endpush
