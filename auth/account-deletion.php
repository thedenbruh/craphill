<?php
  include("../SiT_3/reliveheader.php");
  if (isset($_POST['delete'])) {
$userID = $userRow->{'id'};
$delete = "TRUNCATE `beta_users` WHERE `id` = '$userID'";
$ok = $conn->query($delete);
    header("Location: /login/"); die();
}
  ?>
<title>Account Deletion - Builder Land</title>
<div class="container mt-4">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
            <div class="card card-body">
                <h2>Account Deletion</h2>
                    <p>To delete your account click the Delete button below</p>
              <input class="btn btn-danger " type="submit" value="Delete" id="login-submit" name="delete">
