<?php

namespace App;

class AdminActions
{
    private $user;
    private $db;

    public function __construct($user, $db)
    {
        $this->user = $user;
        $this->db = $db;
    }

    public function deleteUser($userId)
    {
        if ($this->user->getUserRole() !== 'admin') {
            throw new \Exception("Unauthorized");
        }

        $stmt = $this->db->prepare('DELETE FROM Users WHERE id = :id');
        $stmt->execute(['id' => $userId]);
    }

    public function promoteUser($userId)
    {
        if ($this->user->getUserRole() !== 'admin') {
            throw new \Exception("Unauthorized");
        }

        $stmt = $this->db->prepare('UPDATE Users SET admin = 1 WHERE id = :id');
        $stmt->execute(['id' => $userId]);
    }
}