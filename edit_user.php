<?php include('header-admin.php'); 

include 'conn.php';

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit();
}

?>

<div class="container container-edit-new">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <h1>แก้ไขข้อมูลผู้ใช้</h1>
    <form action="update_user.php" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        
        <label for="fname">ชื่อ:</label>
        <input type="text" id="fname" name="fname" value="<?php echo $user['fname']; ?>" required><br><br>

        <label for="username">ชื่อผู้ใช้:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required><br><br>

        <label for="user_type">ประเภทผู้ใช้:</label>
        <select id="user_type" name="user_type" required>
            <option value="customer" <?php echo ($user['user_type'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
            <option value="owner" <?php echo ($user['user_type'] == 'owner') ? 'selected' : ''; ?>>Owner</option>
        </select><br><br>

        <label for="last_login">วันเวลาเข้าสู่ระบบล่าสุด:</label>
        <input  type="text" id="last_login" name="last_login" value="<?php echo $user['last_login']; ?>" disabled><br><br>

        <label for="password">รหัสผ่านใหม่ (กรอกเพื่อเปลี่ยนแปลง):</label>
        <input type="password" id="password" name="password" placeholder="ไม่เปลี่ยน ปล่อยว่าง"><br><br>

        <input type="submit" value="อัพเดตข้อมูล">
    </form>
</div>

<?php
$stmt->close();
$conn->close();
?>
<?php include('footer.html'); ?> 