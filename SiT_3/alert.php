<?php
      
     $bannerSQL = "SELECT * FROM `site_banner`";
     $banner = $conn->query($bannerSQL);
     $bannerRow = $banner->fetch_assoc();

if($kek = mysqli_num_rows($banner) > 0){
$alert = $bannerRow['text']; 
echo '<div class="grid">
      <div class="alert success">
      <!-- dear rawdog kids, if you steal it <br> atleast credit my work -->
      '.$alert.'  </div>   </div>
    					';}
   						?>


