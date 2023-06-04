<?php
include '../database.php';

session_start();
if (!isset($_SESSION["admin"])) {
    header("Location:index.php");
}

/* Add supplier to the database */
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
    $address = $_POST['supplier_address'];
    $number = $_POST['contact_number'];
    $select_table = mysqli_query($conn, "SELECT supplier_name FROM suppliers WHERE supplier_name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_table) > 0) {
        echo "<script>alert('name already added')</script>";
    } else {
        $add_supplier_query = mysqli_query($conn, "INSERT INTO suppliers(supplier_name, supplier_address, contact_number)
                                                   VALUES('$name', '$address', '$number')") or die('query failed'); //add supplier to the database
    } echo "<script>alert('supplier added successfully');</script>";
}

/* delete supplier to the database */
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    //check if selected supplier was still used in product table
    //if yes then dont allow the admin to delete that supplier
    $sql = mysqli_query($conn, "SELECT * FROM products WHERE supplier_id = '$delete_id'") or die('Error: ' . mysqli_error($conn)); 
    if (mysqli_num_rows($sql) > 0) {    
        echo "<script>confirm('!!!You cant delete this supplier because there are still products that are connected with it')</script>";  
    } else {
        mysqli_query($conn, "DELETE FROM suppliers WHERE supplier_id = '$delete_id'") or die('Error: ' . mysqli_error($conn)); //delete the image detail record in the product table 
     }  echo "<script>
                // Check if the URL contains a delete parameter
                if (window.location.href.includes('?delete=')) {
                // Remove the delete parameter from the URL
                const updatedURL = window.location.href.split('?')[0];
                history.replaceState(null, null, updatedURL);
                }
                //display a message
                alert('supplier deleted successfully')
            </script>";
}

/* update supplier to the database*/
if (isset($_POST['update_supplier'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_address = $_POST['update_address'];
    $update_number = $_POST['update_number'];

    //check if supplier still exist in the database
    $sql = mysqli_query($conn, "SELECT * FROM suppliers WHERE supplier_id = '$update_p_id'") or die('Error: ' . mysqli_error($conn));

    if(mysqli_num_rows($sql) > 0) {
        mysqli_query($conn, "UPDATE suppliers SET supplier_name = '$update_name', supplier_address = '$update_address', contact_number = '$update_number'
                         WHERE supplier_id = '$update_p_id'") or die('Error: ' . mysqli_error($conn));
    }
    echo "<script>
            // Check if the URL contains a upadte parameter
            if (window.location.href.includes('?update=')) {
            // Remove the update parameter from the URL
            const updatedURL = window.location.href.split('?')[0];
            history.replaceState(null, null, updatedURL);
            }
            alert('updated supplier details successfully!')
        </script>";
}
?>


<!-- supplier management html structure -->
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
    <title>Supplier Management</title>
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
            <li class="side-bar-btn active"><a href="supplier.php">
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

        <!-- ADD SUPPLIER FORM -->
        <section class="popup" id="addSupplierForm">
            <div class="form-header">
                <h1>Add Supplier</h1>
                <button type="button" class="close-btn" onclick="closeForm('addSupplierForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <!-- add form after clicking the btn -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label>Supplier Name</label>
                <input type="text" id="supplier_name" name="supplier_name">
                <label>Supplier Address</label>
                <input type="text" id="supplier_address" name="supplier_address">
                <label>Contact Number</label>
                <input type="text" id="contact_number" name="contact_number">
                <input type="submit" name="submit" id="submit" value="ADD" onclick="return confirm('add this supplier?');">
            </form>
        </section>


        <!-- UPDATE PRODUCT FORM -->
        <section class="popup" id="editSupplierForm">
            <div class="form-header">
                <h1>Edit Supplier Details</h1>
                <button type="button" class="close-btn" onclick="closeForm('editSupplierForm')"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <?php
            if (isset($_GET['update'])) {
                $update_id = $_GET['update'];
                $sql = mysqli_query($conn, "SELECT * FROM suppliers WHERE supplier_id = '$update_id'") or die('query failed');
                if (mysqli_num_rows($sql) > 0) {
                    while ($fetch_update = mysqli_fetch_assoc($sql)) {
                        ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="update_p_id" name="update_p_id"
                                value="<?php echo $fetch_update['supplier_id']; ?>">
                            <label>Supplier Name</label>
                            <input type="text" id="update_name" name="update_name"
                                value="<?php echo $fetch_update['supplier_name']; ?>" />
                            <label>Supplier Address</label>
                            <input type="text" id="update_address" name="update_address"
                                value="<?php echo $fetch_update['supplier_address']; ?>" />
                            <label>Contact Number</label>
                            <input type="number" id="update_number" name="update_number"
                                value="<?php echo $fetch_update['contact_number']; ?>" />

                            <input type="submit" name="update_supplier" value="UPDATE"
                                onclick="return confirm('update this supplier?');">
                        </form>
                        <?php
                    }
                }
            } else {
                echo '<script>document.querySelector("#editSupplierForm").style.display = "none";</script>';
            }
            ?>
        </section>

        <!-- display, edit, delete, product -->
        <main id="main-section-list">
            <div class="list-header">
                <h1>Supplier Details</h1>
                <button class="open-add-form-btn" onclick="openForm('addSupplierForm')">+ ADD SUPPLIER</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <td class="id-td">ID Code</td>
                        <td>Supplier Name</td>
                        <td>Supplier Address</td>
                        <td>Contact Number</td>
                        <td class="action-td">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- display list of categories in a table form -->
                    <?php
                    $select_table = mysqli_query($conn, "SELECT * FROM suppliers;") or die('query failed');
                    if (mysqli_num_rows($select_table) > 0) {

                        while ($row = mysqli_fetch_assoc($select_table)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo "#" . $row['supplier_id']; ?>
                                </td>
                                <td>
                                    <?php echo $row['supplier_name']; ?>
                                </td>
                                <td>
                                    <?php echo $row['supplier_address']; ?>
                                </td>
                                <td>
                                    <?php echo $row['contact_number']; ?>
                                </td>
                                <td class="action-col-cells">
                                    <a href="supplier.php?update=<?php echo $row['supplier_id']; ?>">
                                        <span class="material-symbols-outlined edit-btn">edit</span></a>

                                    <a href="supplier.php?delete=<?php echo $row['supplier_id']; ?>"
                                        onclick="return confirm('delete this supplier record?');">
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
            var name = document.getElementById("supplier_name").value;
            var address = document.getElementById("supplier_address").value;
            var number = document.getElementById("contact_number").value;

            if (name === "" || address === "" || number === "") {
                alert("Please fill in all fields.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>