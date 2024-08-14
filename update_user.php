<?php
include 'conn.php';

$id = $_POST['id'];
$fname = $_POST['fname'];
$username = $_POST['username'];
$user_type = $_POST['user_type'];
$last_login = $_POST['last_login'];
$password = $_POST['password']; // รหัสผ่านใหม่

// เตรียมคำสั่ง SQL สำหรับการอัพเดต
$sql = "UPDATE users SET fname=?, username=?, user_type=?";

if (!empty($last_login)) {
    $sql .= ", last_login=?";
}

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_BCRYPT); // เข้ารหัสรหัสผ่าน
    $sql .= ", password=?";
}

$sql .= " WHERE id=?";
$stmt = $conn->prepare($sql);

// กำหนดพารามิเตอร์
$params = [$fname, $username, $user_type];
$types = 'sss';

if (!empty($last_login)) {
    $params[] = $last_login;
    $types .= 's';
}

if (!empty($password)) {
    $params[] = $password_hash;
    $types .= 's';
}

$params[] = $id;
$types .= 'i';

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "อัพเดตข้อมูลสำเร็จ";
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}

$stmt->close();
$conn->close();

// เปลี่ยนเส้นทางกลับไปที่หน้าแสดงผู้ใช้
header("Location: manage_user.php");
exit();
?>
