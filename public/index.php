<?php
declare(strict_types=1);
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
        render('home', ['title' => 'Accueil', 'username' => $username]);


        if (isset($_POST['logout'])) {
            $auth->logUserOut();
            header('Location: /');
            exit;
        }
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
