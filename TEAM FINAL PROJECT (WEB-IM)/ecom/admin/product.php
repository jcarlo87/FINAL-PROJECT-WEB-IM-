<?php
include '../database.php';

session_start();
if (!isset($_SESSION["admin"])) { //change this to admin later
    header("Location:index.php");
}

/* Add product to the database */
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['product_price'];
    $description = $_POST['product_description'];
    $category = $_POST['product_category'];
    $supplier = $_POST['product_supplier'];

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT product_name FROM products WHERE product_name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        echo "<script>alert('product name already added')</script>";
    } else {
        $add_product_query = mysqli_query($conn, "INSERT INTO products(product_name, category_id, supplier_id, price, description, image) 
                                                  VALUES('$name', '$category', '$supplier', '$price', '$description', '$image')") or die('query failed'); //add product to the database

        if ($add_product_query) {
            if ($image_size > 2000000) { //check if the image size exceed to the limit cap
                echo "<script>alert('image size is too large!')</script>";
            } else { //move the uploaded product image to the designated folder
                move_uploaded_file($image_tmp_name, $image_folder);
                echo "<script>alert('product added successfully!')</script>";
            }
        } else {
            echo "<script>alert('product could not be added!')</script>";
        }
    }
}

/* delete product to the database */
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_query = "DELETE FROM products WHERE product_id = '$delete_id'";
    $delete_image_query = mysqli_query($conn, "SELECT image FROM products WHERE product_id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);

    if (mysqli_num_rows($delete_image_query) > 0) {
        //delete th image that is placed inside the uploaded_img if the image still exist in the directory
        if (file_exists('uploaded_img/' . $fetch_delete_image['image'])) {
            unlink('uploaded_img/' . $fetch_delete_image['image']);
        }
        mysqli_query($conn, $delete_query) or die('Error: ' . mysqli_error($conn)); //delete the image detail record in the product table 
    }
    echo "<script>
            // Check if the URL contains a delete parameter
            if (window.location.href.includes('?delete=')) {
            // Remove the delete parameter from the URL
            const updatedURL = window.location.href.split('?')[0];
            history.replaceState(null, null, updatedURL);
            }
            //display a message
            alert('product deleted successfully')
         </script>";

}

/* update product to the database*/
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_product_description = $_POST['update_product_description'];
    $update_product_category = $_POST['update_product_category'];
    $update_product_supplier = $_POST['update_product_supplier'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn, "UPDATE products SET product_name = '$update_name', category_id = '$update_product_category' , 
                                supplier_id = '$update_product_supplier', description = '$update_product_description', 
                                price = '$update_price' WHERE product_id = '$update_p_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            echo "<script>alert('image file size is too large!')</script>";
        } else {
            mysqli_query($conn, "UPDATE products SET image = '$update_image' WHERE product_id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image);
        }
    }
    echo "<script>
            // Check if the URL contains a upadte parameter
            if (window.location.href.includes('?update=')) {
            // Remove the update parameter from the URL
            const updatedURL = window.location.href.split('?')[0];
            history.replaceState(null, null, updatedURL);
            }
            alert('product updated successfully!')
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
    <title>Product Management</title>
</head>

