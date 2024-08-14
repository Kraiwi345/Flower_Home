<?php
// Include the database connection file
include("conn.php");

// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare the SQL query to fetch product details
    $sql = "SELECT id, name, description, prev_price, current_price, img_path FROM tbproduct WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = htmlspecialchars($row["img_path"]);
        $filePath = 'uploaded_files/' . $fileName;
        $name = htmlspecialchars($row["name"]);
        $description = htmlspecialchars($row["description"]);
        $prevPrice = number_format((float) $row["prev_price"], 2);
        $currentPrice = number_format((float) $row["current_price"], 2);
        
        // Calculate discount if applicable
        $discount = $prevPrice - $currentPrice;
        $formattedDiscount = $discount > 0 ? number_format($discount, 2) . " บ." : "ไม่มีส่วนลด";

        // Display the order form with product details
        echo '<div class="container">';
        echo '    <h2>รายละเอียดการสั่งซื้อ</h2>';
        echo '    <div class="row">';
        echo '        <div class="col-lg-6">';
        echo '            <img src="' . $filePath . '" class="img-fluid" alt="Product Image">';
        echo '        </div>';
        echo '        <div class="col-lg-6">';
        echo '            <h3>' . $name . '</h3>';
        echo '            <p>' . $description . '</p>';
        echo '            <p><strong>ราคาเดิม: </strong><del>' . $prevPrice . ' บ.</del></p>';
        echo '            <p><strong>ราคาโปรโมชั่น: </strong>' . $currentPrice . ' บ.</p>';
        echo '            <p><strong>ส่วนลด: </strong>' . $formattedDiscount . '</p>';
        echo '            <form action="confirm_order.php" method="post">';
        echo '                <input type="hidden" name="id" value="' . $id . '">';
        echo '                <input type="hidden" name="name" value="' . $name . '">';
        echo '                <input type="hidden" name="description" value="' . $description . '">';
        echo '                <input type="hidden" name="prev_price" value="' . $prevPrice . '">';
        echo '                <input type="hidden" name="current_price" value="' . $currentPrice . '">';
        echo '                <input type="submit" class="btn btn-success" value="ยืนยันการสั่งซื้อ">';
        echo '            </form>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
        
    } else {
        echo '<div class="container"><p>ไม่พบข้อมูลสินค้าที่เลือก.</p></div>';
    }
    
    $stmt->close();
} else {
    echo '<div class="container"><p>ไม่มีการระบุรหัสสินค้า.</p></div>';
}

// Close the connection
$conn->close();
?>
