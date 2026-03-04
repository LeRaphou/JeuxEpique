<?php
require(__DIR__ . '/../src/refactorer.php');
$username = $username ?? false;
$isAdmin = $isAdmin ?? false;
$game = $game ?? null;
$achievements = $achievements ?? [];
$unlockedIds = $unlockedIds ?? [];
$ownsGame = $ownsGame ?? false;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Jeux Épiques – <?= htmlspecialchars($game['game_name']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css"/>
    <link rel="stylesheet" href="/assets/css/game.css"/>
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
            <a href="/library" class="s-btn" title="Bibliothèque">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                </svg>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <?php if ($isAdmin): ?>
                <a href="/admin" class="s-btn" title="Admin">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path fill="currentColor"
                              d="M12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 8-8m0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6s6 2.685 6 6s-2.685 6-6 6m0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m9 6h1v5h-8v-5h1v-1a3 3 0 1 1 6 0zm-2 0v-1a1 1 0 1 0-2 0v1z"/>
                    </svg>
                </a>
            <?php endif; ?>
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
            <span class="top-bar-label">
                <a href="/" class="back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Retour
                </a>
            </span>
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

            <!-- HERO BANNER -->
            <div class="game-hero">
                <?php if (!empty($game['game_image'])): ?>
                    <img class="game-hero-bg" src="data:image/png;base64,<?= htmlspecialchars($game['game_image']) ?>"
                         alt="<?= htmlspecialchars($game['game_name']) ?>"/>
                <?php endif; ?>
                <div class="game-hero-gradient"></div>
                <div class="game-hero-content">
                    <span class="game-hero-type"><?= htmlspecialchars($game['game_type']) ?></span>
                    <h1 class="game-hero-title"><?= htmlspecialchars($game['game_name']) ?></h1>
                    <p class="game-hero-desc"><?= htmlspecialchars($game['description']) ?></p>
                    <div class="game-hero-actions">
                        <?php if ($game['price'] == 0): ?>
                            <span class="game-hero-price free">Gratuit</span>
                        <?php else: ?>
                            <span class="game-hero-price"><?= number_format($game['price'], 2) ?> €</span>
                        <?php endif; ?>

                        <?php if ($ownsGame): ?>
                            <span class="game-owned-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                     height="16">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Dans votre bibliothèque
                            </span>
                        <?php else: ?>
                            <a href="/payment?game_id=<?= (int)$game['id'] ?>" class="btn-buy-game">
                                <?= $game['price'] == 0 ? 'Obtenir' : 'Acheter' ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ACHIEVEMENTS -->
            <section class="game-section">
                <div class="game-section-head">
                    <h2>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22"
                             height="22">
                            <circle cx="12" cy="8" r="6"/>
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                        </svg>
                        Succès
                    </h2>
                    <?php if (!empty($achievements)): ?>
                        <span class="ach-progress">
                            <?= count(array_filter($achievements, fn($a) => in_array($a['id'], $unlockedIds))) ?>
                            / <?= count($achievements) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if (empty($achievements)): ?>
                    <div class="ach-empty">
                        <p>Aucun succès disponible pour ce jeu.</p>
                    </div>
                <?php else: ?>
                    <div class="ach-grid">
                        <?php foreach ($achievements as $ach):
                            $isUnlocked = in_array($ach['id'], $unlockedIds);
                            ?>
                            <div class="ach-card <?= $isUnlocked ? 'unlocked' : 'locked' ?>">
                                <div class="ach-icon">
                                    <?php if ($isUnlocked): ?>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="8" r="6"/>
                                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                                        </svg>
                                    <?php else: ?>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div class="ach-info">
                                    <span class="ach-name"><?= htmlspecialchars($ach['name']) ?></span>
                                    <span class="ach-desc"><?= htmlspecialchars($ach['description'] ?? '') ?></span>
                                </div>
                                <div class="ach-status">
                                    <?php if ($isUnlocked): ?>
                                        <span class="ach-badge-unlocked">Débloqué</span>
                                    <?php elseif ($ownsGame && $username): ?>
                                        <form method="post" action="/game?id=<?= (int)$game['id'] ?>">
                                            <input type="hidden" name="unlock_achievement_id"
                                                   value="<?= (int)$ach['id'] ?>">
                                            <button type="submit" class="btn-unlock">Débloquer</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="ach-badge-locked">Verrouillé</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

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

