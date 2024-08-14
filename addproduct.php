
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Page</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>

<?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include("conn.php");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการอัพโหลดไฟล์หรือไม่
if (isset($_FILES['img_path']) && $_FILES['img_path']['error'] == UPLOAD_ERR_OK) {
  $fileTmpPath = $_FILES['img_path']['tmp_name'];
  $fileName = $_FILES['img_path']['name'];
  $fileSize = $_FILES['img_path']['size'];
  $fileType = $_FILES['img_path']['type'];
  $fileNameCmps = explode(".", $fileName);
  $fileExtension = strtolower(end($fileNameCmps));
  
  // กำหนดที่เก็บไฟล์
  $uploadFileDir = './uploaded_files/';
  $dest_file_path = $uploadFileDir . $fileName;

  // ตรวจสอบประเภทไฟล์และขนาดไฟล์ที่อนุญาต
  $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
  if (in_array($fileExtension, $allowedfileExtensions) && $fileSize < 5000000) { // 5MB limit
      if (move_uploaded_file($fileTmpPath, $dest_file_path)) {
          echo 'File is successfully uploaded.<br>';
      } else {
          echo 'Error occurred while moving the uploaded file.<br>';
      }
  } else {
      echo 'Upload failed. Allowed file types: jpg, jpeg, png, gif. Max file size: 5MB.<br>';
      $dest_file_path = ''; // Set to empty if file upload failed
  }
} else {
  echo 'No file uploaded or there was an upload error.<br>';
  $dest_file_path = ''; // Set to empty if file upload failed
}

// รับค่าจากฟอร์ม
$name = $_POST['name'];
$description = $_POST['description'];
$prev_price = $_POST['prev_price'];
$current_price = $_POST['current_price'];
$type_shop = $_POST['type_shop'];


// สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูลลงในฐานข้อมูล
$sql = "INSERT INTO tbproduct (name, img_path, description, prev_price, current_price, type_shop) VALUES (?, ?, ?, ?, ?, ?)";

// เตรียมคำสั่ง
$stmt = $conn->prepare($sql);
if ($stmt === false) {
  die('Prepare failed: ' . $conn->error);
}

// ผูกค่ากับคำสั่ง
$filePath = basename($dest_file_path); // ใช้ชื่อไฟล์สำหรับบันทึกในฐานข้อมูล
$stmt->bind_param('ssssss', $name, $filePath, $description, $prev_price, $current_price, $type_shop);

// ดำเนินการคำสั่ง
if ($stmt->execute()) {
  echo 'Data successfully inserted.';
  echo '<script>window.location.href = "manage_product.php";</script>';
} else {
  echo 'Error inserting data: ' . $stmt->error;
  echo '<div class="form-group">';
  echo '<div class="col-sm-12">';
  echo '<a href="manage_product.php" class="btn btn-danger">หน้าแรก</a>';
  echo '</div>';
  echo '</div>';
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>



<br><br>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>