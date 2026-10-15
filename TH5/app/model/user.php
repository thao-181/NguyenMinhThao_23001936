<?php

require_once __DIR__ . "/../common/dbConnect.php";

function getUserByUsername($username)
{
    global $conn;

    $stmt = $conn->prepare(
        "SELECT id, username, password FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();

    return $user;
}

function createUser($username, $password)
{
    global $conn;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (username, password) VALUES (?, ?)"
    );

    $stmt->bind_param("ss", $username, $hashedPassword);

    $success = $stmt->execute();

    $stmt->close();

    return $success;
}
?>