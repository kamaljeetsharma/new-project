<?php
@include 'config.php';

if(isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'upload_image/' . $product_image;

    if(empty($product_name) || empty($product_price) || empty($product_image)){
        $message[] = 'Please fill out all fields';
    } else {
        $insert = "INSERT INTO products(name, price, image) VALUES('$product_name', '$product_price', '$product_image')";
        $upload = mysqli_query($conn, $insert);
        if($upload){
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            $message[] = 'New product added successfully';
        } else {
            $message[] = 'Could not add the product';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="admin-product-form-container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <h3>Add a new product</h3>
                <input type="text" placeholder="Enter product name" name="product_name" class="box">
                <input type="number" placeholder="Enter product price" name="product_price" class="box">
                <input type="file" accept="image/png, image/jpg" name="product_image" class="box">
                <input type="submit" class="btn" name="add_product" value="Add Product">
            </form>
        </div>
        <?php
        if(isset($message)){
            foreach($message as $msg){
                echo '<p class="message">'.$msg.'</p>';
            }
        }
        ?>
    </div>
</body>
</html>
