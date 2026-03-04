<?php
$gamesInfo = $gamesInfo ?? [];
$users = $users ?? [];
$message = $message ?? '';
$username = $username ?? '';
$isAdmin = $isAdmin ?? false;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Jeux Épiques</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/overlay.css">
</head>
<body>
<div id="user_modal" class="modal">
    <div class="modal_content">
        <h1><?= $username ?></h1>

        <form method="post" action="">
            <input type="hidden" name="logout" value="true"/>
            <button class="btn-modal">Se déconnecter</button>
        </form>

        <a href="/admin" class="modal_close">×</a>
    </div>
</div>
<div class="launcher">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="/assets/images/jeux_epiques_logo.png" class="sidebar-logo">
        </div>

        <nav class="sidebar-nav">
            <a href="/" class="s-btn" title="Accueil">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
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
            <?php if ($isAdmin) : ?>
                <a href="/admin" class="s-btn active" title="Admin">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1v2"/>
                        <path d="M12 21v2"/>
                        <path d="M4.22 4.22l1.42 1.42"/>
                        <path d="M18.36 18.36l1.42 1.42"/>
                        <path d="M1 12h2"/>
                        <path d="M21 12h2"/>
                        <path d="M4.22 19.78l1.42-1.42"/>
                        <path d="M18.36 5.64l1.42-1.42"/>
                    </svg>
                </a>
            <?php endif; ?>
            <?php if ($username !== false): ?>
                <a href="#" class="s-btn" title="Paramètres">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                </a>
                <a href="#user_modal" class="s-avatar">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                    <span class="online-dot"></span>
                </a>
            <?php endif; ?>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- Header right -->
        <header class="top-bar">
            <div class="top-bar-left">
                <span class="top-bar-label">Administration</span>
            </div>
            <div class="top-bar-right">
                <button class="top-btn" title="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 01-3.46 0"/>
                    </svg>
                    <span class="notif-dot"></span>
                </button>
                <div class="top-user">
                    <?php if (!$username) : ?>
                        <a href="/login">Se connecter</a>
                    <?php else : ?>
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar"/>
                        <span><?= $username ?></span>
                    <?php endif ?>
                </div>
            </div>
        </header>
        <div class="admin-wrapper">
            <div>
                <?php if (!empty($message)): ?>
                    <div class="admin-message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <div class="tab-content" id="content-games">

                    <div class="admin-section">
                        <h2><?= isset($_GET['edit_game']) ? 'Modifier un amusement vidéoludique' : 'Ajouter un amusement vidéoludique' ?></h2>

                        <?php
                        $editGame = null;
                        if (isset($_GET['edit_game'])) {
                            foreach ($gamesInfo as $g) {
                                if ((string)$g['id'] === $_GET['edit_game']) {
                                    $editGame = $g;
                                    break;
                                }
                            }
                        }
                        ?>

                        <form action="/admin" method="post" enctype="multipart/form-data" class="admin-form">
                            <?php if ($editGame): ?>
                                <input type="hidden" name="action" value="edit_game">
                                <input type="hidden" name="edit_game_id"
                                       value="<?= htmlspecialchars($editGame['id']) ?>">
                            <?php else: ?>
                                <input type="hidden" name="action" value="add_game">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="game_name">Nom de l'amusement vidéoludique</label>
                                <input type="text" id="game_name" name="game_name"
                                       value="<?= $editGame ? htmlspecialchars($editGame['game_name']) : '' ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="game_type">Type / Genre</label>
                                <input type="text" id="game_type" name="game_type"
                                       value="<?= $editGame ? htmlspecialchars($editGame['game_type']) : '' ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="price">Prix (€)</label>
                                <input type="number" id="price" name="price" step="0.01" min="0"
                                       value="<?= $editGame ? htmlspecialchars($editGame['price']) : '0' ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="game_image">Image de l'amusement vidéoludique</label>
                                <input type="file" id="game_image" name="game_image" accept="image/*">
                            </div>

                            <div class="form-group full-width">
                                <label for="description">Description</label>
                                <textarea id="description" name="description"
                                          required><?= $editGame ? htmlspecialchars($editGame['description']) : '' ?></textarea>
                            </div>

                            <div class="form-actions">
                                <?php if ($editGame): ?>
                                    <a href="/admin" class="btn btn-secondary">Annuler</a>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary">
                                    <?= $editGame ? 'Enregistrer' : 'Ajouter l\'amusement vidéoludique' ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des jeux -->
                    <div class="admin-section">
                        <h2>Liste des jeux (<?= count($gamesInfo) ?>)</h2>

                        <?php if (empty($gamesInfo)): ?>
                            <div class="empty-state">Aucun amusement vidéoludique trouvé dans la base de données.
                            </div>
                        <?php else: ?>
                            <table class="admin-table">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Genre</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($gamesInfo as $game): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($game['game_image'])): ?>
                                                <img class="game-thumb"
                                                     src="data:image/png;base64,<?= htmlspecialchars($game['game_image']) ?>"
                                                     alt="<?= htmlspecialchars($game['game_name']) ?>">
                                            <?php else: ?>
                                                <span style="color:#555;">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($game['game_name']) ?></td>
                                        <td><?= htmlspecialchars($game['game_type']) ?></td>
                                        <td><?= $game['price'] == 0 ? 'Gratuit' : htmlspecialchars($game['price']) . ' €' ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/admin?edit_game=<?= htmlspecialchars($game['id']) ?>"
                                                   class="btn btn-primary btn-sm">Modifier</a>

                                                <form action="/admin" method="post" style="display:inline;"
                                                      onsubmit="return confirm('Supprimer ce amusement vidéoludique ?');">
                                                    <input type="hidden" name="action" value="delete_game">
                                                    <input type="hidden" name="delete_game_id"
                                                           value="<?= htmlspecialchars($game['id']) ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="content-users">

                <div class="admin-section">
                    <h2>Utilisateurs (<?= count($users) ?>)</h2>

                    <?php if (empty($users)): ?>
                        <div class="empty-state">Aucun utilisateur trouvé.</div>
                    <?php else: ?>
                        <table class="admin-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($user['phone'] ?? '—') ?></td>
                                    <td>
                                        <?php
                                        $isUserAdmin = isset($user['admin']) && $user['admin'];
                                        ?>
                                        <span class="user-role <?= $isUserAdmin ? 'role-admin' : 'role-user' ?>">
                                    <?= $isUserAdmin ? 'Admin' : 'Utilisateur' ?>
                                </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <!-- Promouvoir / Rétrograder -->
                                            <form action="/admin" method="post" style="display:inline;">
                                                <input type="hidden" name="action" value="toggle_admin">
                                                <input type="hidden" name="id"
                                                       value="<?= htmlspecialchars($user['id']) ?>">
                                                <input type="hidden" name="admin"
                                                       value="<?= $isUserAdmin ? '0' : '1' ?>">
                                                <button type="submit"
                                                        class="btn <?= $isUserAdmin ? 'btn-secondary' : 'btn-success' ?> btn-sm"<?php if ($isUserAdmin) {
                                                    echo 'disabled="disabled"';
                                                } ?>>
                                                    <?= $isUserAdmin ? 'Déja Admin' : 'Promouvoir' ?>
                                                </button>
                                            </form>

                                            <!-- Supprimer -->
                                            <form action="/admin" method="post" style="display:inline;"
                                                  onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="id"
                                                       value="<?= htmlspecialchars($user['id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html
