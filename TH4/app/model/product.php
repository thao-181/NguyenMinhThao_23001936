<?php

require_once __DIR__ . "/../common/dbConnect.php";

function getAllProducts()
{
    global $conn;

    $sql = "SELECT id, name, price, quantity FROM products ORDER BY id ASC";
    $result = $conn->query($sql);

    $products = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

function getProductById($id)
{
    global $conn;

    $stmt = $conn->prepare(
        "SELECT id, name, price, quantity FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();

    return $product;
}

function addProduct($name, $price, $quantity)
{
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)"
    );

    $stmt->bind_param("sdi", $name, $price, $quantity);

    $success = $stmt->execute();

    $stmt->close();

    return $success;
}

function updateProduct($id, $name, $price, $quantity)
{
    global $conn;

    $stmt = $conn->prepare(
        "UPDATE products
         SET name = ?, price = ?, quantity = ?
         WHERE id = ?"
    );

    $stmt->bind_param("sdii", $name, $price, $quantity, $id);

    $success = $stmt->execute();

    $stmt->close();

    return $success;
}

function deleteProduct($id)
{
    global $conn;

    $stmt = $conn->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $success = $stmt->execute();

    $stmt->close();

    return $success;
}
?>
