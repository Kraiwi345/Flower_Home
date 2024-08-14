<?php
include('conn.php');
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามี ID ที่ต้องการอัปเดตหรือไม่
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $prev_price = $_POST['prev_price'];
    $current_price = $_POST['current_price'];
    $type_shop = $_POST['type_shop'];

    // ตรวจสอบว่าอัพโหลดไฟล์ใหม่หรือไม่
    if (!empty($_FILES['img_path']['name'])) {
        $file = $_FILES['img_path']['name'];
        $file_tmp = $_FILES['img_path']['tmp_name'];
        $file_path = "uploaded_files/" . basename($file); // ระบุเส้นทางที่ต้องการเก็บไฟล์

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่ต้องการ
        if (move_uploaded_file($file_tmp, $file_path)) {
            // ลบไฟล์เก่าหากมี
            if (!empty($_POST['oldfile']) && file_exists($_POST['oldfile'])) {
                unlink($_POST['oldfile']);
            }
        } else {
            die("Failed to upload file.");
        }
    } else {
        // ใช้ไฟล์เดิมหากไม่มีการอัปโหลดใหม่
        $file = $_POST['oldfile'];
    }

    // เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูล
    $sql = "UPDATE tbproduct SET name = ?, img_path = ?, description = ?, prev_price = ?, current_price = ?, type_shop = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $file, $description, $prev_price, $current_price, $type_shop, $id);
    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
} else {
    die("ID is not set.");
}

$conn->close();

// เปลี่ยนเส้นทางกลับไปที่หน้าหลักหลังจากการอัปเดต
header("Location: manage_product.php");
exit();
?>
