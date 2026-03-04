<?php
declare(strict_types=1);
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
        render('home', ['title' => 'Accueil', 'username' => $username, 'isAdmin' => $auth->isAdmin($userId), 'gamesInfo' => $gamesInfo]);


        if (isset($_POST['logout'])) {
            $auth->logUserOut();
            header('Location: /');
            exit;
        }
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
}
