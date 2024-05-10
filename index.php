<?php
  include("SiT_3/config.php");
  include("SiT_3/head.php");

  if($loggedIn) {header("Location: /dashboard"); die();}
     
       ?>
<?php
$fetch_users = $conn->query("SELECT * FROM beta_users");
$total_users = mysqli_num_rows($fetch_users);

?>
<head>    
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $sitename; ?></title>
  </head>
<style>
.index-top-bar {
    background-color: #00062C;
    margin-top: -3%;
    text-align: center;
    color: #FFF;
}
.index-top-bar img {
    max-width: 80%;
    width: 550px;
    margin-top: 25px;
}
.index-top-bar .bar-1 {
    margin-top: 10px;
}
.index-top-bar .bar-1 span {
    font-size: 20px;
    margin-right: 75px;
}
.index-top-bar .bar-2 {
    margin-top: 30px;
    padding-bottom: 25px;
}
.col-1-2 {
    padding-right: 0;
}
.mt20 {
    margin-top: 20px;
}
</style>
<div class="index-top-bar">
  <img src="assets/pivo.png" style="width:50%; height:auto;">
<div class="bar-1">
<span>Look Around</span>
<button class="orange no-click" style="font-size:20px;">become sigma</button>
</div>
<div class="bar-2">
<span class="block">fixed by thedenbruh, the (not now) dev of virto<br>fix yo security and site design, kids.</span>
<span>Be a part of <b><?php echo $total_users ?></b> users!<br><h5>also play virto, our site is not screwed like this!!!</h5></span>
</div>
</div>
<?php
  $id = 1;
  $findalertSQL = "SELECT * FROM `alert` WHERE `id` = '$id'";
  $findalert = $conn->query($findalertSQL);
  $alertRow=$findalert->fetch_assoc();
echo('<div class="grid"><div class="alert '.$alertRow['type'].'">'.$alertRow['alert'].'</div></div>');
?>
<div class="main-holder grid">
<div class="mt20" style="min-height:360px;">
<div class="col-1-2">
<div class="card" style="border-radius:5px 0 0 5px;">
<div class="top blue">The Limits of Imagination</div>
<div class="content darkest-grey-text" style="height:320px;">
<?php echo $sitename; ?> is the ultimate building block game, allowing thedenbruh to do kids "homework" with them trying to do the site for 2 weeks.
<br><br>
You're NOT able to compete, build, and play with other users in an environment of 2017 Workshop.exe, with the extensive games NOT being made by our users. Check out some of the TheDenBruh Deez Nuts Hell (tdbdnh) gameplay now!
</div>
</div>
</div>
<div class="col-1-2 no-mobile" style="height:360px;">
<iframe style="box-shadow:0 2px 5px rgba(0,0,0,.2);height:100%;width:100%;" type="text/html" src="https://www.youtube.com/embed/yb8YeD5XiQE" frameborder="0"></iframe>
</div>
</div>
<div class="mt20" style="text-align:center;min-height:300px;">
<div class="col-1-2">
<img style="width:500px; height:300px;" src="/assets/dcsucks.gif"></img>
</div>
<div class="col-1-2 darkest-grey-text" style="padding:8.75% 0;">
Customise your avatar in seemingly limited ways! Choose from a <a href="/shop" style="color:dodgerblue;">catalog</a> full of items and clothing created by us and the community!
<br><br>
With a beta and die-in-a-fire interface, you'll be able to make your character look exactly how you don't want it to.
<br><br>
If you're stumped, you can always see what other users have been buying in their <a href="/user/2#tabs" style="color:dodgerblue;">crate</a>.
</div>
</div>
<div class="mt20">
<div class="col-1-2">
<div class="card" style="border-radius:5px 0 0 5px;">
<div class="top orange">Explore the Workshop</div>
<div class="content darkest-grey-text" style="height:254.18px;">
A game built for imagination, this tailor-made workshop allows you to put practically anything you envision into reality.<br>
The simple layout and user-friendly interface ensures that you will be able to make what you want, no matter your experience.
<br><br>
If you're ever stuck, we've got a friendly <a href="/forum/5/" style="color:dodgerblue;">support section</a> on the forum where the staff and users can help you with whatever you need!
<br><br>
We have NOTHING. And we are not that serious as you might think.
</div>
</div>
</div>
<div class="col-1-2">
<img style="box-shadow:0 2px 5px rgba(0,0,0,.2);width:100%;" src="assets/workshop.png">
</div>
</div>
</div>
<br><br><br>
<?php
  include("SiT_3/footer.php")
    ?>