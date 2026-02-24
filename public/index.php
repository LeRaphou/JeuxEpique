<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

function render(string $view, array $data = []): void {
    extract($data, EXTR_SKIP);
    require __DIR__ . "/../views/{$view}.php";
}

switch ($path) {
    case '/':
        render('home', ['title' => 'Accueil']);
        break;

    default:
        http_response_code(404);
        render('404', ['title' => 'Page introuvable']);
}
