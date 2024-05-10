<?php
include('../SiT_3/header.php');
include('../SiT_3/config.php');
  $error = array();
  if (isset($_POST['submit'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    
    $findcodeSQL = "SELECT * FROM `promocode` WHERE `code` = '$code' AND `used` = 0";
      
    $findcode = $conn->query($findcodeSQL);

    
    if(empty($error)) {
      if ($findcode->num_rows == 0) {
          $error[] = 'The promocode is Invalid!';
          
      } elseif($findcode->num_rows > 0) {
        
        $codeRow = $findcode->fetch_assoc();
        $codeID = $codeRow['id'];
        $citemID = $codeRow['item_earn'];
        
        $updatecodeSQL = "UPDATE `promocode` SET `used` = '0' WHERE `id` = '$codeID' ";
        $updatecode = $conn->query($updatecodeSQL);
        
        $itemcode = rand(4);
        $additemcodeSQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`,`payment`,`price`,`date`,`own`) VALUES (NULL,'$UID','$citemID','0','bits','0','$curDate','yes')";
        $additemcode = $conn->query($additemcodeSQL);
        $success[] = 'Successfully redeemed promocode!';
      }
    }
  }
    if(empty($error)) {
      }
?>
<!DOCTYPE html>
  <head>
    <title>Promocode - Shut-Hill</title>
  </head>
  <body>
<div class="main-holder grid">
<div class="col-10-12 push-1-12">

      <form action="" method="POST">
        <vue-comp id="redeempromo-v" data-v-app="">
  <div class="new-theme" data-v-756e1e11="">
    <div class="col-5-12" data-v-756e1e11="">
      <div class="large-text bold" style="margin-bottom: 20px;" data-v-756e1e11="">REDEEM PROMO CODE</div>
      <div style="margin-bottom: 5px;" data-v-756e1e11="">Enter code here:</div>
      <div data-v-756e1e11="">
        <input style="margin-bottom: 10px;" name="code">
  <button type="submit" name="submit" class="blue">
    REDEEM
  </button>

      </div>
              <?php
          if(!empty($error)) {
            echo '<div data-v-756e1e11="" class="smaller-text lower-text"><!--v-if-->
  <svg data-v-756e1e11="" width="16" height="16" style="height: 16px; width: 16px;" class="svg-icon-text">
    <use xmlns:xlink="http://www.w3.org/1999/xlink" href="#error" xlink-href="#error">
              <symbol viewBox="0 0 16 16" id="error" xmlns="http://www.w3.org/2000/svg"><circle cy="8" cx="8" style="fill:none;fill-opacity:1;stroke:#dc0f18;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1" r="7"></circle><path d="m11 5-6 6m6 0L5 5" style="fill:none;stroke:#dc0f18;stroke-width:2;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"></path></symbol>
    </use></svg> ';
            foreach($error as $line) {
              echo $line.'';
            }
            echo '</div>';
          }
        ?>
            <?php
          if(!empty($success)) {
            echo '<div style="background-color:#6aad5f;margin:10px;padding:5px;color:white;">';
            foreach($success as $line) {
              echo $line.'<br>';
            }
            echo '</div>';
          }
        ?>

      
      <div style="padding-bottom: 50px;" data-v-756e1e11="">
        Promo codes can be obtained through official Brick Hill promotions or through events hosted by us. As well as this, promo codes may be included in products or merchandise produced by us.
        <br data-v-756e1e11="">
        <br data-v-756e1e11="">
        All available items that are part of current promotions can be seen below.
      </div>
    </div>
    <div class="col-1-1" data-v-756e1e11="">
      <div class="large-text bold" style="margin-bottom: 20px;" data-v-756e1e11="">AVAILABLE ITEMS</div>
      <div class="carousel" data-v-756e1e11="">
        <div class="col-1-5 mobile-col-1-2" data-v-756e1e11="">
          <div class="item" style="text-align: center; padding: 10px;" data-v-756e1e11="">
            <img style="max-width: 100%;" data-v-756e1e11="">
          </div>
        </div>
        <div class="col-1-5 mobile-col-1-2" data-v-756e1e11="">
          <div class="item" style="text-align: center; padding: 10px;" data-v-756e1e11="">
            <img style="max-width: 100%;" data-v-756e1e11="">
          </div>
        </div>
        <div class="col-1-5 mobile-col-1-2" data-v-756e1e11="">
          <div class="item" style="text-align: center; padding: 10px;" data-v-756e1e11="">
            <img style="max-width: 100%;" data-v-756e1e11="">
          </div>
        </div>
        <div class="col-1-5 mobile-col-1-2" data-v-756e1e11="">
          <div class="item" style="text-align: center; padding: 10px;" data-v-756e1e11="">
            <img style="max-width: 100%;" data-v-756e1e11="">
          </div>
        </div>
<div class="col-1-5 mobile-col-1-2" data-v-756e1e11="">
          <div class="item" style="text-align: center; padding: 10px;" data-v-756e1e11="">
            <img style="max-width: 100%;" data-v-756e1e11="">
          </div>
        </div>
        <div class="col-1-5 mobile-col-1-2" data-v-756e1e11="">
          <div class="item" style="text-align: center; padding: 10px;" data-v-756e1e11="">
            <img style="max-width: 100%;" data-v-756e1e11="">
          </div>
        </div>
      </div>
    </div>
  </div>
</vue-comp>

        </form>

</div>
</div>
</div>
</div>
          </div>
</div>
<?php
include('../SiT_3/footer.php')
?>

