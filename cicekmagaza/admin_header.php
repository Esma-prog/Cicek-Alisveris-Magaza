<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="flex">

      <a href="admin_anasayfa.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_anasayfa.php">Anasayfa</a>
         <a href="admin_urunler.php">Ürünler</a>
         <a href="admin_siparisler.php">Siparişler</a>
         <a href="admin_kullanici.php">Kullanıcılar</a>
         <a href="admin_mesaj.php">Mesajlar</a>
         <a href="Anasayfa.php">Siteye Geri Dön</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>Kullanıcı adı : <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">Oturum kapa</a>
         <div>Yeni <a href="login.php">Oturum aç</a> | <a href="register.php">Kayıt olma</a> </div>
      </div>

   </div>

</header>