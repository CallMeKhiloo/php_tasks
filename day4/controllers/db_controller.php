<?php

function create_users_table($pdo)
{
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        room_no VARCHAR(50),
        ext VARCHAR(50),
        profile_picture VARCHAR(255)
    )";

    try {
        $pdo->exec($sql);
        return true;
    } catch (PDOException $e) {
        echo ("Table creation failed: " . $e->getMessage());
    }
}

function insert_user($pdo, $name, $email, $password, $room_no, $ext, $profile_picture)
{
    $sql = "INSERT INTO users (name, email, password, room_no, ext, profile_picture) VALUES (:name, :email, :password, :room_no, :ext, :profile_picture)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => md5($password),
            ':room_no' => $room_no,
            ':ext' => $ext,
            ':profile_picture' => $profile_picture
        ]);
        return true;
    } catch (PDOException $e) {
        echo ("User insertion failed: " . $e->getMessage());
    }
}

function get_user_by_id($pdo, $id)
{
    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        echo ("Fetching user failed: " . $e->getMessage());
    }
}

function edit_user($pdo, $id, $name, $email, $current_password, $room_no, $ext, $profile_picture)
{
    $sql = "UPDATE users SET name = :name, email = :email, password = :password, room_no = :room_no, ext = :ext, profile_picture = :profile_picture WHERE id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':password' => $current_password,
            ':room_no' => $room_no,
            ':ext' => $ext,
            ':profile_picture' => $profile_picture
        ]);
        return true;
    } catch (PDOException $e) {
        echo ("User update failed: " . $e->getMessage());
    }
}

function delete_user($pdo, $id)
{
    $sql = "DELETE FROM users WHERE id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return true;
    } catch (PDOException $e) {
        echo ("User deletion failed: " . $e->getMessage());
    }
}

function get_all_users($pdo)
{
    $sql = "SELECT * FROM users";

    try {
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo ("Fetching users failed: " . $e->getMessage());
    }
}
