<?php include('header-admin.php'); 
    include ('conn.php');
?> 

<section id="product">
 <div class="container text-center ">
        <h1>เพิ่มข้อมูลสินค้า</h1>
        <form action="addproduct.php" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <label for="name" class="col-sm-3 control-label">ชื่อสินค้า</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="file" class="col-sm-3 control-label">รูปภาพ</label>
                    <div class="col-sm-9">
                        <input type="file" name="img_path" id="file" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="description" class="col-sm-3 control-label">รายละเอียด</label>
                    <div class="col-sm-9">
                        <input type="text" name="description" id="description" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="prev_price" class="col-sm-3 control-label">ราคาเดิม</label>
                    <div class="col-sm-9">
                        <input type="number" name="prev_price" id="prev_price" class="form-control" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="current_price" class="col-sm-3 control-label">ราคาโปรโมชั่น</label>
                    <div class="col-sm-9">
                        <input type="number" name="current_price" id="current_price" class="form-control" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="type_shop" class="col-sm-3 control-label">ประเภทสินค้า</label>
                    <div class="col-sm-9">
                        <select name="type_shop" id="type_shop" class="form-control" required>
                            <option value="flower_25">ดอกไม้ 25 บาท</option>
                            <option value="flower_50">ดอกไม้ 50 บาท</option>
                            <option value="flower_100">ดอกไม้ 100 บาท</option>
                            <option value="flower_luxury">ดอกไม้หรูหรา</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
     
                    <div class="col-6">
                        <input type="submit" name="btn_insert" class="btn btn-success" value="เพิ่ม">
                    </div>
                    <div class="col-6">
                        <input type="reset" name="btn_insert" class="btn btn-success" value="ยกเลิก">
                    </div>

            </div>
        </form>

 
        <hr>
    <!-- table edit,delete,view -->
    <?php
include ('conn.php');

// จำนวนรายการต่อหน้า
$items_per_page = 3;

// รับหน้าปัจจุบันจาก query string
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการค้นหา type_shop หรือไม่
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// ดึงข้อมูลจำนวนรวมทั้งหมด
$sql_count = "SELECT COUNT(*) as total FROM tbproduct";
if ($search !== '') {
    $sql_count .= " WHERE type_shop LIKE ?";
}
$stmt_count = $conn->prepare($sql_count);
if ($search !== '') {
    $search_param = "%$search%";
    $stmt_count->bind_param("s", $search_param);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_items = $row_count['total'];
$total_pages = ceil($total_items / $items_per_page);

// ดึงข้อมูลจากตาราง tbproduct ตาม type_shop ที่ค้นหา
$sql = "SELECT id, name, description, prev_price, current_price, img_path, type_shop, date_created, date_updated FROM tbproduct";
if ($search !== '') {
    $sql .= " WHERE type_shop LIKE ?";
}
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($search !== '') {
    $stmt->bind_param("sii", $search_param, $items_per_page, $offset);
} else {
    $stmt->bind_param("ii", $items_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
    }
    img {
        max-width: 100px; /* ปรับขนาดรูปภาพให้เหมาะสม */
        height: auto;
    }
    .pagination {
        margin-top: 20px;
    }
    .pagination a {
        padding: 8px 16px;
        margin: 0 4px;
        text-decoration: none;
        color: #007bff;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .pagination a:hover {
        background-color: #f1f1f1;
    }
</style>

<h2>รายการสินค้า</h2>

<!-- ฟอร์มค้นหา -->
<form action="manage_product.php" method="POST">
    <label for="search">ค้นหา ประเภทสินค้า :</label>
    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
    <div class="row">
        <div class="col-12">
            <input type="submit" value="ค้นหา">
        </div>
      
    </div>

</form>
<br>
<table>
    <tr>
        <th>ชื่อสินค้า</th>
        <th>รูปภาพ</th>
        <th>รายละเอียด</th>
        <th>ราคาเดิม</th>
        <th>ราคาโปรโมชั่น</th>
        <th>ประเภทสินค้า</th>
        <th>Actions</th>
    </tr>
    <?php
    // ตรวจสอบว่ามีข้อมูลที่ได้หรือไม่
    if ($result->num_rows > 0) {
        // แสดงข้อมูลในตาราง
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td><img src='uploaded_files/" . $row["img_path"] . "' alt='Product Image'></td>"; // แสดงรูปภาพ
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["prev_price"] . "</td>";
            echo "<td>" . $row["current_price"] . "</td>";
            echo "<td>" . $row["type_shop"] . "</td>";
            echo "<td>
                <a href='edit.php?id=" . $row["id"] . "'><i class='fas fa-edit' style='font-size:20px;color:blue'></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href='delete.php?id=" . $row["id"] . "' onclick=\"return confirm('คุณแน่ใจว่าต้องการลบผู้ใช้รายนี้?');\"><i class='fas fa-times-circle' style='font-size:20px;color:red'></i></a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>";
    }
    ?>
</table>
<style>
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}

.pagination a, .pagination span {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 5px;
    border: 1px solid #007bff;
    border-radius: 5px;
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
    text-align: center;
    transition: background-color 0.2s, color 0.2s;
}

.pagination a:hover {
    background-color: #0056b3;
    color: white;
}

.pagination .active {
    background-color: #007bff;
    color: white;
    border: 1px solid #007bff;
}

.pagination .disabled {
    color: #ddd;
    border-color: #ddd;
    cursor: not-allowed;
}
</style>
<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">&laquo; ย้อนกลับ</a>
    <?php else: ?>
        <span class="disabled">&laquo; ย้อนกลับ</span>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php if ($i == $page): ?>
            <span class="active"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">ถัดไป &raquo;</a>
    <?php else: ?>
        <span class="disabled">ถัดไป &raquo;</span>
    <?php endif; ?>
</div>


<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>


    </div>
</section>

<?php include('footer.html'); ?> 