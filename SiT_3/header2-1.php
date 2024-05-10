<?php
if($loggedIn) {
echo '<div id="navbar2"><span><span>';
echo '<a class="nav" href="/">Home</a>';
echo '<span> | </span>';
echo '<a class="nav" href="/settings/">Settings</a>';
echo '<span> | </span>';
echo '<a class="nav" href="/customize/">Avatar</a>';
echo '<span> | </span>';
echo '<a class="nav" href="/user?id='.$userRow->{'id'}.'">Profile</a>';
echo '<span> | </span>';
echo '<a class="nav" href="/download/">Download</a>';
echo '<span> | </span>';
echo '<a class="nav" href="/information/money/">Currency</a>';
echo '<span> | </span>';
echo '<a class="nav" href="https://blog.shit-hell.cf/">Blog</a>';
echo '<span> | <span>';
?>
<a class="nav" href="/trade/">
                <?php
             echo 'Trades (';   if($loggedIn) {
                  $mID = $userRow->{'id'};
                  $sqlSearch = "SELECT * FROM `trades` WHERE  `tradereceiver` = '$mID' AND `decision` = 0";
                  $result = $conn->query($sqlSearch);
                  
                  $trades = 0;
                  while($searchRow=$result->fetch_assoc()) {$trades++;}
                  echo number_format($trades);
                }
echo ')</a>';  ?>
<?php
} else {
echo '';
  }
?>
</div>