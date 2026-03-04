<?php
require_once __DIR__ . '/../refactorer.php';


$username = $_SESSION['username'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$username || !$userId) {
    header('Location: /login');
    exit;
}

$gameId = $_POST['game_id'] ?? null;

if (!$gameId || !is_numeric($gameId)) {
    header('Location: /');
    exit;
}

$gameId = (int)$gameId;

try {
    $pdo = getPDO();

    // Vérifier que le jeu existe
    $stmt = $pdo->prepare("SELECT id_game FROM Game WHERE id_game = ?");
    $stmt->execute([$gameId]);
    $game = $stmt->fetch();

    if (!$game) {
        header('Location: /?error=game_not_found');
        exit;
    }

    // Vérifier que le jeu n'est pas déjà dans la bibliothèque
    $stmt = $pdo->prepare("SELECT id_game FROM Library_game WHERE id_game = ? AND id_user = ?");
    $stmt->execute([$gameId, $userId]);
    $alreadyOwned = $stmt->fetch();

    if ($alreadyOwned) {
        header('Location: /?error=already_owned');
        exit;
    }

    // Ajouter le jeu à la bibliothèque
    $gameTime = rand(0, 500);
    $addedTime = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("
        INSERT INTO Library_game (id_game, id_user, game_time, added_time)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$gameId, $userId, $gameTime, $addedTime]);

    header('Location: /library?success=1');
    exit;

} catch (PDOException $e) {
    error_log('Erreur paiement : ' . $e->getMessage());
    header('Location: /?error=payment_failed');
    exit;
}