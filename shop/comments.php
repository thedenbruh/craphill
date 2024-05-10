      <div id="box" style="margin-top:10px;">
        <div id="subsect">
        <h3 style="margin-left:5px;">Comments</h3>

        <?php
        if($loggedIn && isset($_POST['comment'])) {
          $lastCommentSQL = "SELECT * FROM `item_comments` WHERE `author_id`='$userID' ORDER BY `id` DESC";
          $lastCommentQ = $conn->query($lastCommentSQL);
          if($lastCommentQ->num_rows > 0) {
            $lastCommentRow = $lastCommentQ->fetch_assoc();
            $lastComment = $lastCommentRow['time'];
          } else {
            $lastComment = 0;
          }
          
          if(time()-strtotime($lastComment) >= 30) {//they can post
            $comment = mysqli_real_escape_string($conn,$_POST['comment']);
            if(strlen($comment) >= 2 && strlen($comment) <= 100) {
              $commentSQL = "INSERT INTO `item_comments` (`id`,`author_id`,`item_id`,`comment`,`time`) VALUES (NULL,'$userID','$itemID','$comment',CURRENT_TIMESTAMP)";
              $commentQ = $conn->query($commentSQL);
              echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
      } else {
        echo 'Comment must be between 2 and 100 characters';
      }
          } else {
            echo 'Please wait before posting again';
          }
        }
        
        
        
        /*if(isset($_POST['CommentTxT'])){
          
          $CommentTxT = mysqli_real_escape_string($conn,$_POST['CommentTxT']);
          $hasCommentQ = mysqli_query($conn,"SELECT * FROM `item_comments` WHERE `USER_ID`='$userID' AND `ITEM_ID`='$itemID'");
          $hasComment = mysqli_num_rows($hasCommentQ);

          if($hasComment > 0){
            
              
              $PostedCommentsQ = mysqli_query($conn,"SELECT * FROM `item_comments` WHERE `USER_ID`='$userID' AND `ITEM_ID`='$itemID' ORDER BY `TIME` DESC");
              $LatestComment = mysqli_fetch_array($PostedCommentsQ);
              
              $CurrentTime = time();
              $LastPostTime = strtotime($LatestComment['TIME']);

              $TimeCheck = $CurrentTime - $LastPostTime;
              
              if($TimeCheck > 60){
                  // Older than 60 seconds lol, they can post comment
                mysqli_query($conn,"INSERT INTO `item_comments` VALUES(NULL,'$userID','$itemID','$CommentTxT',CURRENT_TIMESTAMP())");
                  echo"<center>
                  <form action='' method='post'>
                    <textarea name='CommentTxT' style='width:790;height:80;' rows='5' cols='120' maxlength='500' disabled>Posted successfully!</textarea>
                    <button disabled>Post</button>
                  </form>
                </center>";
              }else{
                echo"<center>
                  <form action='' method='post'>
                    <textarea name='CommentTxT' style='width:790;height:80;' rows='5' cols='120' maxlength='500' disabled>Please wait!</textarea>
                    <button disabled>Post</button>
                  </form>
                </center>";
              }
              

echo"
                <center>
                  <form action='' method='post'>
                    <textarea name='CommentTxT' style='width:790;height:80;' rows='5' cols='120' maxlength='500' disabled>Please do not spam!</textarea>
                    <button disabled>Post</button>
                  </form>
                </center>";
            }else{
              // They havent posted before. let them post
              mysqli_query($conn,"INSERT INTO `item_comments` VALUES(NULL,'$userID','$itemID','$CommentTxT',CURRENT_TIMESTAMP())");
              //echo"<script>location.reload();</script>";
            }
          }else{
              // They can post
              echo"<form action='' method='post'>
                    <textarea name='CommentTxT' style='width:790;height:80;' rows='5' cols='120' maxlength='500'></textarea>
                    <button>Post</button>
                  </form>";
            }*/
            ?>
            <?php if ($loggedIn) { ?>
            <form action="" method="POST" style="margin:5px;">
              <textarea name="comment" style="width: 99.3%; height: 80px; margin: 0px; resize: vertical;"></textarea><br>
              <input type="submit" value="Comment">
            </form>
<?php } ?>

        </div>
        <?php
        $comments = mysqli_query($conn, "SELECT * FROM `item_comments` WHERE `item_id`='$itemID' ORDER BY `id` DESC");

        while($commentRow = mysqli_fetch_assoc($comments)) {
              $commentUserID = $commentRow['author_id'];
              $userDataQ = mysqli_query($conn,"SELECT * FROM `beta_users` WHERE `id`='$commentUserID'");
              $userData = mysqli_fetch_array($userDataQ);
              echo'<div id="subsect" style="overflow:auto;"><span style="float:left;"><a href="/user?id='.$commentUserID.'" style="color:#333;"><img style="width:55px;" src="/avatar/render/avatars/'.$commentUserID.'.png?c='.rand().'"><br>'.$userData['username'].'</a></span><span><span style="font-size:12px;">'.$commentRow['time'].'</span><br>'. htmlentities($commentRow['comment']).'</span>';
              if($loggedIn && $power >= 1) {
                echo '<div><a class="label" href="item?id='.$shopRow['id'].'&scrub_comment='.$commentRow['id'].'">Scrub</a></div>';
              }
              echo '</div>';
        }
        ?>