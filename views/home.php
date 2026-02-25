<?php
$featuredGames = [
        [
                'id' => 1, 'title' => 'Quatorze Nuits',
                'image' => 'https://cdn2.unrealengine.com/fortnite-chapter-5-season-1-underground-702658e55a25.jpg',
                'price' => 'Gratuit', 'tag' => 'GRATUIT', 'tagColor' => 'blue',
                'description' => 'La Bataille Royale gratuite où 100 joueurs s\'affrontent pour la victoire ultime.',
                'genre' => 'Bataille Royale',
        ],
        [
                'id' => 2, 'title' => 'Grand Vol de Voiture, le 5ème du nom',
                'image' => 'https://cdn1.epicgames.com/offer/c4763f236024445f852fb6e7b5e1c7d2/EGS_AlanWake2_RemedyEntertainment_S1_2560x1440-5e5b8c70db8d68b03ed67c9c4a68a42b',
                'price' => '49,99 €', 'tag' => 'POPULAIRE', 'tagColor' => 'orange',
                'description' => 'Vivez une aventure épique dans un monde ouvert rempli de missions, de courses-poursuites et de chaos urbain.',
                'genre' => 'Action / Horreur',
        ],
        [
                'id' => 3, 'title' => 'Rocket League',
                'image' => 'https://cdn1.epicgames.com/offer/9773aa1aa54f4f7b80e44bef04986cea/EGS_RocketLeague_PsyonixLLC_S1_2560x1440-c4524b4d5e9d6b22e6a7c99e1b8c7d73',
                'price' => 'Gratuit', 'tag' => 'GRATUIT', 'tagColor' => 'blue',
                'description' => 'Football propulsé par fusées. Le sport ultime mêlant vitesse et acrobaties.',
                'genre' => 'Sport',
        ],
];

$storeGames = [
        ['title' => "Punk-Cybernétique de l'Année 2077", 'price' => '59,99 €', 'tag' => '-50%', 'oldPrice' => '119,98 €', 'image' => 'https://cdn1.epicgames.com/offer/77f2b98e2cef40c8a7437518bf420e47/EGS_Cyberpunk2077_CDPROJEKTRED_S1_2560x1440-c4524b4d5e9d6b22e6a7c99e1b8c7d73'],
        ['title' => 'Miner-Fabriquer', 'price' => '39,99 €', 'tag' => '', 'oldPrice' => '', 'image' => 'https://cdn1.epicgames.com/offer/14ee004dadc142faaaece5a6270fb628/EGS_TheWitcher3WildHuntCompleteEdition_CDPROJEKTRED_S1_2560x1440-5e5b8c70db8d68b03ed67c9c4a68a42b'],
        ['title' => 'Grand Vol de Voiture, le 5ème du nom', 'price' => '29,99 €', 'tag' => '-25%', 'oldPrice' => '39,98 €', 'image' => 'https://cdn1.epicgames.com/offer/0584d2013f0149a791e8b9bad0eec102/GTAV_EGS_Artwork_1200x1600-5e5b8c70db8d68b03ed67c9c4a68a42b'],
        ['title' => "L'Appel du Devoir: Opérations Sombre 7", 'price' => 'Gratuit', 'tag' => '', 'oldPrice' => '', 'image' => 'https://cdn1.epicgames.com/offer/50118b7f954e450f8823df1614b24e80/EGS_FallGuys_Mediatonic_S1_2560x1440-2f8c81912f62bc8b6b7b3e7c23a8a1c1'],
        ['title' => 'Quatorze Nuits', 'price' => 'Gratuit', 'tag' => '', 'oldPrice' => '', 'image' => 'https://cdn2.unrealengine.com/fortnite-chapter-5-season-1-underground-702658e55a25.jpg'],
        ['title' => "La Contre-Attaque : L'Offensive Globale", 'price' => '12,99 €', 'tag' => '', 'oldPrice' => '', 'image' => 'https://cdn1.epicgames.com/offer/9773aa1aa54f4f7b80e44bef04986cea/EGS_RocketLeague_PsyonixLLC_S1_2560x1440-c4524b4d5e9d6b22e6a7c99e1b8c7d73'],
];

$freeGames = [
        ['title' => 'Rocket League', 'image' => 'https://cdn1.epicgames.com/offer/9773aa1aa54f4f7b80e44bef04986cea/EGS_RocketLeague_PsyonixLLC_S1_2560x1440-c4524b4d5e9d6b22e6a7c99e1b8c7d73', 'end' => '27 fév.'],
        ['title' => 'Fall Guys', 'image' => 'https://cdn1.epicgames.com/offer/50118b7f954e450f8823df1614b24e80/EGS_FallGuys_Mediatonic_S1_2560x1440-2f8c81912f62bc8b6b7b3e7c23a8a1c1', 'end' => '6 mars'],
];

