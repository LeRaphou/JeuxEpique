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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css"/>
    <link rel="stylesheet" href="/assets/css/library.css"/>
    <link rel="stylesheet" href="/assets/css/overlay.css"/>

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
                <img src="/assets/images/jeux_epiques_logo.png" alt="Logo Jeux Épiques" class="sidebar-logo"/>
            </div>
            <nav class="sidebar-nav">
                <a href="/" class="s-btn" title="Accueil">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9,22 9,12 15,12 15,22"/>
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
                        <path fill="currentColor"
                              d="M12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 8-8m0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6s6 2.685 6 6s-2.685 6-6 6m0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m9 6h1v5h-8v-5h1v-1a3 3 0 1 1 6 0zm-2 0v-1a1 1 0 1 0-2 0v1z"/>
                    </svg>
                </a><?php endif; ?>
            <?php if ($username !== false): ?>
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
                <div class="lib-grid" id="lib-grid">
                    <?php foreach ($libraryGames as $g): ?>
                        <div class="lib-card" data-name="<?= htmlspecialchars(strtolower($g['game_name'])) ?>">
                            <div class="lib-card-img">
                                <img src="data:image/png;base64, <?= htmlspecialchars($g['game_image']) ?>"
                                     alt="<?= htmlspecialchars($g['game_name']) ?>" loading="lazy"/>
                                <div class="lib-card-overlay">
                                    <a href="/game?id=<?= (int)$g['id'] ?>" class="btn-play">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <polygon points="5,3 19,12 5,21"/>
                                        </svg>
                                        Jouer
                                    </a>
                                </div>
                            </div>
                            <div class="lib-card-info">
                                <span class="lib-card-name"><?= htmlspecialchars($g['game_name']) ?></span>
                                <span class="lib-card-type"><?= htmlspecialchars($g['game_type']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
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

</body>
</html>
