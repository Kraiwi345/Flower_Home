<?php include ('header.html'); ?>

<br>
<main id="main">

  <div class="container" ;>
    <div class="row">
      <div class="col-3"></div>
      <div class="col-6">
        <h2>เพิ่มสินค้า</h2>
        <form action="addproduct.php" method="post" enctype="multipart/form-data" class="login-container">
          <label style="margin: 0 0 0 11px;" for="product_id">รหัสสินค้า</label>
          <p><input id="product_id" type="text" name="product_id" value="" required="required"></p>
          <label style="margin: 0 0 0 11px;" for="product_id">ชื่อสินค้า</label>
          <p><input id="product_name" type="text" name="product_name" value="" required="required"></p>
          <label style="margin: 0 0 0 11px;" for="product_id">ราคาสินค้า</label>
          <p><input id="product_price" type="number" name="product_price" value="" required="required"></p>
          <label style="margin: 0 0 0 11px;" for="product_id">รูปภาพสอนค้า</label>
          <p> <input id="product_photo" type="file" name="product_photo" value="" required="required"></p>
          <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
          <button type="Reset" class="btn btn-success">ยกเลิก</button>
        </form>
      </div>
      <div class="col-3"></div>
    </div>
</main>
<br>
<?php include ('footer.html'); ?>