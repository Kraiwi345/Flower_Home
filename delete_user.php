<?php
include 'conn.php';

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "ลบผู้ใช้สำเร็จ";
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}

$stmt->close();
$conn->close();

// เปลี่ยนเส้นทางกลับไปที่หน้าแสดงผู้ใช้
header("Location: manage_user.php");
exit();
?>
