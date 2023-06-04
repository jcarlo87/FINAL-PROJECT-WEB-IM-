<?php
include '../database.php';

session_start();
if (!isset($_SESSION["admin"])) { //change this to admin later
    header("Location: index.php");
}

/* update product quantity*/
if (isset($_POST['update_inventory'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_quantity = $_POST['update_quantity'];

    //check if supplier still exist in the database
    $sql = mysqli_query($conn, "SELECT * FROM inventory WHERE product_id = '$update_p_id'") or die('Error: ' . mysqli_error($conn));

    if (mysqli_num_rows($sql) > 0) {
        mysqli_query($conn, "UPDATE inventory SET quantity = '$update_quantity' WHERE product_id = '$update_p_id'") or die('query failed');

    }
    echo "<script>
            // Check if the URL contains a upadte parameter
            if (window.location.href.includes('?update=')) {
            // Remove the update parameter from the URL
            const updatedURL = window.location.href.split('?')[0];
            history.replaceState(null, null, updatedURL);
            }
            alert('updated inventory quantity successfully!')
        </script>";
}
?>


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
    <title>Inventory Management</title>
</head>

<body id="admin-panel-body">
    <div id="side-section">
        <div class="logo-wrapper"><span class="material-symbols-outlined logo">barcode_reader</span>URBAN-THREAD</div>
        <ul>
            <li class="side-bar-btn"><a href="#">
                    <span class="material-symbols-outlined">home</span>
                    Dashboard</a></li>
            <li class="side-bar-btn"><a href="product.php">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Product</a></li>
            <li class="side-bar-btn"><a href="category.php">
                    <span class="material-symbols-outlined">category</span>
                    Category</a></li>
            <li class="side-bar-btn"><a href="supplier.php">
                    <span class="material-symbols-outlined">conveyor_belt</span>
                    Supplier</a></li>
            <li class="side-bar-btn"><a href="order.php">
                    <span class="material-symbols-outlined">format_list_bulleted</span>
                    Order</a></li>
            <li class="side-bar-btn active"><a href="inventory.php">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Inventory</a></li>
        </ul>
    </div>

    <div id="main-section">
        <!-- navbar -->
        <?php include 'admin-nav.php' ?>

        <!-- Edit form for inventory product quantity -->
        <section class="popup" id="editInventoryForm">
            <div class="form-header">
                <h1>Edit Product Quantity</h1>
                <button type="button" class="close-btn" onclick="closeForm('editInventoryForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <?php
            if (isset($_GET['update'])) {
                $update_id = $_GET['update'];
                $sql = mysqli_query($conn, "SELECT * FROM inventory WHERE product_id = '$update_id'") or die('query failed');
                if (mysqli_num_rows($sql) > 0) {
                    while ($fetch_update = mysqli_fetch_assoc($sql)) {
                        ?>
                        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateNumber('update_quantity')">
                            <input type="hidden" id="update_p_id" name="update_p_id"
                                value="<?php echo $fetch_update['product_id']; ?>">
                            <label>Product Quantity</label>
                            <input type="number" id="update_quantity" name="update_quantity"
                                value="<?php echo $fetch_update['quantity']; ?>" class="box" required
                                placeholder="enter new quantity">
                            <input type="submit" name="update_inventory" value="UPDATE"
                                onclick="return confirm('update this product?');">
                        </form>
                        <?php
                    }
                }
            } else {
                echo '<script>document.querySelector("#editInventoryForm").style.display = "none";</script>';
            }
            ?>
        </section>




        <!-- display, edit, delete, product -->
        <main id="main-section-list">
            <div class="list-header">
                <h1>Inventory</h1>
            </div>
            <table>
                <thead>
                    <tr>
                        <td class="id-td">ID Code</td>
                        <td>Product Name</td>
                        <td>Quantity</td>
                        <td class="action-td">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- display list of products in a table form -->
                    <?php
                    $sql = "
                        SELECT products.product_id, products.product_name, inventory.quantity
                        FROM products
                        INNER JOIN inventory 
                        ON products.product_id = inventory.product_id;
                    ";
                    $select_products_and_inventory = mysqli_query($conn, $sql) or die('query failed');
                    if (mysqli_num_rows($select_products_and_inventory) > 0) {
                        while ($row = mysqli_fetch_assoc($select_products_and_inventory)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo "#" . $row['product_id']; ?>
                                </td>
                                <td>
                                    <?php echo $row['product_name']; ?>
                                </td>
                                <td>
                                    <?php echo $row['quantity'] ?>
                                </td>
                                <td class="action-col-cells">
                                    <a href="inventory.php?update=<?php echo $row['product_id']; ?>">
                                        <span class="material-symbols-outlined edit-btn">edit</span></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

</body>

</html>