$currentSlide = 0;
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
            <a href="#" class="s-btn" title="Paramètres">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                </svg>
            </a>
            <div class="s-avatar">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                <span class="online-dot"></span>
            </div>
        </div>
    </aside>

    <!-- SECONDARY NAV -->
    <div class="secondary-nav">
        <div class="sec-nav-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Rechercher dans la boutique..."/>
        </div>
        <div class="sec-nav-filters">
            <button class="filter-pill active">Tous les jeux</button>
            <button class="filter-pill">Gratuit</button>
            <button class="filter-pill">Action</button>
            <button class="filter-pill">RPG</button>
            <button class="filter-pill">Sport</button>
            <button class="filter-pill">Aventure</button>
        </div>
    </div>

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
                <button class="top-btn" title="Panier">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                    </svg>
                </button>
                <div class="top-user">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                    <span>Joueur</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>
            </div>
        </header>

        <div class="scroll-area">

            <!-- HERO CAROUSEL -->
            <section class="hero">
                <div class="hero-slides">
                    <?php foreach ($featuredGames as $i => $g): ?>
                        <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>">
                            <img class="hero-bg" src="<?= htmlspecialchars($g['image']) ?>"
                                 alt="<?= htmlspecialchars($g['title']) ?>"/>
                            <div class="hero-gradient"></div>
                            <div class="hero-content">
                                <div class="hero-meta">
                                    <span class="hero-genre"><?= htmlspecialchars($g['genre']) ?></span>
                                </div>
                                <h1 class="hero-title"><?= htmlspecialchars($g['title']) ?></h1>
                                <p class="hero-desc"><?= htmlspecialchars($g['description']) ?></p>
                                <div class="hero-price-row">
                                    <span class="hero-price <?= $g['price'] === 'Gratuit' ? 'free' : '' ?>"><?= htmlspecialchars($g['price']) ?></span>
                                </div>
                                <div class="hero-btns">
                                    <button class="btn-buy"><?= $g['price'] === 'Gratuit' ? 'Obtenir' : 'Acheter' ?></button>
                                    <button class="btn-wish">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                                        </svg>
                                        Ajouter à la wishlist
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Thumbnails carousel -->
                <div class="hero-thumbs">
                    <?php foreach ($featuredGames as $i => $g): ?>
                        <div class="hero-thumb <?= $i === 0 ? 'active' : '' ?>" data-target="<?= $i ?>">
                            <img src="<?= htmlspecialchars($g['image']) ?>" alt="<?= htmlspecialchars($g['title']) ?>"/>
                            <div class="thumb-info">
                                <span class="thumb-tag tag-<?= $g['tagColor'] ?>"><?= htmlspecialchars($g['tag']) ?></span>
                                <span class="thumb-title"><?= htmlspecialchars($g['title']) ?></span>
                                <span class="thumb-price"><?= htmlspecialchars($g['price']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="section">
                <div class="section-head">
                    <h2>Jeux populaires</h2>
                    <a href="#" class="see-all">Voir tout
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>
                </div>
                <div class="store-grid">
                    <?php foreach ($storeGames as $g): ?>
                        <div class="store-card">
                            <div class="store-img-wrap">
                                <img src="<?= htmlspecialchars($g['image']) ?>"
                                     alt="<?= htmlspecialchars($g['title']) ?>" loading="lazy"/>
                                <?php if (!empty($g['tag'])): ?>
                                    <span class="discount-badge"><?= htmlspecialchars($g['tag']) ?></span>
                                <?php endif; ?>
                                <div class="store-hover-overlay">
                                    <button class="btn-cart-quick">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="9" cy="21" r="1"/>
                                            <circle cx="20" cy="21" r="1"/>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                                        </svg>
                                        Ajouter au panier
                                    </button>
                                </div>
                            </div>
                            <div class="store-info">
                                <span class="store-game-name"><?= htmlspecialchars($g['title']) ?></span>
                                <div class="store-price-row">
                                    <?php if (!empty($g['oldPrice'])): ?>
                                        <span class="old-price"><?= htmlspecialchars($g['oldPrice']) ?></span>
                                    <?php endif; ?>
                                    <span class="store-price <?= $g['price'] === 'Gratuit' ? 'free' : '' ?>"><?= htmlspecialchars($g['price']) ?></span>
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

<script src="/assets/js/app.js"></script>
</body>
</html>