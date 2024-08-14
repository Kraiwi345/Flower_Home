<?php 
include('header-admin.php'); 
include('conn.php');

$message = ''; // ตัวแปรสำหรับเก็บข้อความสถานะ

// ตรวจสอบการส่งฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $fname = $_POST['fname'];
    $username = $_POST['username'];
    $user_type = $_POST['user_type'];
    $password = $_POST['password'];

    // เช็คการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ตรวจสอบว่า username ซ้ำหรือไม่
    $sql_check = "SELECT id FROM users WHERE username=?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว กรุณาเลือกชื่อผู้ใช้ใหม่";
    } else {
        // เข้ารหัสรหัสผ่าน
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // สร้างคำสั่ง SQL สำหรับการเพิ่มข้อมูล
        $sql_insert = "INSERT INTO users (fname, username, user_type, password) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssss", $fname, $username, $user_type, $password_hash);

        // Execute และตรวจสอบการสำเร็จ
        if ($stmt_insert->execute()) {
            $message = "เพิ่มข้อมูลผู้ใช้สำเร็จ!";
        } else {
            $message = "เกิดข้อผิดพลาด: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
}

// ดึงข้อมูลผู้ใช้สำหรับตาราง
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $conn->error);
}
?>
        <div class="container container-edit-new">
    <title>เพิ่มข้อมูลผู้ใช้</title>

    <h1>เพิ่มข้อมูลผู้ใช้</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="fname">ชื่อ:</label>
            <input type="text" id="fname" name="fname" required>
        </div>

        <div class="form-group">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="user_type">ประเภทผู้ใช้:</label>
            <select id="user_type" name="user_type" required>
                <option value="customer">ลูกค้า</option>
                <option value="owner">เจ้าของร้าน</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <input type="submit" value="เพิ่มข้อมูล">
    </form>
    </div>

    <hr>

    <section id="user">
        <div class="container text-center container-edit ">
            <?php
            if ($result->num_rows > 0) {
                echo "<h1>จัดการข้อมูลผู้ใช้</h1>";
                echo "<table border='1'>";
                echo "<tr><th>ชื่อ</th><th>ชื่อผู้ใช้</th><th>ประเภทผู้ใช้</th><th>วันเวลาเข้าสู่ระบบล่าสุด</th><th>จัดการ</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['last_login']) . "</td>";
                    echo "<td>
                            <a href='edit_user.php?id=" . htmlspecialchars($row['id']) . "'><i class='fas fa-edit' style='font-size:20px;color:blue'></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a href='delete_user.php?id=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('คุณแน่ใจว่าต้องการลบผู้ใช้รายนี้?');\"><i class='fas fa-times-circle' style='font-size:20px;color:red'></i></a>
                        </td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "ไม่มีข้อมูลผู้ใช้";
            }

            $conn->close();
            ?>
        </div>
    </section>

    <?php include('footer.html'); ?>
</body>
</html>
