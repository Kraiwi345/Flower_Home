<?php session_start();?>
<?php include('header.html'); ?>

<main id="main">

  <!-- ======= Gallery Section ======= -->
  <section id="flower_25" >
    <div class="container">
      <div class="section-title">
        <h2 style="font-family: Kanit, sans-serif;">ดอกไม้ช่อ</h2>
        <hr>
      </div>

      <div class="row">
        <?php
        // Include the database connection file
        include("conn.php");

        // Define the SQL query to fetch all records
        $sql = "SELECT id, name, description, prev_price, current_price, img_path FROM tbproduct WHERE type_shop = 'flower_25'";

        // Prepare and execute the query
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result && $result->num_rows > 0) {
            // Fetch and display each row of data
            while ($row = $result->fetch_assoc()) {
                $id = htmlspecialchars($row["id"]); // Get product id
                $fileName = htmlspecialchars($row["img_path"]); // Corrected to match the column name
                $filePath = 'uploaded_files/' . $fileName; // Adjust the path if necessary
                $name = htmlspecialchars($row["name"]);
                $description = htmlspecialchars($row["description"]); // Corrected to match the column name
                $prevPrice = (float) htmlspecialchars($row["prev_price"]); // Cast to float for accurate calculations
                $currentPrice = (float) htmlspecialchars($row["current_price"]); // Cast to float for accurate calculations

                // Initialize variables for discount calculation
                $formattedCurrentPrice = number_format($currentPrice, 2);
                $formattedPrevPrice = number_format($prevPrice, 2);

                if ($currentPrice > 0) {
                    if ($prevPrice > 0) {
                        $discount = $prevPrice - $currentPrice;
                        if ($discount > 0) {
                            $formattedDiscount = "ส่วนลด " . number_format($discount, 2) . " บ.";
                            $priceDisplay = '<p><i style="color:red;" class="fa-solid fa-baht-sign">' . $formattedCurrentPrice . '</i>&nbsp;&nbsp; <del><i class="fa-solid fa-baht-sign"></i>' . $formattedPrevPrice . '</del></p>';
                        } else {
                            $formattedDiscount = "ไม่มีส่วนลด";
                            $priceDisplay = '<p><i style="color:red;" class="fa-solid fa-baht-sign">' . $formattedCurrentPrice . '</i>&nbsp;&nbsp; <i class="fa-solid fa-baht-sign"></i>' . $formattedPrevPrice . '</p>';
                        }
                      }
                }

                // Display the HTML
                echo '<div class="col-lg-4 col-md-6 mb-4">'; // Added margin-bottom for spacing
                echo '    <div class="member">';
                echo '        <div class="member_edit">';
                echo '            <div><img src="' . $filePath . '" class="img-fluid" alt="Product Image"></div>';
                echo '            <div class="row">';
                echo '                <div class="col-6 text-center">';
                echo '                    <h5 style="color:white;background-color:red;border-radius:15px;padding:5px;margin-top:5%;">' . $formattedDiscount . '</h5>';
                echo '                </div>';
                echo '            </div>';
                echo '            <div class="row">';
                echo '                <div class="col-12">';
                echo '                    <div class="text_left">';
                echo '                        <p>' . $name . '</p>';
                echo '                        <p>' . $description . '</p>';
                echo '                        ' . $priceDisplay;
                echo '                    </div>';
                echo '                </div>';
                echo '            <div class="row">';
                echo '                <div class="col-12 text_left">';
                echo '                    <a href="cart.php?action=add&id=' . $id . '" class="btn btn-primary">  <i class="fas fa-shopping-cart"></i> สั่งซื้อ</a>'; // Link to cart page with product ID
                echo '                </div>';
                echo '                </div>';
                echo '            </div>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo '<p>No products found.</p>';
        }

        // Close the connection
        $conn->close();
        ?>
      </div>
    </div>
  </section><!-- End Chefs Section -->

  <!-- ======= Contact Section ======= -->

</main><!-- End #main -->

<?php include('footer.html'); ?>
