<?php
declare(strict_types=1);
session_start();
require_once(__DIR__ . '/../src/Auth.php');
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


$uri = "mysql://" . $_ENV["DB_USER"] . ":" . $_ENV["DB_PASSWORD"] . "@" . $_ENV["DB_HOST"] . ":" . $_ENV["DB_PORT"] . "/" . $_ENV["DB_NAME"] . "?ssl-mode=REQUIRED";

$fields = parse_url($uri);

$conn = "mysql:";
$conn .= "host=" . $_ENV["DB_HOST"];
$conn .= ";port=" . $_ENV["DB_PORT"];
$conn .= ";dbname=jeux-epiques";
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
        render('home', ['title' => 'Accueil', 'username' => $username, 'isAdmin' => $auth->getUserRole($userId), 'gamesInfo' => $gamesInfo]);


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
                SELECT g.game_name, g.game_image, g.game_type
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
            'isAdmin'      => $auth->getUserRole($userId),
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
            } else {
                $stmtInsert = $db->prepare('INSERT INTO Library_game (id_user, id_game) VALUES (:userId, :gameId)');
                $stmtInsert->execute([':userId' => $userId, ':gameId' => $gameId]);
                header('Location: /library');
                exit;
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
            'isAdmin'      => $auth->getUserRole($userId),
            'game'         => $game,
            'alreadyOwned' => $alreadyOwned,
            'message'      => $message,
        ]);
        break;


    case '/process-payment':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/controllers/ProcessPaymentController.php';
        }
        break;



    case '/admin':
        if ($auth->getUserRole($userId) === false) {
            header('Location: /');
            exit;
        }
        render('admin', ['title' => 'Admin']);
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


