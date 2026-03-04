# Jeux Épiques

## Introduction

The goal of this project is to build a web application simulating a video game store and launcher, inspired by the Epic Games Store. Users can browse available games, create an account, log in, purchase games, manage their library, view individual game pages, and unlock achievements. An admin panel allows privileged users to manage the game catalog and user accounts. The application is built with PHP 8.3, uses MySQL for persistence, and is fully containerized with Docker (PHP-FPM + Nginx).

## Requirements

In order to run the application, you need to install:

- **Docker** and **Docker Compose**
  You can download Docker for free from the [official website](https://www.docker.com/products/docker-desktop/).

- A **MySQL** database (remote or local) with the required tables (`Users`, `Game`, `Library_game`, `Achievement`, `Achievement_association`).

## How to use the application

### 1. Clone the repository and configure the environment

```bash
git clone <repository-url>
cd JeuxEpique
cp .env.example .env
```

Edit the `.env` file with your database credentials:

```dotenv
DB_HOST=your-host
DB_PORT=3306
DB_USER=your-user
DB_PASSWORD=your-password
DB_NAME=your-database
```

### 2. Launch with Docker Compose

```bash
docker compose up --build
```

Wait for the containers to start. Once ready, open your browser and navigate to:

```
http://localhost:8080
```

You are ready to use the application.

## Features

### Guest Pages

Guests (unauthenticated visitors) can:

- **Browse the store** — View all available games on the home page with a hero carousel and a grid of popular games
- **View a game page** — See game details (image, description, genre, price) and its achievements
- **Register** — Create a new account with username, email, phone, and password
- **Log in** — Authenticate with an existing account

### User Pages

Once logged in, a user can:

- **Purchase a game** — Select a game, confirm the payment, and add it to their library (with duplicate purchase protection)
- **Access the library** — View all owned games with a search/filter feature
- **View a game page** — See game details and **unlock achievements** for owned games
- **Log out** — Disconnect via the sidebar logout button

### Admin Panel

Admin users have access to a dedicated panel to:

- **Manage games** — Add, edit (name, type, price, description, image), and delete games from the catalog
- **Manage users** — View all users, promote a user to admin, or delete an account

## Database Schema

| Table                     | Description                                      |
|---------------------------|--------------------------------------------------|
| `Users`                   | User accounts (username, email, phone, password, admin flag) |
| `Game`                    | Game catalog (name, type, description, image, price, difficulty) |
| `Library_game`            | User–Game ownership (user ID, game ID, playtime, added date) |
| `Achievement`             | Achievements per game (name, description, game ID) |
| `Achievement_association` | Unlocked achievements per user (user ID, achievement ID) |

## Project Structure

```
/JeuxEpique
│── public/                          # Web root (served by Nginx)
│   ├── index.php                    # Front controller / Router
│   ├── assets/
│   │   ├── css/
│   │   │   ├── app.css              # Global variables & base styles
│   │   │   ├── overlay.css          # Sidebar, top bar, modal & layout
│   │   │   ├── home.css             # Home page (hero, store grid, footer)
│   │   │   ├── library.css          # Library page (game cards, search)
│   │   │   ├── game.css             # Game detail page (hero, achievements)
│   │   │   ├── payment.css          # Payment page
│   │   │   ├── admin.css            # Admin panel (tables, forms, tabs)
│   │   │   ├── connection.css       # Login & register forms
│   │   ├── images/
│   │       └── jeux_epiques_logo.png
│── src/                             # Backend logic
│   ├── Auth.php                     # Authentication (register, login, session, admin check)
│   ├── AdminActions.php             # Admin operations (delete user, promote user)
│   ├── refactorer.php               # Utility functions (price formatting)
│── views/                           # PHP templates
│   ├── home.php                     # Store home page
│   ├── library.php                  # User game library
│   ├── game.php                     # Game detail & achievements page
│   ├── payment.php                  # Payment / purchase page
│   ├── admin.php                    # Admin panel
│   ├── login.php                    # Login form
│   ├── register.php                 # Registration form
│   ├── 404.php                      # Not found page
│── nginx/
│   └── default.conf                 # Nginx configuration (PHP-FPM proxy)
│── docker-compose.yml               # Docker services (PHP-FPM + Nginx)
│── Dockerfile                       # PHP 8.3 FPM image with PDO MySQL
│── composer.json                    # PHP dependencies (phpdotenv)
│── .env.example                     # Environment variables template
│── README.md                        # Project documentation
```

### Key Components

- **`public/index.php`** — Front controller handling all routing via a `switch` on the URL path. Manages GET/POST logic for every page, applies the PRG (Post-Redirect-Get) pattern for form submissions.
- **`src/Auth.php`** — Handles user registration, authentication (password hashing with `password_hash`/`password_verify`), session management, username retrieval, and admin role verification.
- **`src/AdminActions.php`** — Provides admin-only operations (delete user, promote to admin) with authorization checks.
- **`src/refactorer.php`** — Utility functions (price formatting).
- **`views/`** — PHP templates rendered by the front controller via `extract()`. Each view receives its data as variables.

### Architecture

- **Front Controller pattern** — A single entry point (`public/index.php`) routes all requests.
- **PRG pattern (Post-Redirect-Get)** — All POST actions redirect to avoid form re-submission on refresh.
- **MVC-like separation** — Routing/logic in `index.php`, business logic in `src/`, templates in `views/`, styles in `assets/css/`.

## Technologies Used

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP        | 8.3     | Backend language |
| MySQL      | —       | Database |
| Nginx      | 1.27    | Web server / reverse proxy |
| Docker     | —       | Containerization |
| Composer   | —       | PHP dependency management |
| phpdotenv  | 5.6     | Environment variable loading |

## About the project

### Versions

- **1.0** — Release 04/03/2026

### Authors

- Noah CHARRIN--BOURRAT,
- Raphaël BONNET
