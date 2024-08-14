<?php
session_start();
include("conn.php");
include("./header_option.php");


// Function to get product data
function getProductData($conn, $pids) {
    if (empty($pids)) return [];
    
    $ids = implode(',', array_map('intval', $pids));
    $sql = "SELECT id, name, description, prev_price, current_price, img_path FROM tbproduct WHERE id IN ($ids)";
    return $conn->query($sql);
}

// Add item to cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header('Location: cart.php');
    exit();
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'removeItem' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header('Location: cart.php');
    exit();
}

// Handle quantity update
if (isset($_POST['action']) && $_POST['action'] == "update_qty" && isset($_POST['pid']) && isset($_POST['quantity'])) {
    $pid = intval($_POST['pid']);
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        $_SESSION['cart'][$pid] = $quantity;
    } else {
        unset($_SESSION['cart'][$pid]);
    }
    header('Location: cart.php');
    exit();
}
// Function to clear the cart
function clearCart() {
  unset($_SESSION['cart']);
}

// Clear cart if the action is to clear the cart
if (isset($_GET['action']) && $_GET['action'] == 'clear_cart') {
  clearCart();
  header('Location: cart.php');
  exit();
}


// Check login status
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!-- debug check login นะ
<?php echo $_SESSION['fname'] ?> -->


        <div class="container">
            <div class="row px-5">
                <div class="col-md-7">
                    <div class="shopping-cart">
                        <h2>ตะกร้าสินค้า</h2>
                        <hr>


                        <?php
                        $total = 0;
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            $pids = array_keys($_SESSION['cart']);
                            $result = getProductData($conn, $pids);
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = htmlspecialchars($row["id"]);
                                    $name = htmlspecialchars($row["name"]);
                                    $description = htmlspecialchars($row["description"]);
                                    $prevPrice = (float) htmlspecialchars($row["prev_price"]);
                                    $currentPrice = (float) htmlspecialchars($row["current_price"]);
                                    $fileName = htmlspecialchars($row["img_path"]);
                                    $filePath = 'uploaded_files/' . $fileName;

                                    if ($currentPrice > 0) {
                                        $displayPrice = $currentPrice;
                                        $formattedPrice = number_format($currentPrice, 2);
                                    } else {
                                        $displayPrice = $prevPrice;
                                        $formattedPrice = number_format($prevPrice, 2);
                                    }

                                    $itemTotal = $displayPrice * intval($_SESSION['cart'][$id]);
                                    $total += $itemTotal;

                                    echo '<div class="row border-top border-bottom py-3">';
                                    echo '    <div class="col-md-3">';
                                    echo '        <img src="' . $filePath . '" alt="Product Image" class="img-fluid">';
                                    echo '    </div>';
                                    echo '    <div class="col-md-6">';
                                    echo '        <h5>ชื่อสินค้า : ' . htmlspecialchars($name) . '</h5>';
                                    echo '        <p>รายละเอียดสินค้า : ' . htmlspecialchars($description) . '</p>';
                                    echo '        <p>ราคา : ' . $formattedPrice . '</p>';

                                    // Form for updating quantity
                                    echo '        <form method="POST" action="cart.php">';
                                    echo '            <input type="hidden" name="action" value="update_qty">';
                                    echo '            <input type="hidden" name="pid" value="' . $id . '">';
                                    echo '            <div class="input-group mb-3">';
                                    echo '                <input type="text" class="form-control text-center" name="quantity" value="' . intval($_SESSION['cart'][$id]) . '">';
                                    echo '                <div class="input-group-append">';
                                    echo '                    <button class="btn btn-outline-secondary" type="submit" name="quantity" value="' . (intval($_SESSION['cart'][$id]) + 1) . '">+</button>';
                                    echo '                    <button class="btn btn-outline-secondary" type="submit" name="quantity" value="' . (intval($_SESSION['cart'][$id]) - 1) . '">-</button>';
                                    echo '                </div>';
                                    echo '            </div>';
                                    echo '        </form>';
                                    echo '    </div>';
                                    echo '    <div class="col-md-3 text-right">';
                                    echo '        <a href="cart.php?action=removeItem&id=' . $id . '" class="btn btn-danger">ลบรายการ</a>';
                                    echo '    </div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<h5>No products found in the cart.</h5>';
                            }
                        } else {
                            echo '<h5>เลือกซื้อสินค้าที่ชอบก่อน..</h5>';
                        }
                        ?>

                    </div>
                </div>
                <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">

                    <div class="pt-4">
                        <h5>ทั้งหมด</h5>
                        <hr>
                        <div class="row price-details">
                            <div class="col-md-6">
                                <?php
                                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                    $count = count($_SESSION['cart']);
                                    echo "<h6>ราคา : </h6>";
                                } else {
                                    echo "<h7>--ไม่พบสินค้า--</h7>";
                                }
                                ?>

                                <hr>
                                <h6>ราคารวมทั่งสิ้น</h6>
                            </div>
                            <div class="col-md-6">
                                <h6><?php echo number_format($total, 2). " บาท"; ?></h6>
                                <hr>
                                <h6>$<?php echo number_format($total, 2); ?></h6>
                            </div>
                            <hr>
                            <div class="col-12 text-center">
                              <?php
                                if ($isLoggedIn) {
                                    echo '<a href="checkout.php?action=add&id=' . $id . '" class="btn btn-success"><i class="fas fa-shopping-cart"></i> ยืนยันการสั่งซื้อสินค้า</a>';
                                } else {
                                    echo '<a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-in-alt"></i> กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ</a>';
                                }
                              ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


<?php include('footer.html'); ?>