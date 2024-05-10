<?php
include('../../SiT_3/config.php');
include('../../SiT_3/header.php');

if(!$loggedIn) {header('Location: ../'); die();}

?>

<!DOCTYPE html>
  <head>
    <title>Create - <?php echo $sitename; ?></title>
  </head>
  <div class="main-holder grid">
<div class="col-10-12 push-1-12">
</div>
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top green">Upload</div>
<div class="content">
<div id="types" style="text-align:center;">

        <div style="display:inline-block;">
          <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="tshirt"><img src="_tshirt.png"><br>T-Shirt</a>
        </div>
        <div style="display:inline-block;">
          <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="shirt"><img src="_shirt.png"><br>Shirt</a>
        </div>
        <div style="display:inline-block;">
          <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="pants"><img src="_pants.png"><br>Pants</a>
        </div>
  <?php
        if ($power >= 4) {
          ?>
                  <div style="display:inline-block;">
            <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="hat"><img src="_hat.png"><br>Hat</a>
          </div>
          <div style="display:inline-block;">
            <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="tool"><img src="_pants.png"><br>Tool</a>
          </div>
<div style="display:inline-block;">
            <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="face"><img src="_pants.png"><br>Face</a>
          </div>
   <div style="display:inline-block;">
            <a style="font-weight:bold;font-size:18px;color:;margin:5px;" href="head"><img src="_head.png"><br>Head</a>
          </div>
          <div style="display:inline-block;">
                </div>
    </div>
  </div>
  </div>
  </div>
      </div>
    </div>
  <?php
  }
    ?>
    </body>
</div>
  <?php include('../../SiT_3/footer.php'); ?>
  </body>
</html>