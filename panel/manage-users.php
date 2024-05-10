<?php 
require("adminOnly.php");
include("../SiT_3/configuration.php");
include("adminheader.php");
include("../SiT_3/config.php");
  
    if($power < 1) {header("Location: ../");die(); }

$econSQL = "SELECT SUM(`bits`) AS 'totalBits' FROM `beta_users`";
$econQ = $conn->query($econSQL);
$econRow = $econQ->fetch_assoc();
$totalBits = $econRow['totalBits'];

$econSQL = "SELECT SUM(`bucks`) AS 'totalBucks' FROM `beta_users`";
$econQ = $conn->query($econSQL);
$econRow = $econQ->fetch_assoc();
$totalBucks = $econRow['totalBucks'];

$econ = $totalBits+($totalBucks*10);
?>
<head>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<title><?php echo $sitename;?> - Manage Users</title>
</head>



<div class="container">

<div class="row">
<div class="col-4">
<h3>Admin</h3>
</div>
<div class="col-8 text-right">
</div>
</div>
<ul class="breadcrumb bg-white">
<li class="breadcrumb-item"><a href="/panel/">Admin</a></li>
<li class="breadcrumb-item active">Manage Users and Economy</li>
</ul>

<div id="subsect">
    <div class="card">
        <div class="card-body">
          <h5>Economy (in Bits): <?php echo $econ; ?></h5>
        <i>( Abuse of this feature will result in indefinite suspension of your administrative privileges.<br>Logs are kept. TheDenBRUH is lazy. )</i>
        </div>
                <div class="row">
                    <div class="col-md-4" style="margin:10px;">
        <h4>Item</h4>
        <form action="" method="POST" style="margin:10px;">
          User ID: <input type="text" name="user"><br>
          Item ID: <input type="text" name="item"><br><br>
          <input type="submit" class="button blue upload-submit" name="grant" value="Grant Item">
        </form><br>
        <h4>Currency</h4>
        <form action="" method="POST" style="margin:10px;">
          User ID: <input type="text" name="user"><br>
          Bits: <input type="text" name="bits"><br>
          Bucks: <input type="text" name="bucks"><br><br>
          <input type="submit" class="button blue upload-submit" name="money" value="Add Currency">
        </form><br> </div></div>
  <div class="col-md-4" style="margin-left:50%; margin-top:-35%;">
        <h4>Membership</h4>
        <form action="" method="POST" style="margin:10px;">
          User ID: <input type="text" name="user"><br>
          Membership: <select name="value">
            <option value="1">Ace</option>
            <option value="2">Mint</option>
            <option value="3">Royal</option>
          </select><br>
          Length (Minutes): <input type="number" name="length"><br><br>
          <input type="submit" class="button blue upload-submit" name="membership" value="Set Membership">
        </form><br>
        <h4>Password</h4>
        <form action="" method="POST" style="margin:10px;">
          User ID: <input type="text" name="user"><br><br>
          <input type="submit" class="button blue upload-submit" name="password" value="Reset Password">
        </form></div>
      </div> </div> </div>
    </div>  </div>   </div>
