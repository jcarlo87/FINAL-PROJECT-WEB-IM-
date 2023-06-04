<?php
include '../database.php';

session_start();
if (!isset($_SESSION["customer"])) {
    header("Location: ../index.php");
}

$customer_id = $_SESSION['customer_id'];

//add product to the cart of current customer 
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];    
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
 
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM carts WHERE product_name = '$product_name' AND customer_id = '$customer_id'") or die('Error: ' . mysqli_error($conn)); 
 
    if (mysqli_num_rows($check_cart_numbers) > 0) {
        echo "<script>alert('already added to cart!')</script>";
    } else {
       mysqli_query($conn, "INSERT INTO carts(customer_id, product_id, product_name, price, cart_quantity, image) 
                            VALUES('$customer_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") 
                            or die('Error: ' . mysqli_error($conn)); 
       echo "<script>alert('product added to cart!')</script>";
    }
 
 }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
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

<body>
    <!-- NAVIGATION -->
    <?php include 'customer_nav.php' ?>
<!-- temporary -->
<a href="../logout.php" class="logout-btn">logout</a>
 

    <!-- CATEGORIES -->
    <section id="category-section">
        <div class="category-btn-div">
            <div class="category-btn">
                <button data-category="all">All</button>
            </div>
            <?php
            $select_categories = mysqli_query($conn, "SELECT * FROM categories") or die('query failed');
            if (mysqli_num_rows($select_categories) > 0) {
                while ($row = mysqli_fetch_assoc($select_categories)) {
                    ?>
                    <!-- add all category to the client side category buttons -->
                    <div class="category-btn">
                        <button class="cat-btn" data-category="<?php echo $row['category_name']; ?>">
                            <?php echo $row['category_name']; ?>
                        </button>
                    </div>
                    </form>
                    <?php
                }
            }
            ?>
        </div>

        <div class="search-bar-div">
            <div class="search-bar-wrapper">
                <input class="search-bar" type="text" placeholder="Jacket" />
                <span class="search-icon material-symbols-outlined">search</span>
            </div>
        </div>
    </section>

    <!-- PRODUCTS SECTION -->
    <section id="product-section">
        <?php
        $sql = "SELECT * FROM products JOIN categories ON products.category_id = categories.category_id";
        $select_products = mysqli_query($conn, $sql) or die('query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <form action="" method="post" class="product-box-container"
                    data-category="<?php echo $fetch_products['category_name']; ?>">
                    <!-- product image -->
                    <div class="image-wrapper">
                        <img class="product-image" src="../admin/uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                    </div>
                    <!-- product category label -->
                    <span class="category">
                        <?php echo $fetch_products['category_name']; ?>
                    </span>
                    <!-- product name -->
                    <div class="product-name-wrapper">
                        <span class="product-name">
                            <?php echo $fetch_products['product_name']; ?>
                        </span>
                    </div>
                    <!-- product price -->
                    <div class="price-cart-wrapper">
                        <span class="price">₱
                            <?php echo $fetch_products['price']; ?>
                        </span>
                        <div class="add-to-cart-wrapper">
                            <!-- quantity: it is hidden while not fully viewed -->
                            <input type="hidden" min="1" name="product_quantity" id="product_quantity" value="1" class="qty">
                            <!-- product info -->
                            <input type="hidden" name="product_id"  id="product_id" value="<?php echo $fetch_products['product_id']; ?>">
                            <input type="hidden" name="product_name"  id="product_name" value="<?php echo $fetch_products['product_name']; ?>">
                            <input type="hidden" name="product_price" id="product_price" value="<?php echo $fetch_products['price']; ?>">
                            <input type="hidden" name="product_image" id="product_image" value="<?php echo $fetch_products['image']; ?>">
                            <!-- add to cart btn-->
                            <button type="submit" name="add_to_cart" id="add_to_cart"><span
                                    class="add-to-cart material-symbols-outlined">shopping_cart</span></button>
                        </div>
                    </div>
                </form>
                <?php
            }
        } else {
            echo '<p class="empty">no products added yet!</p>';
        }
        ?>
    </section>

    <!-- FOOTER -->
    <footer></footer>

    <script src="../js/script.js"></script>
</body>

</html>



<!--
<center>
            <h1>Welcome to Dashboard</h1>
            <a href="admin/product.php">admin</a>
            <a href="../logout.php" class="btn btn-warning">Logout</a>
        </center>
-->