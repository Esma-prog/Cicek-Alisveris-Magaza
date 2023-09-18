<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, '1-2. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-m-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ',$cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if($cart_total == 0){
        $message[] = 'Sepetiniz boş!';
    }elseif(mysqli_num_rows($order_query) > 0){
        $message[] = 'sipariş zaten verildi!';
    }else{
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'sipariş başarıyla verildi!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ödeme</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>ödeme sırası</h3>
    <p> <a href="Anasayfa.php">Anasayfa</a> / Çıkış yap </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo 'TL'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="empty">Sepetiniz boş</p>';
        }
    ?>
    <div class="grand-total">Genel Toplam: <span>TL<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>Siparişlerin yeri</h3>

        <div class="flex">
            <div class="inputBox">
                <span>isim :</span>
                <input type="text" name="name" placeholder="adınızı giriniz" required>
            </div>
            <div class="inputBox">
                <span>Telefon no :</span>
                <input type="number" name="number" min="0" placeholder="numaranızı giriniz" required>
            </div>
            <div class="inputBox">
                <span>email :</span>
                <input type="email" name="email" placeholder="E-postanızı giriniz" required>
            </div>
            <div class="inputBox">
                <span>ödeme yöntemi :</span>
                <select name="method">
                    <option value="kapıda ödeme">kapıda ödeme</option>
                    <option value="kredi kartı">kredi kartı</option>
                  
                   
                </select>
            </div>
            <div class="inputBox">
                <span>adres satırı 01 :</span>
                <input type="text" name="flat" placeholder="Örneğin sabit no." required>
            </div>
            <div class="inputBox">
                <span>adres satırı 02:</span>
                <input type="text" name="street" placeholder="Örneğin. sokak adı">
            </div>
            <div class="inputBox">
                <span>İl :</span>
                <input type="text" name="city" placeholder="Örneğin. Ankara" required>
            </div>
            <div class="inputBox">
                <span>İlçe :</span>
                <input type="text" name="state" placeholder="Örneğin. Yenimahalle" required>
            </div>
            <div class="inputBox">
                <span>ÜLKE :</span>
                <input type="text" name="country" placeholder="Örneğin. TÜRKİYE" required>
            </div>
            <div class="inputBox">
                <span>posta kodu :</span>
                <input type="number" min="0" name="pin_code" placeholder="Örneğin. 123456" required>
            </div>
        </div>

        <input type="submit" name="order" value="şimdi sipariş ver" class="btn">

    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>