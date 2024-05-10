<?php
include('../SiT_3/config.php');
include('../SiT_3/reliveheader.php');
include('../SiT_3/PHP/helper.php');
 
 if($userRow->{'invite'} < 0) {
   header('Location: /');
   die();
 }
 
?>
<!DOCTYPE html>
<html>

  <head>
    <title>Invite Users - <?php echo $sitename; ?></title>

  </head>
  
  <body>
    
    
    <main class="flex-shrink-0">
<div class="container mt-4">
<div class="row">
<div class="col-12">
<h3>Invite</h3>
<p>Invite people using the Create Invite Key button. Only select people that will have access to this and the site, as it's a private website.</p>
<form method="POST">
<input type="submit" name="genKey" class="btn btn-primary mt-1 mb-4" value="Create Invite Key" />
<input type="hidden" name="action" value="CreateInvite" />
<input name="__RequestVerificationToken" type="hidden" value="CfDJ8Hag2sGDeX1BoTU_YdeOUnNp2KAms0fr-eF_2g63hMZvOI7TAMsEg8QkUG0vd6kWuBEKeaEwTaz0WjSbGtdfZqfhx1T9yIMGS0UEwzfc4m4fFQWbMN33KK-1aL4XWrB6nTKNR9ZKdvPvzgbAJk3LMgo" />
</form>
<table class="table">
  Active Keys:
<thead>
<tr>
 <th>ID</th>
<th>Key</th>


</tr>
</thead>
  <?php
            
            $findActiveKeysSQL = "SELECT * FROM `reg_keys` WHERE `used` = '0'";
            $findActiveKeys = $conn->query($findActiveKeysSQL);
            
            if ($findActiveKeys->num_rows > 0) {
              while ($keyRow = $findActiveKeys->fetch_assoc()) {
                $keyRow = (object) $keyRow;
                ?>
                <div style="">
                 <tr>
<td><?php echo $keyRow->{'id'} ; ?></td>
<td><?php echo $keyRow->{'key_content'} ; ?></td>

</tr>
                
                </div>
                <div style="">
                
                </div>
                <?php
              }
            } else {
              ?>
  <div style="color:grey;text-align:center;">
                There are no active keys!
              </div>
              <?php
            }
            
            ?></p>
  
<table class="table">
  Used Keys:
<thead>
<tr>
 <th>ID</th>
<th>Key</th>


</tr>
</thead>
  
      <p><?php
            
            $findActiveKeysSQL = "SELECT * FROM `reg_keys` WHERE `used` = '1'";
            $findActiveKeys = $conn->query($findActiveKeysSQL);
            
            if ($findActiveKeys->num_rows > 0) {
              ?>
              
              <?php
              while ($keyRow = $findActiveKeys->fetch_assoc()) {
                $keyRow = (object) $keyRow;
                ?>
                <div style="">
                 <tr>
<td><?php echo $keyRow->{'id'} ; ?></td>
<td><?php echo $keyRow->{'key_content'} ; ?></td>

</tr>
                
                </div>
                <div style="">
                
                </div>
                <?php
              }
            } else {
              ?>
              <div style="color:grey;text-align:center;">
                There are no used keys!
              </div>
              <?php
            }
            
            ?></p>
    </div>
</div>
      </div>
    <div id="body">
      
   
      <div id="box" style="box-sizing: border-box;padding:5px;">
      <?php
      
      if (isset($_POST['genKey'])) {
        
        function genKey() {
          return rand(1,9) . rand(10000,99999) . range(1,9);
        }
        
        $key = substr(hash('sha256', genKey()), 0, 10);
        
        $createKeySQL = "INSERT INTO `reg_keys` (`id`,`key_content`,`used`) VALUES (NULL, '$key', '0') ";
        $createKey = $conn->query($createKeySQL);
        
        if($createKey) {echo"<script>location.replace('');</script>";die();
        
        ?>
        
        <?php
        die();
        } else {
          die('error');
        }
      }
      
      ?>
        <div>
        </div>
    
  </div></div></div>
          
          <?php
   // include("../SiT_3/relivefooter.php");
    ?>