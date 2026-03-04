<?php

namespace App;

use Auth;
use PDO;

class AdminActions
{


    public function __construct(
        private int $userId,
        private Auth $auth,
        private PDO $db,
    )
    {

    }

    public function deleteUser(int $otherUserId): void
    {
        if ($this->auth->isAdmin($this->userId) === false) {
            throw new \Exception("Unauthorized");
        }

        $stmt = $this->db->prepare('DELETE FROM Users WHERE id = :id');
        $stmt->execute(['id' => $otherUserId]);
    }

    public function promoteUser(int $otherUserId): void
    {
        if ($this->auth->isAdmin($this->userId) === false) {
            throw new \Exception("Unauthorized");
        }

        $stmt = $this->db->prepare('UPDATE Users SET admin = 1 WHERE id = :id');
        $stmt->execute(['id' => $otherUserId]);
    }
}