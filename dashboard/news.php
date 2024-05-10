<?php
  include("../SiT_3/config.php");
 
  $sqlCount = "SELECT * FROM `news` ORDER BY `lupd`";
  $countQ = $conn->query($sqlCount);
  $count = $countQ->num_rows;
  
  
?>
<?php
            while($postRow=$countQ->fetch_assoc()) {
              $postID = $postRow['id'];
              $authorID = $postRow['uid'];
              
              $sqlAuthor = "SELECT * FROM `beta_users` WHERE `id`='$authorID'";
              $author = $conn->query($sqlAuthor);
              $authorRow = $author->fetch_assoc(); 
echo '<a href="/blog?id='.$postID.'">'.$postRow['title'].'<br><i>By '.$authorRow['username'].'</i>, '.$postRow['lupd'].'<hr>';}
              
          ?>
