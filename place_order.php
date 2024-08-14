<?php
session_start();
include("conn.php");

// Function to save the order
function placeOrder($conn, $orderData) {
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, phone, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $orderData['name'], $orderData['email'], $orderData['address'], $orderData['phone'], $orderData['total']);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $orderId, $productId, $quantity);
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect to cart.php if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'total' => $_POST['total']
    ];
    
    placeOrder($conn, $orderData);
    
    // Clear the cart
    unset($_SESSION['cart']);
    
    echo "<script>alert('Order placed successfully!')</script>";
    echo "<script>window.location = 'thank_you.php'</script>";
    exit();
}

?>
