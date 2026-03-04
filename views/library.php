<?php
require(__DIR__ . '/../src/refactorer.php');
$username = $username ?? false;
$isAdmin = $isAdmin ?? false;
$libraryGames = $libraryGames ?? [];
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Jeux Épiques – Bibliothèque</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css"/>
    <link rel="stylesheet" href="/assets/css/library.css"/>

</head>
<body>

<div id="user_modal" class="modal">
    <div class="modal_content">
        <h1><?= $username ?></h1>
        <form method="post" action="">
            <input type="hidden" name="logout" value="true"/>
            <button class="btn-modal">Se déconnecter</button>
        </form>
        <a href="#" class="modal_close">×</a>
    </div>
</div>

<div class="launcher">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-logo">
                <img src="/assets/images/jeux_epiques_logo.png" alt="Logo"/>
            </div>
            <nav class="sidebar-nav">
                <a href="/" class="s-btn" title="Accueil">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9,22 9,12 15,12 15,22"/>
                    </svg>
                </a>
                <a href="/shop" class="s-btn" title="Boutique">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                </a>
                <a href="/library" class="s-btn active" title="Bibliothèque">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <?php if ($isAdmin): ?>
                <a href="/admin" class="s-btn" title="Admin">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1v2"/><path d="M12 21v2"/>
                        <path d="M4.22 4.22l1.42 1.42"/><path d="M18.36 18.36l1.42 1.42"/>
                        <path d="M1 12h2"/><path d="M21 12h2"/>
                        <path d="M4.22 19.78l1.42-1.42"/><path d="M18.36 5.64l1.42-1.42"/>
                    </svg>
                </a><?php endif; ?>
            <?php if ($username !== false): ?>
                <a href="#" class="s-btn" title="Paramètres">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                </a>
                <a href="#user_modal" class="s-avatar">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                    <span class="online-dot"></span>
                </a>
            <?php endif; ?>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <header class="top-bar">
            <span class="top-bar-label">Bibliothèque</span>
            <div class="top-user">
                <?php if (!$username): ?>
                    <a href="/login">Se connecter</a>
                <?php else: ?>
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                    <span><?= htmlspecialchars($username) ?></span>
                <?php endif; ?>
            </div>
        </header>

        <div class="scroll-area">

            <?php if (!$username): ?>
                <div class="lib-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                    <h2>Votre bibliothèque est vide</h2>
                    <p>Connectez-vous pour accéder à vos jeux.</p>
                    <a href="/login" class="btn-buy">Se connecter</a>
                </div>

            <?php elseif (empty($libraryGames)): ?>
                <div class="lib-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                    <h2>Aucun jeu dans votre bibliothèque</h2>
                    <p>Explorez la boutique et ajoutez vos premiers jeux !</p>
                    <a href="/" class="btn-buy">Découvrir la boutique</a>
                </div>

            <?php else: ?>
                <div class="lib-toolbar">
                    <div class="lib-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="lib-search-input" placeholder="Rechercher un jeu…"/>
                    </div>
                    <span class="lib-count"><?= count($libraryGames) ?> jeu<?= count($libraryGames) > 1 ? 'x' : '' ?></span>
                </div>

                <div class="lib-grid" id="lib-grid">
                    <?php foreach ($libraryGames as $g): ?>
                        <div class="lib-card" data-name="<?= htmlspecialchars(strtolower($g['game_name'])) ?>">
                            <div class="lib-card-img">
                                <img src="data:image/png;base64, <?= htmlspecialchars($g['game_image']) ?>"
                                     alt="<?= htmlspecialchars($g['game_name']) ?>" loading="lazy"/>
                                <div class="lib-card-overlay">
                                    <button class="btn-play">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <polygon points="5,3 19,12 5,21"/>
                                        </svg>
                                        Jouer
                                    </button>
                                </div>
                            </div>
                            <div class="lib-card-info">
                                <span class="lib-card-name"><?= htmlspecialchars($g['game_name']) ?></span>
                                <span class="lib-card-type"><?= htmlspecialchars($g['game_type']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="lib-no-result" id="lib-no-result" style="display:none;">
                    Aucun jeu ne correspond à votre recherche.
                </div>

            <?php endif; ?>

            <footer class="site-footer">
                <div class="footer-logo">
                    <img src="/assets/images/jeux_epiques_logo.png" alt="Logo Jeux Épiques"/>
                    <span>Jeux Épiques</span>
                </div>
                <p class="footer-copy">© 2025 Jeux Épiques, Inc. Tous droits réservés.</p>
            </footer>
        </div>
    </main>
</div>

<script>
    const searchInput = document.getElementById('lib-search-input');
    const grid = document.getElementById('lib-grid');
    const noResult = document.getElementById('lib-no-result');

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().trim();
            let visible = 0;
            grid.querySelectorAll('.lib-card').forEach(card => {
                const match = (card.dataset.name || '').includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            noResult.style.display = visible === 0 ? 'block' : 'none';
        });
    }
</script>

</body>
</html>
