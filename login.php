<?php
session_start();
include 'conn.php'; // เชื่อมต่อฐานข้อมูล

// การตรวจสอบการเข้าสู่ระบบ
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    // ป้องกัน SQL Injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // คำสั่ง SQL สำหรับตรวจสอบข้อมูลผู้ใช้
    $sql = "SELECT * FROM users WHERE username = ? AND user_type = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("การเตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $userType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่านที่ไม่เข้ารหัส
        if ($password === $row['password']) {
            // บันทึกวันที่และเวลาเข้าสู่ระบบ
            $update_sql = "UPDATE users SET last_login = NOW() WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            if ($update_stmt === false) {
                die("การเตรียมคำสั่ง SQL อัปเดตล้มเหลว: " . $conn->error);
            }
            $update_stmt->bind_param("s", $username);
            $update_stmt->execute();
            $update_stmt->close();

            // เก็บข้อมูลในเซสชัน
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $userType;
            $_SESSION['fname'] = $row['fname'];

            if($_SESSION['user_type'] = $userType == 'customer'){
                header("Location: index.php"); // เปลี่ยนเส้นทางไปยังหน้าแดชบอร์ด
            }
            if($_SESSION['user_type'] = $userType == 'owner'){
                header("Location: add.php"); // เปลี่ยนเส้นทางไปยังหน้าแดชบอร์ด
            }
            exit();
        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้า Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .login-container {
            width: 300px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>
    <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="user_type">ประเภทผู้ใช้:</label>
            <select id="user_type" name="user_type" required>
                <option value="customer">ลูกค้า</option>
                <option value="owner">เจ้าของร้าน</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="login">เข้าสู่ระบบ</button>
        </div>
    </form>
</div>

</body>
</html>
