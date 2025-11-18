@extends('layouts.app')

@section('title', 'Générateur de Contenu IA')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-magic gradient-primary p-2 rounded-3 text-white me-2"></i>
                        Générateur de Contenu IA
                    </h2>
                    <p class="text-muted mb-0">Créez du contenu engageant pour vos réseaux sociaux en quelques secondes</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-clock-history me-2"></i>Historique
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Content Generator Form -->
        <div class="col-12 col-lg-5" data-aos="fade-right">
            <div class="card border-0 shadow-sm sticky-top" style="top: 90px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Paramètres</h5>
                </div>
                <div class="card-body">
                    <form id="contentGeneratorForm">
                        <!-- Platform Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Plateforme *</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="platform" id="platform-facebook" value="facebook" checked>
                                    <label class="btn btn-outline-primary w-100" for="platform-facebook">
                                        <i class="bi bi-facebook fs-5 d-block mb-1"></i>
                                        <small>Facebook</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="platform" id="platform-instagram" value="instagram">
                                    <label class="btn btn-outline-danger w-100" for="platform-instagram">
                                        <i class="bi bi-instagram fs-5 d-block mb-1"></i>
                                        <small>Instagram</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="platform" id="platform-twitter" value="twitter">
                                    <label class="btn btn-outline-info w-100" for="platform-twitter">
                                        <i class="bi bi-twitter-x fs-5 d-block mb-1"></i>
                                        <small>Twitter</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="platform" id="platform-linkedin" value="linkedin">
                                    <label class="btn btn-outline-primary w-100" for="platform-linkedin">
                                        <i class="bi bi-linkedin fs-5 d-block mb-1"></i>
                                        <small>LinkedIn</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Theme -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Thème / Sujet *</label>
                            <input type="text" class="form-control" id="theme" placeholder="Ex: Nouveau menu automne, Promotion du weekend..." required>
                            <div class="form-text">Décrivez le sujet de votre publication</div>
                        </div>

                        <!-- Tone -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ton</label>
                            <select class="form-select" id="tone">
                                <option value="professionnel">Professionnel</option>
                                <option value="enthousiaste" selected>Enthousiaste</option>
                                <option value="chaleureux">Chaleureux</option>
                                <option value="humoristique">Humoristique</option>
                                <option value="elegant">Élégant</option>
                            </select>
                        </div>

                        <!-- Audience -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Public cible</label>
                            <select class="form-select" id="audience">
                                <option value="grand_public">Grand public</option>
                                <option value="jeunes_adultes">Jeunes adultes (18-35)</option>
                                <option value="familles">Familles</option>
                                <option value="professionnels">Professionnels</option>
                                <option value="seniors">Seniors</option>
                            </select>
                        </div>

                        <!-- Additional Details -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Détails supplémentaires</label>
                            <textarea class="form-control" id="details" rows="3" placeholder="Ajoutez des informations spécifiques (prix, horaires, ingrédients spéciaux...)"></textarea>
                        </div>

                        <!-- Generate Button -->
                        <button type="submit" class="btn btn-primary w-100 btn-lg gradient-primary">
                            <i class="bi bi-magic me-2"></i>Générer avec l'IA
                            <div class="spinner-border spinner-border-sm ms-2 d-none" id="loadingSpinner"></div>
                        </button>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-secondary w-100" id="generateVariations">
                                <i class="bi bi-arrow-repeat me-2"></i>Générer 3 variations
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Generated Content Preview -->
        <div class="col-12 col-lg-7" data-aos="fade-left">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Contenu Généré</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary" id="copyContent" data-bs-toggle="tooltip" title="Copier">
                                <i class="bi bi-clipboard"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Télécharger">
                                <i class="bi bi-download"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Partager">
                                <i class="bi bi-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="emptyState" class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-stars fs-1 text-primary"></i>
                        </div>
                        <h4 class="mb-3">Prêt à créer du contenu magique ?</h4>
                        <p class="text-muted mb-4">
                            Remplissez le formulaire à gauche et cliquez sur "Générer avec l'IA"<br>
                            pour créer du contenu engageant en quelques secondes.
                        </p>
                        <div class="row g-3 mt-4">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-lightning-charge text-warning fs-4 d-block mb-2"></i>
                                    <strong>Rapide</strong>
                                    <p class="small mb-0 text-muted">Génération en 2-3 secondes</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-stars text-primary fs-4 d-block mb-2"></i>
                                    <strong>Intelligent</strong>
                                    <p class="small mb-0 text-muted">Optimisé pour chaque plateforme</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-palette text-success fs-4 d-block mb-2"></i>
                                    <strong>Personnalisé</strong>
                                    <p class="small mb-0 text-muted">Adapté à votre style</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="generatedContent" class="d-none">
                        <div class="mb-4">
                            <div class="bg-light rounded-3 p-4" id="contentPreview">
                                <p class="mb-0 lead" id="contentText" style="white-space: pre-wrap;"></p>
                            </div>
                        </div>

                        <!-- Hashtags -->
                        <div class="mb-4" id="hashtagsSection">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash me-1"></i>Hashtags suggérés
                            </label>
                            <div id="hashtags" class="d-flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Stats -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted small mb-1">Caractères</div>
                                    <div class="h5 mb-0 fw-bold" id="charCount">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted small mb-1">Mots</div>
                                    <div class="h5 mb-0 fw-bold" id="wordCount">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted small mb-1">Hashtags</div>
                                    <div class="h5 mb-0 fw-bold" id="hashtagCount">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted small mb-1">Meilleur horaire</div>
                                    <div class="h5 mb-0 fw-bold" id="bestTime">--:--</div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex">
                            <button class="btn btn-primary flex-fill">
                                <i class="bi bi-send me-2"></i>Programmer la publication
                            </button>
                            <button class="btn btn-outline-primary flex-fill" id="regenerate">
                                <i class="bi bi-arrow-repeat me-2"></i>Régénérer
                            </button>
                        </div>
                    </div>

                    <!-- Variations -->
                    <div id="variations" class="d-none mt-4">
                        <h5 class="mb-3"><i class="bi bi-layers me-2"></i>Variations</h5>
                        <div id="variationsList" class="row g-3"></div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mt-4" data-aos="fade-up">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Conseils Pro</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Utilisez des émojis pour rendre votre contenu plus engageant 😊</li>
                        <li class="mb-2">Posez des questions à votre audience pour encourager l'interaction</li>
                        <li class="mb-2">Incluez un appel à l'action clair (réserver, visiter, commander...)</li>
                        <li class="mb-2">Publiez aux heures de forte affluence (12h-14h et 18h-21h)</li>
                        <li>Variez les formats : photos, vidéos, stories, carrousels</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('contentGeneratorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const button = form.querySelector('button[type="submit"]');
    const spinner = document.getElementById('loadingSpinner');
    const emptyState = document.getElementById('emptyState');
    const generatedContent = document.getElementById('generatedContent');

    // Show loading
    button.disabled = true;
    spinner.classList.remove('d-none');

    try {
        // Simulate API call (replace with actual AJAX call)
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Sample generated content
        const content = `🍂 Découvrez notre nouveau menu automne ! 🍂

Venez savourer nos délicieuses créations automnales :
🥘 Velouté de butternut aux châtaignes
🍖 Magret de canard aux figues
🍰 Tarte tatin maison

Réservez dès maintenant et profitez d'une expérience culinaire exceptionnelle dans une ambiance chaleureuse.

📍 Ouvert du mardi au dimanche, 12h-14h30 et 19h-22h
📞 Réservations recommandées`;

        const hashtags = ['#MenuAutomne', '#GastronomiesFrancaise', '#RestaurantParis', '#Foodie', '#ChefCuisine'];

        // Hide empty state, show content
        emptyState.classList.add('d-none');
        generatedContent.classList.remove('d-none');

        // Update content
        document.getElementById('contentText').textContent = content;

        // Update hashtags
        const hashtagsContainer = document.getElementById('hashtags');
        hashtagsContainer.innerHTML = hashtags.map(tag =>
            `<span class="badge bg-primary-soft">${tag}</span>`
        ).join('');

        // Update stats
        document.getElementById('charCount').textContent = content.length;
        document.getElementById('wordCount').textContent = content.split(/\s+/).length;
        document.getElementById('hashtagCount').textContent = hashtags.length;
        document.getElementById('bestTime').textContent = '12:30';

        // Scroll to content
        generatedContent.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // Show success notification
        RestauBoost.showToast('Contenu généré avec succès !', 'success');

    } catch (error) {
        RestauBoost.showToast('Erreur lors de la génération', 'danger');
        console.error(error);
    } finally {
        button.disabled = false;
        spinner.classList.add('d-none');
    }
});

// Copy content
document.getElementById('copyContent').addEventListener('click', function() {
    const content = document.getElementById('contentText').textContent;
    const hashtags = Array.from(document.querySelectorAll('#hashtags .badge'))
        .map(badge => badge.textContent).join(' ');

    const fullContent = `${content}\n\n${hashtags}`;

    navigator.clipboard.writeText(fullContent).then(() => {
        RestauBoost.showToast('Contenu copié dans le presse-papier !', 'success');
        this.innerHTML = '<i class="bi bi-check2"></i>';
        setTimeout(() => {
            this.innerHTML = '<i class="bi bi-clipboard"></i>';
        }, 2000);
    });
});

// Regenerate
document.getElementById('regenerate').addEventListener('click', function() {
    document.getElementById('contentGeneratorForm').dispatchEvent(new Event('submit'));
});
</script>
@endpush
