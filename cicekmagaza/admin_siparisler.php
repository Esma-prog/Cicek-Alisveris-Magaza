<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_odeme = $_POST['update_odeme'];
   mysqli_query($conn, "UPDATE `orders` SET odeme_durumu = '$update_odeme' WHERE id = '$order_id'") or die('query failed');
   $message[] = 'ödeme durumu güncellendi!';
}  

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_siparisler.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gösterge Paneli</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">verilen siparişler</h1>

   <div class="box-container">

      <?php
      
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders` order by placed_on") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> Kullanıcı id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> tarih : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> ad : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> numara : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> toplam ürün : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> toplam fiyat: <span>TL<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> ödeme yöntemi : <span><?php echo $fetch_orders['method']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_odeme">
               <option disabled selected><?php echo $fetch_orders['odeme_durumu']; ?></option>
               <option value="Bekleniyor">sırada</option>
               <option value="Tamamlandı">tamamlanmış</option>
            </select>
            <input type="submit" name="update_order" value="Güncelle" class="option-btn">
            <a href="admin_siparisler.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('bu sipariş silinsin mi?');">Sil</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">henüz sipariş verilmedi!</p>';
      }
      ?>
   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>