<body id="admin-panel-body">
    <div id="side-section">
        <div class="logo-wrapper"><span class="material-symbols-outlined logo">barcode_reader</span>URBAN-THREAD</div>
        <ul>
            <li class="side-bar-btn"><a href="#">
                    <span class="material-symbols-outlined">home</span>
                    Dashboard</a></li>
            <li class="side-bar-btn active"><a href="product.php">
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
            <li class="side-bar-btn"><a href="inventory.php">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Inventory</a></li>
        </ul>
    </div>

    <div id="main-section">
        <!-- navbar -->
        <?php include 'admin-nav.php' ?>

        <!-- ADD PRODUCT FORM -->
        <section class="popup" id="addProductForm">
            <div class="form-header">
                <h1>Add Product</h1>
                <button type="button" class="close-btn" onclick="closeForm('addProductForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <!-- add product form after clicking the btn -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label>Product title</label>
                <input type="text" id="product_name" name="product_name">
                <label>Description</label>
                <textarea id="product_description" name="product_description" rows="4" cols="50"></textarea>
                <label>Image</label>
                <input type="file" name="image" id="image" accept="image/jpg, image/jpeg, image/png">
                <label>Category</label>
                <select id="product_category" name="product_category">
                    <!-- display all category record from the database -->
                    <?php
                    $get_category = "select * from categories";
                    $run_category = mysqli_query($conn, $get_category);
                    while ($row_category = mysqli_fetch_array($run_category)) {
                        $category_id = $row_category['category_id'];
                        $category_name = $row_category['category_name'];

                        echo "<option value='$category_id'>$category_name</option>";
                    }
                    ?>
                </select>

                <label>Supplier</label>
                <select id="product_supplier" name="product_supplier">
                    <!-- display all supplier record from the database -->
                    <?php
                    $get_supplier = "select * from suppliers";
                    $run_supplier = mysqli_query($conn, $get_supplier);
                    while ($row_supplier = mysqli_fetch_array($run_supplier)) {
                        $supplier_id = $row_supplier['supplier_id'];
                        $supplier_name = $row_supplier['supplier_name'];

                        echo "<option value='$supplier_id'>$supplier_name</option>";
                    }
                    ?>
                </select>

                <label>Price</label>
                <input type="number" id="product_price" name="product_price">
                <input type="submit" name="submit" id="submit" value="ADD"
                    onclick="return confirm('add this product?');">
            </form>
        </section>


        <!-- UPDATE PRODUCT FORM -->
        <section class="popup" id="editProductForm">
            <div class="form-header">
                <h1>Edit Product</h1>
                <button type="button" class="close-btn" onclick="closeForm('editProductForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <?php
            if (isset($_GET['update'])) {
                $update_id = $_GET['update'];
                $sql = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$update_id'") or die('query failed');
                if (mysqli_num_rows($sql) > 0) {
                    while ($fetch_update = mysqli_fetch_assoc($sql)) {
                        ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="update_p_id" name="update_p_id"
                                value="<?php echo $fetch_update['product_id']; ?>">
                            <input type="hidden" name="update_old_image" id="update_old_image"
                                value="<?php echo $fetch_update['image']; ?>">
                            <label>Product title</label>
                            <input type="text" id="update_name" name="update_name"
                                value="<?php echo $fetch_update['product_name']; ?>" required placeholder="enter new product name">

                            <label>Description</label>
                            <textarea id="update_product_description" name="update_product_description" rows="4"
                                cols="50"><?php echo $fetch_update['description']; ?></textarea>
                            <label>Image</label>
                            <input type="file" name="update_image" id="update_image" accept="image/jpg, image/jpeg, image/png">
                            <label>Category</label>
                            <select id="update_product_category" name="update_product_category"
                                value="<?php echo $fetch_update['category_id']; ?>">
                                <!-- display all category record from the database -->
                                <?php
                                $get_category = "select * from categories";
                                $run_category = mysqli_query($conn, $get_category);
                                while ($row_category = mysqli_fetch_array($run_category)) {
                                    $category_id = $row_category['category_id'];
                                    $category_name = $row_category['category_name'];

                                    echo "<option value='$category_id'>$category_name</option>";
                                }
                                ?>
                            </select>

                            <label>Supplier</label>
                            <select id="update_product_supplier" name="update_product_supplier"
                                value="<?php echo $fetch_update['supplier_id']; ?>">
                                <!-- display all supplier record from the database -->
                                <?php
                                $get_supplier = "select * from suppliers";
                                $run_supplier = mysqli_query($conn, $get_supplier);
                                while ($row_supplier = mysqli_fetch_array($run_supplier)) {
                                    $supplier_id = $row_supplier['supplier_id'];
                                    $supplier_name = $row_supplier['supplier_name'];

                                    echo "<option value='$supplier_id'>$supplier_name</option>";
                                }
                                ?>
                            </select>
                            <label>Price</label>
                            <input type="number" id="update_price" name="update_price" value="<?php echo $fetch_update['price']; ?>"
                                min="0" required placeholder="enter product price">
                            <input type="submit" name="update_product" value="UPDATE"
                                onclick="return confirm('update this product?');">
                        </form>
                        <?php
                    }
                }
            } else {
                echo '<script>document.querySelector("#editProductForm").style.display = "none";</script>';
            }
            ?>
        </section>

        <!-- display, edit, delete, product -->
        <main id="main-section-list">
            <div class="list-header">
                <h1>Product List</h1>
                <button class="open-add-form-btn" onclick="openForm('addProductForm')">+ ADD PRODUCT</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <td class="id-td">ID Code</td>
                        <td>Product Name</td>
                        <td>Category</td>
                        <td>Supplier</td>
                        <td>Price</td>
                        <td>Status</td>
                        <td class="action-td">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- display list of products in a table form -->
                    <?php
                    $sql = "SELECT products.*, inventory.quantity, categories.category_name, suppliers.supplier_name
                                                FROM inventory
                                                JOIN products
                                                ON products.product_id = inventory.product_id
                                                JOIN categories
                                                ON products.category_id = categories.category_id
                                                JOIN suppliers
                                                ON products.supplier_id = suppliers.supplier_id;";

                    $select_table = mysqli_query($conn, $sql) or die('query failed');
                    if (mysqli_num_rows($select_table) > 0) {

                        while ($row = mysqli_fetch_assoc($select_table)) {
                            //check if a product still have a stock
                            $status = "";
                            if ($row['quantity'] > 0) {
                                $status = "<span class='quantity-status'>Stock</span>";
                            } else {
                                $status = "<span class='quantity-status red'>Sold out</span>";
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo "#" . $row['product_id']; ?>
                                </td>
                                <td class="product-name-cell-container">
                                    <div class="product-img-list"><img src="uploaded_img/<?php echo $row['image']; ?>"></div>
                                    <?php echo $row['product_name']; ?>
                                </td>
                                <td>
                                    <?php echo $row['category_name']; ?>
                                </td>
                                <td>
                                    <?php echo $row['supplier_name']; ?>
                                </td>
                                <td>
                                    <?php echo $row['price']; ?>
                                </td>
                                <td>
                                    <?php echo $status ?>
                                </td>
                                <td class="action-col-cells">
                                    <a href="product.php?update=<?php echo $row['product_id']; ?>"
                                        onclick="return confirm('update this product?');">
                                        <span class="material-symbols-outlined edit-btn">edit</span></a>


                                    <a href="product.php?delete=<?php echo $row['product_id']; ?>"
                                        onclick="return confirm('delete this product?');">
                                        <span class="material-symbols-outlined delete-btn">delete</span></a>
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
    <script src="../js/script.js"></script>
    <script>
</body >

</html >