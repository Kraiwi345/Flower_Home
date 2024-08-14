<?php
include ('conn.php');
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามี ID ที่ต้องการลบหรือไม่
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // เตรียมคำสั่ง SQL สำหรับลบข้อมูล
    $sql = "DELETE FROM tbproduct WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "เพิ่มรายการสินค้าสำเร็จ";
    } else {
        echo "ไม่สามารถเพิ่มรายการสินค้าได้: " . $conn->error;
    }
    
    $stmt->close();
} else {
    die("ID is not set.");
}

$conn->close();

// เปลี่ยนเส้นทางกลับไปที่หน้าหลักหลังจากลบ
header("Location: manage_product.php");
exit();
?>
