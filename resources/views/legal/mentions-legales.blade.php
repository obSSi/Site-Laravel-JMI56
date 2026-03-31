<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mentions légales | JMI 56</title>
        <meta name="description" content="Mentions légales du site JMI 56.">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                {!! file_get_contents(resource_path('css/app.css')) !!}
            </style>
            <script>
                {!! file_get_contents(resource_path('js/app.js')) !!}
            </script>
        @endif
    </head>
    <body>
        <a class="skip-link" href="#main">Aller au contenu</a>

        <div class="nav-shell">
            <div class="container nav">
                <a class="logo" href="{{ route('home') }}#accueil">
                    <img src="{{ asset('images/logo-jmi56.png') }}" alt="JMI 56" width="140" height="48">
                </a>
                <div class="nav-cta">
                    <a class="btn btn-ghost" href="{{ route('home') }}">Retour au site</a>
                </div>
            </div>
        </div>

        <main id="main" class="section">
            <div class="container legal-page">
                <h1 class="section-title">Mentions légales</h1>

                <article class="card legal-doc-card">
                    <h2>Éditeur du site</h2>
                    <p><strong>Nom de l'activité :</strong> {{ $ownerName }}</p>
                    <p><strong>Adresse :</strong> {{ $ownerAddress }}</p>
                    <p><strong>Téléphone :</strong> {{ $ownerPhonePrimary }} @if ($ownerPhoneSecondary !== '') - {{ $ownerPhoneSecondary }} @endif</p>
                    <p><strong>Email :</strong> <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                </article>

                <article class="card legal-doc-card">
                    <h2>Hébergeur</h2>
                    <p><strong>Nom :</strong> {{ $hostingProvider }}</p>
                    <p><strong>Adresse :</strong> {{ $hostingAddress }}</p>
                    <p><strong>Téléphone :</strong> {{ $hostingPhone }}</p>
                </article>

                <article class="card legal-doc-card">
                    <h2>Propriété intellectuelle</h2>
                    <p>Les contenus du site (textes, images, logo, charte graphique) sont protégés par le droit d'auteur.</p>
                    <p>Toute reproduction, diffusion ou réutilisation sans autorisation préalable est interdite.</p>
                </article>

                <article class="card legal-doc-card">
                    <h2>Responsabilité</h2>
                    <p>Le site est mis à jour régulièrement. Malgré cela, des erreurs ou omissions peuvent exister.</p>
                    <p>L'utilisateur reste responsable de l'usage qu'il fait des informations disponibles sur le site.</p>
                </article>
            </div>
        </main>

        <footer class="footer footer-legal-links">
            <div class="container legal-links">
                <a href="{{ route('legal.mentions') }}">Mentions légales</a>
                <a href="{{ route('legal.privacy') }}">Politique de confidentialité</a>
            </div>
        </footer>
    </body>
</html>
