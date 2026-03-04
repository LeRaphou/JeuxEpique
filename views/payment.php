<?php
require(__DIR__ . '/../src/refactorer.php');
$username = $username ?? false;
$gameId = $_GET['game_id'] ?? null;
$gameInfo = [];
$game = $game ?? null;
$alreadyOwned = $alreadyOwned ?? false;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Jeux Épiques – Paiement</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css"/>
    <link rel="stylesheet" href="/assets/css/payment.css"/>
</head>
<body>
<div class="launcher">
    <aside class="sidebar">
        <!-- Copiez la sidebar depuis views/home.php pour cohérence -->
    </aside>
    <main class="main">

        <div class="scroll-area">
            <?php if (!$username): ?>
                <div class="lib-empty">
                    <h2>Connexion requise</h2>
                    <p>Vous devez être connecté pour procéder au paiement.</p>
                    <a href="/login" class="btn-buy">Se connecter</a>
                </div>
            <?php elseif (!$gameId): ?>
                <div class="lib-empty">
                    <h2>Jeu non spécifié</h2>
                    <p>Aucun jeu sélectionné pour le paiement.</p>
                    <a href="/" class="btn-buy">Retour à la boutique</a>
                </div>
            <?php else: ?>
                <div class="pay-wrap">
                    <div class="pay-card">

                        <!-- Image à gauche -->
                        <div class="pay-img">
                            <img src="data:image/png;base64,<?= htmlspecialchars($game['game_image']) ?>"
                                 alt="<?= htmlspecialchars($game['game_name']) ?>"/>
                        </div>

                        <!-- Infos à droite -->
                        <div class="pay-info">
                            <span class="pay-type">Jeu</span>
                            <h2 class="pay-title"><?= htmlspecialchars($game['game_name']) ?></h2>
                            <p class="pay-desc"><?= htmlspecialchars($game['description']) ?></p>

                            <div class="pay-price-row">
                                <span class="pay-label">Prix :</span>
                                <?php if ($game['price'] == 0): ?>
                                    <span class="pay-price free">Gratuit</span>
                                <?php else: ?>
                                    <span class="pay-price"><?= number_format($game['price'], 2) ?> €</span>
                                <?php endif; ?>
                            </div>


                            <div class="pay-actions">
                                <?php if (!empty($alreadyOwned)): ?>
                                    <p class="pay-owned">Vous possédez déjà ce jeu dans votre bibliothèque.</p>
                                    <a href="/" class="btn-confirm">Retour à la boutique</a>
                                <?php else: ?>
                                    <form action="/payment" method="post">
                                        <input type="hidden" name="game_id" value="<?= (int)$gameId ?>">
                                        <input type="hidden" name="confirm" value="1">
                                        <button type="submit" class="btn-confirm">Confirmer le paiement</button>
                                    </form>
                                    <a href="/" class="btn-cancel">Annuler</a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>
</div>
</body>
</html>
