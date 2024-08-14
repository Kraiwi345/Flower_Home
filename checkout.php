<?php
session_start();
include("conn.php");

// Function to get product data
function getProductData($conn, $pids) {
    if (empty($pids)) return [];
    
    $ids = implode(',', array_map('intval', $pids));
    $sql = "SELECT id, name, description, prev_price, current_price, img_path FROM tbproduct WHERE id IN ($ids)";
    return $conn->query($sql);
}

// Redirect to cart.php if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Get product IDs from the cart
$pids = array_keys($_SESSION['cart']);
$result = getProductData($conn, $pids);

// Calculate total
$total = 0;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Order Confirmation</h2>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <?php if ($result && $result->num_rows > 0): ?>
                <h4>Your Order:</h4>
                <div class="list-group">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $id = htmlspecialchars($row["id"]);
                        $name = htmlspecialchars($row["name"]);
                        $description = htmlspecialchars($row["description"]);
                        $prevPrice = (float) htmlspecialchars($row["prev_price"]);
                        $currentPrice = (float) htmlspecialchars($row["current_price"]);
                        $fileName = htmlspecialchars($row["img_path"]);
                        $filePath = 'uploaded_files/' . $fileName;

                        // Use currentPrice if available, otherwise use prevPrice
                        $displayPrice = $currentPrice > 0 ? $currentPrice : $prevPrice;
                        $formattedPrice = number_format($displayPrice, 2);
                        $quantity = intval($_SESSION['cart'][$id]);
                        $itemTotal = $displayPrice * $quantity;
                        $total += $itemTotal;
                        ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo $name; ?></h5>
                                <small><?php echo number_format($quantity); ?> x ฿<?php echo $formattedPrice; ?></small>
                            </div>
                            <p class="mb-1"><?php echo $description; ?></p>
                            <small>Total: ฿<?php echo number_format($itemTotal, 2); ?></small>
                        </div>
                    <?php endwhile; ?>
                </div>
                <h4 class="mt-4">Total Amount: ฿<?php echo number_format($total, 2); ?></h4>
            <?php else: ?>
                <p>No products found in the cart.</p>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <h4>Shipping Information</h4>
            <form action="place_order.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <input type="hidden" name="total" value="<?php echo number_format($total, 2); ?>">
                <button type="submit" class="btn btn-primary">Place Order</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
