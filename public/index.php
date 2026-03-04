<?php
declare(strict_types=1);
session_start();
require_once(__DIR__ . '/../src/Auth.php');
require __DIR__ . '/../vendor/autoload.php';

use App\AdminActions;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


$uri = "mysql://" . $_ENV["DB_USER"] . ":" . $_ENV["DB_PASSWORD"] . "@" . $_ENV["DB_HOST"] . ":" . $_ENV["DB_PORT"] . "/" . $_ENV["DB_NAME"] . "?ssl-mode=REQUIRED";

$fields = parse_url($uri);

$conn = "mysql:";
$conn .= "host=" . $_ENV["DB_HOST"];
$conn .= ";port=" . $_ENV["DB_PORT"];
$conn .= ";dbname=jeux-epiques" ;
$conn .= ";sslmode=verify-ca;sslrootcert=ca.pem";

try {
    $db = new PDO($conn, $fields["user"], $fields["pass"]);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

$auth = new Auth($db);

$userId = $auth->loggedInUser();

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . "/../views/{$view}.php";
}

switch ($path) {
    case '/':
        $username = $auth->getUsername();
        $gamesInfo = $db->query('SELECT * FROM Game')->fetchAll(PDO::FETCH_ASSOC);
        render('home', ['title' => 'Accueil', 'username' => $username, 'isAdmin' => $auth->isAdmin($userId), 'gamesInfo' => $gamesInfo]);


        if (isset($_POST['logout'])) {
            $auth->logUserOut();
            header('Location: /');
            exit;
        }
        break;

    case '/library':
        $username = $auth->getUsername();
        $libraryGames = [];

        if ($userId !== false) {
            $stmt = $db->prepare('
                SELECT g.id, g.game_name, g.game_image, g.game_type
                FROM Game g
                INNER JOIN Library_game l ON l.id_game = g.id
                WHERE l.id_user = :userId
            ');
            $stmt->execute([':userId' => $userId]);
            $libraryGames = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        render('Library', [
            'title'        => 'Bibliothèque',
            'username'     => $username,
            'isAdmin'      => $auth->isAdmin($userId),
            'libraryGames' => $libraryGames,
        ]);
        break;


    case '/payment':

        if ($userId === false) {
            header('Location: /login');
            exit;
        }

        $username = $auth->getUsername();
        $game = null;
        $alreadyOwned = false;
        $message = '';

        $gameId = isset($_GET['game_id']) ? (int)$_GET['game_id'] : 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $userId !== false) {
            $gameId = (int)($_POST['game_id'] ?? 0);

            $stmtCheck = $db->prepare('SELECT 1 FROM Library_game WHERE id_user = :userId AND id_game = :gameId');
            $stmtCheck->execute([':userId' => $userId, ':gameId' => $gameId]);

            if ($stmtCheck->fetchColumn()) {
                $alreadyOwned = true;
                $message = 'Vous possédez déjà ce jeu.';
            } else {
                try {
                    $gameTime = rand(0, 500);
                    $addedDate = date('Y-m-d H:i:s');
                    $stmtInsert = $db->prepare('INSERT INTO Library_game (id_user, id_game, game_time, added_date) VALUES (:userId, :gameId, :gameTime, :addedDate)');
                    $stmtInsert->execute([
                        ':userId' => $userId,
                        ':gameId' => $gameId,
                        ':gameTime' => $gameTime,
                        ':addedDate' => $addedDate
                    ]);
                    header('Location: /library');
                    exit;
                } catch (PDOException $e) {
                    $message = 'Erreur lors du paiement : ' . $e->getMessage();
                    error_log($e->getMessage());
                }
            }
        }

        if ($gameId > 0) {
            $stmtGame = $db->prepare('SELECT id, game_name, game_image, game_type, description, price FROM Game WHERE id = :gameId');
            $stmtGame->execute([':gameId' => $gameId]);
            $game = $stmtGame->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        if ($game && $userId !== false && !$alreadyOwned) {
            $stmtCheck2 = $db->prepare('SELECT 1 FROM Library_game WHERE id_user = :userId AND id_game = :gameId');
            $stmtCheck2->execute([':userId' => $userId, ':gameId' => $game['id']]);
            $alreadyOwned = (bool)$stmtCheck2->fetchColumn();
        }

        render('payment', [
            'title'        => 'Paiement',
            'username'     => $username,
            'isAdmin'      => $auth->isAdmin($userId),
            'game'         => $game,
            'alreadyOwned' => $alreadyOwned,
            'message'      => $message,
        ]);
        break;


    case '/game':
        $username = $auth->getUsername();
        $gameId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $game = null;
        $achievements = [];
        $unlockedIds = [];
        $ownsGame = false;

        if ($gameId > 0) {
            $stmtGame = $db->prepare('SELECT * FROM Game WHERE id = :id');
            $stmtGame->execute([':id' => $gameId]);
            $game = $stmtGame->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        if (!$game) {
            http_response_code(404);
            render('404', ['title' => 'Jeu introuvable']);
            break;
        }

        // Récupérer les succès du jeu
        $stmtAch = $db->prepare('SELECT * FROM Achievement WHERE game_id = :gameId');
        $stmtAch->execute([':gameId' => $gameId]);
        $achievements = $stmtAch->fetchAll(PDO::FETCH_ASSOC);

        if ($userId !== false) {
            // Vérifier si l'utilisateur possède le jeu
            $stmtOwns = $db->prepare('SELECT 1 FROM Library_game WHERE id_user = :userId AND id_game = :gameId');
            $stmtOwns->execute([':userId' => $userId, ':gameId' => $gameId]);
            $ownsGame = (bool)$stmtOwns->fetchColumn();

            // Récupérer les succès débloqués
            $stmtUnlocked = $db->prepare('SELECT achievement_id FROM Achievement_association WHERE user_id = :userId');
            $stmtUnlocked->execute([':userId' => $userId]);
            $unlockedIds = $stmtUnlocked->fetchAll(PDO::FETCH_COLUMN);

            // Débloquer un succès via POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unlock_achievement_id']) && $ownsGame) {
                $achId = (int)$_POST['unlock_achievement_id'];
                // Vérifier que le succès appartient bien à ce jeu
                $stmtCheckAch = $db->prepare('SELECT 1 FROM Achievement WHERE id = :achId AND game_id = :gameId');
                $stmtCheckAch->execute([':achId' => $achId, ':gameId' => $gameId]);
                if ($stmtCheckAch->fetchColumn()) {
                    // Vérifier pas déjà débloqué
                    $stmtCheckUnlock = $db->prepare('SELECT 1 FROM Achievement_association WHERE user_id = :userId AND achievement_id = :achId');
                    $stmtCheckUnlock->execute([':userId' => $userId, ':achId' => $achId]);
                    if (!$stmtCheckUnlock->fetchColumn()) {
                        $stmtInsertAch = $db->prepare('INSERT INTO Achievement_association (user_id, achievement_id) VALUES (:userId, :achId)');
                        $stmtInsertAch->execute([':userId' => $userId, ':achId' => $achId]);
                    }
                }
                header('Location: /game?id=' . $gameId);
                exit;
            }
        }

        render('game', [
            'title'        => $game['game_name'],
            'username'     => $username,
            'isAdmin'      => $auth->isAdmin($userId),
            'game'         => $game,
            'achievements' => $achievements,
            'unlockedIds'  => $unlockedIds,
            'ownsGame'     => $ownsGame,
        ]);
        break;

    case '/admin':
        if ($auth->isAdmin($userId) === false) {
            header('Location: /');
            exit;
        }
        $adminActions = new AdminActions($userId, $auth, $db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['logout'])) {
                $auth->logUserOut();
                header('Location: /');
                exit;
            }

            if (isset($_POST['delete_user_id'])) {
                try {
                    $adminActions->deleteUser((int)$_POST['delete_user_id']);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }

            if (isset($_POST['id'])) {
                try {
                    $adminActions->promoteUser((int)$_POST['id']);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }

            if (isset($_POST['delete_game_id'])) {
                try {
                    $stmt = $db->prepare('DELETE FROM Game WHERE id = :id');
                    $stmt->execute(['id' => (int)$_POST['delete_game_id']]);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }

            if (isset($_POST['game_name'], $_POST['game_type'], $_POST['price'], $_POST['description'])) {
                try {
                    $imgToBase64 = '';
                    if (isset($_FILES['game_image']) && $_FILES['game_image']['error'] === UPLOAD_ERR_OK) {
                        $imgToBase64 = base64_encode(file_get_contents($_FILES['game_image']['tmp_name']));
                    }
                    if (isset($_POST['edit_game_id'])) {
                        $stmt = $db->prepare('SELECT game_image FROM Game WHERE id = :id');
                        $imgAlreadyInDb = $stmt->execute(['id' => (int)$_POST['edit_game_id']]) ? $stmt->fetch(PDO::FETCH_ASSOC)['game_image'] : null;
                        $stmt = $db->prepare('UPDATE Game SET game_name = :game_name, game_type = :game_type, description = :description, game_image = :game_image, price = :price WHERE id = :id');
                        $stmt->execute([
                            'game_name' => $_POST['game_name'],
                            'game_type' => $_POST['game_type'],
                            'description' => $_POST['description'],
                            'game_image' => $imgToBase64 === "" ? $imgAlreadyInDb : $imgToBase64,
                            'price' => (float)$_POST['price'],
                            'id' => (int)$_POST['edit_game_id']
                        ]);
                    } else {
                        $stmt = $db->prepare('INSERT INTO Game (game_name, game_type, description, game_image, price) VALUES (:game_name, :game_type, :description, :game_image, :price)');
                        $stmt->execute([
                            'game_name' => $_POST['game_name'],
                            'game_type' => $_POST['game_type'],
                            'description' => $_POST['description'],
                            'game_image' => $imgToBase64,
                            'price' => (float)$_POST['price']
                        ]);
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }

            header('Location: /admin');
            exit;
        }

        $users = $db->query('SELECT id, username, email, phone, admin FROM Users')->fetchAll(PDO::FETCH_ASSOC);
        $username = $auth->getUsername();
        $gamesInfo = $db->query('SELECT * FROM Game')->fetchAll(PDO::FETCH_ASSOC);
        render('admin', ['title' => 'Admin', 'username' => $username, 'isAdmin' => $auth->isAdmin($userId), 'gamesInfo' => $gamesInfo, "users" => $users]);
        break;

    case '/login':
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userId = $auth->authenticate($username, $password);

            if ($userId !== false) {
                $auth->logUserIn($userId);
                header('Location: /');
                exit;
            } else {
                $message = 'Identifiants incorrects';
            }
        }

        render('login', ['title' => 'Connexion', 'message' => $message]);
        break;

    case '/register':
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $password = $_POST['password'] ?? '';

            $userId = $auth->addUser($username, $email, $phone, $password);

            if ($userId !== false) {
                $auth->logUserIn($userId);
                header('Location: /');
                exit;
            } else {
                $message = 'Erreur lors de l\'inscription';
            }
        }

        render('register', ['title' => 'Inscription', 'message' => $message]);
        break;

    default:
        http_response_code(404);
        render('404', ['title' => 'Page introuvable']);
        break;


}


