@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1"><i class="bi bi-people text-primary me-2"></i>Gestion des Clients</h2>
                    <p class="text-muted mb-0">Gérez et suivez tous vos clients en un seul endroit</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Client
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total</small>
                            <h3 class="mb-0 fw-bold">1,248</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">VIP</small>
                            <h3 class="mb-0 fw-bold">84</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon danger me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">À Risque</small>
                            <h3 class="mb-0 fw-bold">12</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon success me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Anniversaires</small>
                            <h3 class="mb-0 fw-bold">7</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="card border-0 shadow-sm" data-aos="fade-up">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Rechercher un client..." id="searchCustomers">
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                        <select class="form-select form-select-sm" style="width: auto;" id="filterTier">
                            <option value="">Tous les tiers</option>
                            <option value="regular">Regular</option>
                            <option value="vip">VIP</option>
                            <option value="super_vip">Super VIP</option>
                        </select>

                        <select class="form-select form-select-sm" style="width: auto;" id="filterSegment">
                            <option value="">Tous les segments</option>
                            <option value="1">Actifs</option>
                            <option value="2">Inactifs</option>
                            <option value="3">Nouveaux</option>
                        </select>

                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" data-filter="all">
                                <i class="bi bi-grid-3x3-gap me-1"></i>Tous
                            </button>
                            <button type="button" class="btn btn-outline-warning" data-filter="vip">
                                <i class="bi bi-star me-1"></i>VIP
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-filter="at-risk">
                                <i class="bi bi-exclamation-triangle me-1"></i>À Risque
                            </button>
                        </div>

                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="border-0">Client</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Tier</th>
                            <th class="border-0">Visites</th>
                            <th class="border-0">LTV</th>
                            <th class="border-0">Dernière visite</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Customer Row 1 -->
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input row-checkbox">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary me-3">JD</div>
                                    <div>
                                        <div class="fw-semibold">Jean Dupont</div>
                                        <div class="badge badge-success-soft">
                                            <i class="bi bi-check-circle me-1"></i>Vérifié
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">jean.dupont@email.com</div>
                                <div class="text-muted small">+33 6 12 34 56 78</div>
                            </td>
                            <td>
                                <span class="badge bg-warning">
                                    <i class="bi bi-star-fill me-1"></i>VIP
                                </span>
                            </td>
                            <td><strong>24</strong></td>
                            <td><span class="text-success fw-semibold">1,250€</span></td>
                            <td>
                                <span class="text-muted small">Il y a 3 jours</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Voir le profil">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Envoyer un email">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Plus d'options">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Customer Row 2 -->
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input row-checkbox">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-success me-3">MM</div>
                                    <div>
                                        <div class="fw-semibold">Marie Martin</div>
                                        <div class="badge badge-warning-soft">
                                            <i class="bi bi-gift me-1"></i>Anniversaire ce mois-ci
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">marie.martin@email.com</div>
                                <div class="text-muted small">+33 6 98 76 54 32</div>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-star-fill me-1"></i>Super VIP
                                </span>
                            </td>
                            <td><strong>42</strong></td>
                            <td><span class="text-success fw-semibold">2,890€</span></td>
                            <td>
                                <span class="text-muted small">Il y a 1 jour</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Voir le profil">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Envoyer un email">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Plus d'options">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Customer Row 3 - At Risk -->
                        <tr class="table-warning">
                            <td>
                                <input type="checkbox" class="form-check-input row-checkbox">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-danger me-3">PD</div>
                                    <div>
                                        <div class="fw-semibold">Pierre Durand</div>
                                        <div class="badge badge-danger-soft">
                                            <i class="bi bi-exclamation-triangle me-1"></i>À risque
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">pierre.durand@email.com</div>
                                <div class="text-muted small">+33 6 45 67 89 01</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">Regular</span>
                            </td>
                            <td><strong>8</strong></td>
                            <td><span class="text-success fw-semibold">456€</span></td>
                            <td>
                                <span class="text-danger small"><strong>Il y a 67 jours</strong></span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Voir le profil">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Relancer">
                                        <i class="bi bi-bell"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Plus d'options">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- More rows... -->
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input row-checkbox">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-info me-3">SL</div>
                                    <div>
                                        <div class="fw-semibold">Sophie Lefebvre</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">sophie.lefebvre@email.com</div>
                                <div class="text-muted small">+33 6 23 45 67 89</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">Regular</span>
                            </td>
                            <td><strong>12</strong></td>
                            <td><span class="text-success fw-semibold">678€</span></td>
                            <td>
                                <span class="text-muted small">Il y a 5 jours</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Voir le profil">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Envoyer un email">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Plus d'options">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Affichage de 1 à 20 sur 1,248 clients
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">63</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar (hidden by default) -->
    <div class="bulk-actions-bar d-none" id="bulkActions">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong class="selected-count">0</strong> client(s) sélectionné(s)
                </div>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Envoyer un email
                    </button>
                    <button class="btn btn-sm btn-outline-success">
                        <i class="bi bi-tag me-2"></i>Ajouter un tag
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Customer Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Nouveau Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom*</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom*</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email*</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tier</label>
                            <select class="form-select">
                                <option value="regular">Regular</option>
                                <option value="vip">VIP</option>
                                <option value="super_vip">Super VIP</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Créer le client
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.bulk-actions-bar {
    position: fixed;
    bottom: 0;
    left: var(--sidebar-width);
    right: 0;
    background: white;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    padding: 15px 0;
    z-index: 1000;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

@media (max-width: 991px) {
    .bulk-actions-bar {
        left: 0;
    }
}
</style>
@endpush
