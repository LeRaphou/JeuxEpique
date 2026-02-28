<?php

class Auth
{
    public function __construct(
        protected PDO $db,
    )
    {

    }

    public function addUser(string $username, string $email, string $phone, string $password): int|false
    {
        $username = trim($username);
        $email = trim($email);
        $phone = trim($phone);
        $password = trim($password);

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO Users (username, email, phone, password, admin) VALUES (:username, :email, :phone, :password, 0)");
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":phone" => $phone,
                ":password" => $hash
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }

        $id = $this->db->lastInsertId();

        if ($id === false) {
            error_log("Failed to retrieve last insert ID");
            return false;
        }

        return intval($id);
    }

    public function authenticate(string $username, string $password): int|false
    {
        $username = trim($username);
        $password = trim($password);

        try {
            $stmt = $this->db->prepare("SELECT id, password FROM Users WHERE username = :username");
            $stmt->execute([
                ":username" => $username,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return false;
        }

        if (password_verify($password, $user["password"])) {
            return intval($user["id"]);
        }

        return false;
    }

    public function logUserIn(int $userId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['logged_in_user_id'] = $userId;
    }

    public function logUserOut(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['logged_in_user_id'] = null;
    }

    public function loggedInUser(): int|false
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['logged_in_user_id'] ?? false;
    }

    public function getUsername(): string|false
    {
        $userId = $this->loggedInUser();
        if ($userId === false) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("SELECT username FROM Users WHERE id = :id");
            $stmt->execute([
                ":id" => $userId,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return false;
        }

        return $user["username"];
    }
}
