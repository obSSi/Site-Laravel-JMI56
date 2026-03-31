<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Politique de confidentialité | JMI 56</title>
        <meta name="description" content="Politique de confidentialité RGPD du site JMI 56.">

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
                <h1 class="section-title">Politique de confidentialité</h1>

                <article class="card legal-doc-card">
                    <h2>Données collectées</h2>
                    <ul>
                        <li>Le site collecte des données uniquement quand un client remplit le formulaire de contact.</li>
                        <li>Données demandées : nom, numéro de téléphone et message de la demande.</li>
                        <li>Aucune donnée dite sensible n'est demandée (santé, opinion politique, religion, etc.).</li>
                    </ul>
                </article>

                <article class="card legal-doc-card">
                    <h2>Finalité et base légale</h2>
                    <ul>
                        <li>Finalité : répondre à la demande du client et assurer le suivi de son ticket.</li>
                        <li>Base légale : consentement de la personne qui envoie volontairement le formulaire.</li>
                    </ul>
                </article>

                <article class="card legal-doc-card">
                    <h2>Durée de conservation</h2>
                    <p>Les demandes sont conservées au maximum {{ $retentionDays }} jours puis supprimées automatiquement.</p>
                    <p>En cas de demande de suppression anticipée, la suppression est effectuée avant ce délai.</p>
                </article>

                <article class="card legal-doc-card">
                    <h2>Destinataires des données</h2>
                    <p>Les données sont réservées à l'administration du site JMI 56 et ne sont pas revendues.</p>
                    <p>Elles peuvent être hébergées chez le prestataire d'hébergement technique du site.</p>
                </article>

                <article class="card legal-doc-card">
                    <h2>Vos droits RGPD</h2>
                    <p>Vous pouvez demander l'accès, la rectification ou la suppression de vos données à tout moment.</p>
                    <p>Contact : <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.</p>
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
