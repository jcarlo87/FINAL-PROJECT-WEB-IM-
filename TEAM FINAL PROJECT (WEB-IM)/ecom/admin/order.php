<!--
<?php
include '../database.php';

session_start();
if (!isset($_SESSION["admin"])) { //change this to admin later
    header("Location:index.php");
}

/*edit this to fit on the inventory */
/* update product to the database*/
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn, "UPDATE `products` SET product_name = '$update_name', price = '$update_price' WHERE product_id = '$update_p_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'image file size is too large';
        } else {
            mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE product_id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image);
        }
    }
    header('location:admin_products.php');
}
?>
-->

<!-- product management html structure -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/general.css" />
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="../js/script.js"></script>
    <title>Order Management</title>
</head>

<body id="admin-panel-body">
    <div id="side-section">
        <div class="logo-wrapper"><span class="material-symbols-outlined logo">barcode_reader</span>URBAN-THREAD</div>
        <ul>
            <li class="side-bar-btn"><a href="#">
                    <span class="material-symbols-outlined">home</span>
                    Dashboard</a></li>
            <li class="side-bar-bt"><a href="product.php">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Product</a></li>
            <li class="side-bar-btn"><a href="category.php">
                    <span class="material-symbols-outlined">category</span>
                    Category</a></li>
            <li class="side-bar-btn"><a href="supplier.php">
                    <span class="material-symbols-outlined">conveyor_belt</span>
                    Supplier</a></li>
            <li class="side-bar-btn active"><a href="order.php">
                    <span class="material-symbols-outlined">format_list_bulleted</span>
                    Order</a></li>
            <li class="side-bar-btn"><a href="inventory.php">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Inventory</a></li>
        </ul>
    </div>

    <div id="main-section">
        <!-- add admin navbar in this file -->
        <?php include 'admin-nav.php' ?>

        <!-- display, edit, delete, product -->
        <main id="main-section-list">
            <div class="list-header">
                <h1>Order Details</h1>
            </div>
            <table class="order-table">
                <thead>
                    <tr>
                        <td class="id-td">Order ID</td>
                        <td>Date of Order</td>
                        <td>Customer ID</td>
                        <td>Customer Name</td>
                        <td>Email Address</td>
                        <td>Total Ammount</td>
                        <td>Payment Method</td>
                        <td>Payment Date</td>
                        <td>Shipping Address</td>
                        <td>Shipping Date</td>
                        <td class="action-td">Status</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- display list of products in a table form -->
                </tbody>
            </table>
        </main>
    </div>

</body>

</html>