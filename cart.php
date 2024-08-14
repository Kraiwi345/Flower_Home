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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Flower's Home</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/logoheader.png" rel="apple-touch-icon">
  <script src="https://kit.fontawesome.com/31da23dcf7.js" crossorigin="anonymous"></script>
 
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sarabun:ital,wght@0,300;1,100&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Delicious
  * Template URL: https://bootstrapmade.com/delicious-free-restaurant-bootstrap-theme/
  * Updated: May 16 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  <style>
    body{
      font-family: "Kanit", sans-serif !important;

    }

  </style>
  <!-- ======= Top Bar ======= -->
  <!-- <section id="topbar" class="d-flex align-items-center fixed-top topbar-transparent">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-center justify-content-lg-start">
      <i class="bi bi-phone d-flex align-items-center"><span>+1 5589 55488 55</span></i>
      <i class="bi bi-clock ms-4 d-none d-lg-flex align-items-center"><span>Mon-Sat: 11:00 AM - 23:00 PM</span></i>
    </div>
  </section> -->

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center header-transparent">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <div class="logo me-auto">
        <h1><a href="index.php">Flower's Home <i class="fa-brands fa-pagelines"></i></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="index.php" href="#hero">หน้าแรก</a></li>
          <li><a class="nav-link scrollto" href="#about">สินค้าขายดี</a></li>
          <li><a class="nav-link scrollto" href="#menu">ธีมมาแรง</a></li>
          <li><a class="nav-link scrollto" href="#chefs">สมาชิก</a></li>

          <!-- <li><a class="nav-link scrollto" href="#gallery">แกลอรี่</a></li> -->
          <li class="dropdown"><a href="#"><span>สั่งซื้อสินค้า</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li class="dropdown"><a href="#"><span>ดอกไม้</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="flower_25.php" href="#flower_25">ดอกไม้ช่อ 25 บาท</a></li>
                  <li><a href="expensive_flowers.php">ดอกไม้ราคาแพง</a></li>
                
                </ul>
              </li>
              <li><a href="bunch_of_flowers.php">ช่อดอกไม้</a></li>
              <li><a href="flower_vase.php">แจกันดอกไม้</a></li>
              <li><a href="flower_basket.php">กระเช้าดอกไม้</a></li>
              <li><a href="bouquet_of_money.php">ช่อเงิน</a></li>
              <li><a href="price_of_flowers.php">ดอกไม้จับราคา</a></li>
              <li><a href="flower_wrapping_pape.php">กระด่าษห่อดอกไม้</a></li>
              <li><a href="other_equipment.php">อุปกรณ์อื่นๆ</a></li>
              <hr>
              <li><a href="add.php">เพิ่มข้อมูล</a></li>

            </ul>
          </li>
          <li><a class="nav-link scrollto" href="#events">โปรโมชั่น</a></li>
          <li><a class="nav-link scrollto" href="#gallery">แกลอรี่</a></li>
          <li><a class="nav-link scrollto" href="#contact">ติดต่อ</a></li>
          <style>
            .cart {
    background-color: #ffffff; /* พื้นหลังของเมนูตะกร้า */
    color: #000000; /* สีของข้อความในเมนูตะกร้า */
    padding: 15px;
    border-radius: 20px; /* มุมโค้ง */
    margin-bottom: 0;
}

/* สไตล์สำหรับจำนวนสินค้าตะกร้า */
#cart_count {
    background-color: #e95ba2; /* พื้นหลังของจำนวนสินค้า */

    border-radius: 5px;
    padding: 5px 15px;
    margin-left: 10px;
    font-weight: bold;
}
          </style>
          <li>
            <a href="cart.php" class="nav-item nav-link active">
                <h5 class="px-5 cart">
                    <i class="fas fa-shopping-cart"></i>
                    <?php
                    if (isset($_SESSION['cart'])){
                        $count = 0;
                        foreach($_SESSION['cart'] as $v){
                            $count += $v;
                        }
                        echo "<span id=\"cart_count\" class=\"text-light\">$count</span>";
                    } else {
                        echo "<span id=\"cart_count\" class=\"text-light\">0</span>";
                    }
                    ?>
                    Cart
                </h5>
            </a>
        </li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <!-- <a href="#book-a-table" class="book-a-table-btn scrollto">Book a table</a> -->

    </div>
  </header><!-- End Header -->
    <style>
        .container{
            margin-top: 10% ;
            background-color:#ffdee0 ;
            padding: 20px;
            border-radius: 20px;
        }
        .price-details{
          padding: 10px;
        }
</style>
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
                                error_reporting(0);
                                echo '        <a href="checkout.php?action=add&id=' . $id . '" class="btn btn-success">  <i class="fas fa-shopping-cart"></i> ยืนยันการสั่งซื้อสินค้า</a>';
                                ?>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


<?php include('footer.html'); ?>