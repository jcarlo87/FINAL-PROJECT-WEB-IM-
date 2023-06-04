<?php
include '../database.php';

session_start();
if (!isset($_SESSION["admin"])) {
    header("Location:index.php");
}

/* Add category to the database */
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $select_category_name = mysqli_query($conn, "SELECT category_name FROM categories WHERE category_name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_category_name) > 0) {
        echo "<script>alert('category name already added!')</script>";  
    } else {
        $add_category_query = mysqli_query($conn, "INSERT INTO categories(category_name) VALUES('$name')") or die('query failed'); //add category to the database
    }  echo "<script>alert('category added successfully!')</script>";  
}

/* delete category to the database */
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    //check if the selected category was still used in product table
    //if yes then dont allow the admin to delete that category
    $sql = mysqli_query($conn, "SELECT * FROM products WHERE category_id = '$delete_id'") or die('Error: ' . mysqli_error($conn)); 
    if (mysqli_num_rows($sql) > 0) {    
        echo "<script>confirm('!!!You cant delete this category because there are still products that are connected with it')</script>";  
    } else {
        mysqli_query($conn, "DELETE FROM categories WHERE category_id = '$delete_id'") or die('Error: ' . mysqli_error($conn)); //delete the image detail record in the product table 
    } echo "<script>
                // Check if the URL contains a delete parameter
                if (window.location.href.includes('?delete=')) {
                // Remove the delete parameter from the URL
                const updatedURL = window.location.href.split('?')[0];
                history.replaceState(null, null, updatedURL);
                }
                //display a message
                alert('deleted successfully!')
            </script>";  
}

/* update category to the database*/
if (isset($_POST['update_category'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    //check if category still exist in the database
    $sql = mysqli_query($conn, "SELECT * FROM categories WHERE category_id = '$update_p_id'") or die('Error: ' . mysqli_error($conn));

    if(mysqli_num_rows($sql) > 0) {
        mysqli_query($conn, "UPDATE categories SET category_name = '$update_name' WHERE category_id = '$update_p_id'") or die('Error: ' . mysqli_error($conn));
    }
    //!!error not wireking make it work
    echo "<script>
            // Check if the URL contains a upadte parameter
            if (window.location.href.includes('?update=')) {
            // Remove the update parameter from the URL
            const updatedURL = window.location.href.split('?')[0];
            history.replaceState(null, null, updatedURL);
            }
            alert('updated category successfully!')
        </script>";
}
?>


<!-- category management html structure -->
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
    <title>Category Management</title>
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
            <li class="side-bar-btn active"><a href="category.php">
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

        <!-- ADD CATEGORY FORM -->
        <section class="popup" id="addCategoryForm">
            <div class="form-header">
                <h1>Add Caregory</h1>
                <button type="button" class="close-btn" onclick="closeForm('addCategoryForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <!-- add product form after clicking the btn -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label>Category Name</label>
                <input type="text" id="category_name" name="category_name">
                <input type="submit" name="submit" id="submit" value="ADD" onclick="return confirm('add this category?')">
            </form>
        </section>


        <!-- UPDATE PRODUCT FORM -->
        <section class="popup" id="editCategoryForm">
            <div class="form-header">
                <h1>Edit Category</h1>
                <button type="button" class="close-btn" onclick="closeForm('editCategoryForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <?php
            if (isset($_GET['update'])) {
                $update_id = $_GET['update'];
                $sql = mysqli_query($conn, "SELECT * FROM categories WHERE category_id = '$update_id'") or die('query failed');
                if (mysqli_num_rows($sql) > 0) {
                    while ($fetch_update = mysqli_fetch_assoc($sql)) {
                        ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="update_p_id" name="update_p_id"
                                value="<?php echo $fetch_update['category_id']; ?>">
                            <label>Category Name</label>
                            <input type="text" id="update_name" name="update_name"
                                value="<?php echo $fetch_update['category_name']; ?>" />
                            <input type="submit" name="update_category" value="UPDATE"
                                onclick="return confirm('update this category?');">
                        </form>
                        <?php
                    }
                }
            } else {
                echo '<script>document.querySelector("#editCategoryForm").style.display = "none";</script>';
            }
            ?>
        </section>

        <!-- display, edit, delete, product -->
        <main id="main-section-list">
            <div class="list-header">
                <h1>Category List</h1>
                <button class="open-add-form-btn " onclick="openForm('addCategoryForm')">+ ADD CATEGORY</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <td class="id-td">ID Code</td>
                        <td>Category Name</td>
                        <td class="action-td">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- display list of categories in a table form -->
                    <?php
                    $select_products = mysqli_query($conn, "SELECT * FROM categories;") or die('query failed');
                    if (mysqli_num_rows($select_products) > 0) {

                        while ($row = mysqli_fetch_assoc($select_products)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo "#" . $row['category_id']; ?>
                                </td>
                                <td>
                                    <?php echo $row['category_name']; ?>
                                </td>
                                <td class="action-col-cells">
                                    <a href="category.php?update=<?php echo $row['category_id']; ?>" onclick="return confirm('update this category?')">
                                        <span class="material-symbols-outlined edit-btn">edit</span></a>


                                    <a href="category.php?delete=<?php echo $row['category_id']; ?>"
                                        onclick="return confirm('delete this category?');">
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

    <script>
        //validate add from 
        function validateForm() {
            var name = document.getElementById("category_name").value;

            if (name === "") {
                alert("Please fill in all fields.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>