<?php
require (__DIR__ . '/../src/refactorer.php');
$gamesInfo = $gamesInfo ?? [];
$featuredGames = array_slice($gamesInfo, 0, 3);
$currentSlide = 0;
$username = $username ?? false;
$isAdmin = $isAdmin ?? false;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Jeux Épiques</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css"/>
    <link rel="stylesheet" href="/assets/css/home.css"/>
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
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="/assets/images/jeux_epiques_logo.png" class="sidebar-logo">
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="s-btn active" title="Accueil">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
            </a>
            <a href="#" class="s-btn" title="Boutique">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
            </a>
            <a href="#" class="s-btn" title="Bibliothèque">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                </svg>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <?php if ($isAdmin) : ?>
                <a href="/admin" class="s-btn" title="Admin">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1v2"/>
                        <path d="M12 21v2"/>
                        <path d="M4.22 4.22l1.42 1.42"/>
                        <path d="M18.36 18.36l1.42 1.42"/>
                        <path d="M1 12h2"/>
                        <path d="M21 12h2"/>
                        <path d="M4.22 19.78l1.42-1.42"/>
                        <path d="M18.36 5.64l1.42-1.42"/>
                    </svg>
                </a>
            <?php endif; ?>
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

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- Header right -->
        <header class="top-bar">
            <div class="top-bar-left">
                <span class="top-bar-label">Boutique</span>
            </div>
            <div class="top-bar-right">
                <button class="top-btn" title="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 01-3.46 0"/>
                    </svg>
                    <span class="notif-dot"></span>
                </button>
                <div class="top-user">
                    <?php if (!$username) : ?>
                        <a href="/login">Se connecter</a>
                    <?php else : ?>
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                        <span><?= $username ?></span>
                    <?php endif ?>
                </div>
            </div>
        </header>

        <div class="scroll-area">

            <!-- HERO CAROUSEL -->
            <section class="hero">
                <div class="hero-slides">
                    <?php foreach ($featuredGames as $i => $g): ?>
                        <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>">
                            <img class="hero-bg" src="data:image/png;base64, <?= htmlspecialchars($g['game_image']) ?>"
                                 alt="<?= htmlspecialchars($g['game_name']) ?>"/>
                            <div class="hero-gradient"></div>
                            <div class="hero-content">
                                <div class="hero-meta">
                                    <span class="hero-genre"><?= htmlspecialchars($g['game_type']) ?></span>
                                </div>
                                <h1 class="hero-title"><?= htmlspecialchars($g['game_name']) ?></h1>
                                <p class="hero-desc"><?= htmlspecialchars($g['description']) ?></p>
                                <div class="hero-price-row">
                                    <span class="hero-price <?= $g['price'] === 0.0 ? 'free' : '' ?>"><?= $g['price'] === 0 ? "Gratuit" : refactorPrice(htmlspecialchars($g['price'])) . "€" ?></span>
                                </div>
                                <div class="hero-btns">
                                    <button class="btn-buy"><?= $g['price'] === 0.0 ? 'Obtenir' : 'Acheter' ?></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Thumbnails carousel -->
                <div class="hero-thumbs">
                    <?php foreach ($featuredGames as $i => $g): ?>
                        <div class="hero-thumb <?= $i === 0 ? 'active' : '' ?>" data-target="<?= $i ?>">
                            <img src="data:image/png;base64, <?= htmlspecialchars($g['game_image']) ?>" alt="<?= htmlspecialchars($g['game_name']) ?>"/>
                            <div class="thumb-info">
                                <span class="thumb-title"><?= htmlspecialchars($g['game_name'])?></span>
                                <span class="thumb-price"><?= refactorPrice(htmlspecialchars($g['price'])) . "€" ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="section">
                <div class="section-head">
                    <h2>Jeux populaires</h2>
                </div>
                <div class="store-grid">
                    <?php foreach ($gamesInfo as $g): ?>
                        <div class="store-card">
                            <div class="store-img-wrap">
                                <img src="data:image/png;base64, <?= htmlspecialchars($g['game_image']) ?>"
                                     alt="<?= htmlspecialchars($g['game_name']) ?>" loading="lazy"/>
                                <div class="store-hover-overlay">
                                    <button class="btn-cart-quick">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="9" cy="21" r="1"/>
                                            <circle cx="20" cy="21" r="1"/>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                                        </svg>
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                            <div class="store-info">
                                <span class="store-game-name"><?= htmlspecialchars($g['game_name']) ?></span>
                                <div class="store-price-row">
                                    <span class="store-price <?= $g['price'] === 0.0 ? 'free' : '' ?>"><?= $g['price'] === 0.0 ? "Gratuit" : refactorPrice(htmlspecialchars($g['price'])) . "€" ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- FOOTER -->
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