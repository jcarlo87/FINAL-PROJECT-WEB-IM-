<?php
include '../database.php';

session_start();
if (!isset($_SESSION["customer"])) {
    header("Location: ../index.php");
}

$customer_id = $_SESSION['customer_id'];


/* delete product cart to the database */
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_query = "DELETE FROM carts WHERE product_id = '$delete_id' AND customer_id = '$customer_id'";
    $select_query = mysqli_query($conn, "SELECT * FROM carts WHERE customer_id = '$customer_id'");

    mysqli_query($conn, $delete_query) or die('Error: ' . mysqli_error($conn));
    echo "<script>
            // Check if the URL contains a delete parameter
            if (window.location.href.includes('?delete=')) {
            // Remove the delete parameter from the URL
            const updatedURL = window.location.href.split('?')[0];
            history.replaceState(null, null, updatedURL);
            }
            //display a message
            alert('product remove successfully')
         </script>";

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/customer.css" />
    <link rel="stylesheet" href="../css/general.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600;800&family=Montserrat:ital,wght@0,300;0,500;1,400&display=swap"
        rel="stylesheet">
</head>
</head>

<body>
    <!-- NAVIGATION -->
    <?php include 'customer_nav.php' ?>
    <!-- temporary -->
    <a href="../logout.php" class="logout-btn">logout</a>

    <main id="main-cart-wrapper">
    <h1>My Cart</h1>
        <table class="cart">
            <thead>
                <tr>
                    <td class="checkbox-cell"><input type="checkbox" /></td>
                    <td class="image-cell"></td>
                    <td class="product-name-cell">Product Name</td>
                    <td class="amount">Price</td>
                    <td class="quantity">Quantity</td>
                    <td class="amount">Subtotal</td>
                    <td></td>
                </tr>
            </thead>
            <tbody class="cart-product">
                <?php
                $sql = "SELECT * FROM carts
                JOIN inventory ON carts.product_id = inventory.product_id
                WHERE carts.customer_id = '$customer_id';
                ";

                $select_carts = mysqli_query($conn, $sql) or die('query failed');
                if (mysqli_num_rows($select_carts) > 0) {
                    while ($row = mysqli_fetch_assoc($select_carts)) {
                        ?>
                        <tr>
                            <td><input type="checkbox" class="product-checkbox" /></td>
                            <td>
                                <div class="product-image-wrapper">
                                    <img src="uploaded_img/<?php echo $row['image'] ?>">
                                </div>
                            <td>
                                <div class="product-details">
                                    <p class="product-name"><?php echo $row['product_name'] ?></p>
                                    <p class="product-quantity">Currently Available: <?php echo $row['quantity'] ?></p>
                                </div>
                            </td>
                            <td class="amount"><?php echo $row['price'] ?></td>
                            <td class="quantity">
                                <input type="number" value="<?php echo $row['cart_quantity'] ?>" min="1" class="quantity-field">
                            </td>
                            <td class="amount">₱104.00</td>
                            <td class="remove">
                            <a href="cart.php?delete=<?php echo $row['product_id']; ?>"
                                        onclick="return confirm('remove this product?');">
                                        <span class="remove-btn">Remove</span></a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <aside>
            <div class="summary">
                <div class="summary-total-items">
                    <h1>Selected Item(s)</h1>
                </div>
                <h3>Order Summary</h3>
                <div class="order-summary">
                    <div>
                        <p>Products (2 items(s))</p>
                        <p>Subtotal</p>
                    </div>
                    <div>
                        <p>($)</p>
                        <p>3150.75</p>
                    </div>
                </div>
                <div class="total-payable">
                    <div>
                        <h3>Total Payable</h3>
                    </div>
                    <div>
                        <p>3150.75</p>
                    </div>

                </div>
                <button class="check-btn">CHECKOUT</button>
            </div>

        </aside>

    </main>

</body>

</html>