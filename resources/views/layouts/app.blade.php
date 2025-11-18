<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RestauBoost') - Marketing SaaS pour Restaurants</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center">
                <div class="logo-icon">
                    <i class="bi bi-shop-window"></i>
                </div>
                <div class="logo-text ms-3">
                    <h5 class="mb-0 fw-bold">RestauBoost</h5>
                    <small class="text-muted">Marketing Pro</small>
                </div>
            </div>
            <button class="btn btn-link text-white d-lg-none" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">
                <small class="menu-section-title">PRINCIPAL</small>
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                    <span class="badge bg-primary ms-auto">12</span>
                </a>
                <a href="{{ route('analytics') }}" class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Analytics</span>
                </a>
            </div>

            <div class="menu-section">
                <small class="menu-section-title">CRM & CLIENTS</small>
                <a href="{{ route('customers.index') }}" class="menu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Clients</span>
                    <span class="badge bg-success ms-auto">248</span>
                </a>
                <a href="{{ route('customers.segments') }}" class="menu-item">
                    <i class="bi bi-diagram-3"></i>
                    <span>Segments</span>
                </a>
                <a href="{{ route('customers.vip') }}" class="menu-item">
                    <i class="bi bi-star"></i>
                    <span>VIP</span>
                    <span class="badge bg-warning ms-auto">18</span>
                </a>
                <a href="{{ route('customers.at-risk') }}" class="menu-item">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>À risque</span>
                    <span class="badge bg-danger ms-auto">7</span>
                </a>
            </div>

            <div class="menu-section">
                <small class="menu-section-title">INTELLIGENCE IA</small>
                <a href="{{ route('ai.content') }}" class="menu-item {{ request()->routeIs('ai.content') ? 'active' : '' }}">
                    <i class="bi bi-magic"></i>
                    <span>Générateur de Contenu</span>
                </a>
                <a href="{{ route('ai.sentiment') }}" class="menu-item">
                    <i class="bi bi-emoji-smile"></i>
                    <span>Analyse Sentiment</span>
                </a>
                <a href="{{ route('ai.reviews') }}" class="menu-item">
                    <i class="bi bi-chat-dots"></i>
                    <span>Réponses Auto</span>
                </a>
            </div>

            <div class="menu-section">
                <small class="menu-section-title">MARKETING</small>
                <a href="{{ route('campaigns.index') }}" class="menu-item">
                    <i class="bi bi-envelope"></i>
                    <span>Campagnes Email</span>
                </a>
                <a href="{{ route('social.posts') }}" class="menu-item">
                    <i class="bi bi-instagram"></i>
                    <span>Réseaux Sociaux</span>
                </a>
                <a href="{{ route('reviews.index') }}" class="menu-item">
                    <i class="bi bi-star-fill"></i>
                    <span>Avis Clients</span>
                </a>
            </div>

            <div class="menu-section">
                <small class="menu-section-title">PARAMÈTRES</small>
                <a href="{{ route('settings.business') }}" class="menu-item">
                    <i class="bi bi-building"></i>
                    <span>Mon Restaurant</span>
                </a>
                <a href="{{ route('settings.account') }}" class="menu-item">
                    <i class="bi bi-person-circle"></i>
                    <span>Mon Compte</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=6366f1&color=fff" alt="User" class="user-avatar">
                <div class="user-info ms-3">
                    <div class="fw-semibold">{{ Auth::user()->name ?? 'John Doe' }}</div>
                    <small class="text-muted">{{ Auth::user()->email ?? 'john@example.com' }}</small>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm w-100"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
            <div class="container-fluid">
                <button class="btn btn-link text-dark d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="d-flex align-items-center ms-auto">
                    <!-- Search -->
                    <div class="search-box me-3 d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" placeholder="Rechercher...">
                    </div>

                    <!-- Notifications -->
                    <div class="dropdown me-3">
                        <button class="btn btn-link text-dark position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <li class="dropdown-header">Notifications</li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex">
                                        <i class="bi bi-star-fill text-warning me-3"></i>
                                        <div>
                                            <strong>Nouveau VIP</strong>
                                            <p class="mb-0 small text-muted">Marie Martin est maintenant VIP</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex">
                                        <i class="bi bi-exclamation-triangle text-danger me-3"></i>
                                        <div>
                                            <strong>Client à risque</strong>
                                            <p class="mb-0 small text-muted">3 clients n'ont pas visité depuis 60j</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex">
                                        <i class="bi bi-envelope text-primary me-3"></i>
                                        <div>
                                            <strong>Campagne envoyée</strong>
                                            <p class="mb-0 small text-muted">Newsletter Automne - 248 emails</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">Voir toutes les notifications</a></li>
                        </ul>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=6366f1&color=fff"
                                 alt="User" class="rounded-circle" width="40" height="40">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('settings.account') }}"><i class="bi bi-person me-2"></i>Mon Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings.business') }}"><i class="bi bi-building me-2"></i>Mon Restaurant</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; 2024 RestauBoost. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted me-3">Support</a>
                        <a href="#" class="text-muted me-3">Documentation</a>
                        <a href="#" class="text-muted">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>

    @stack('scripts')
</body>
</html>
