<?php
//experimental thing, please do not fuck it up

  include("../SiT_3/config.php");
  
  $forumID = "";
  
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));} else {$page = 0;}
  $page = max($page,1);
  
  $sqlCount = "SELECT * FROM `forum_threads` WHERE `board_id` = '$forumID' AND `deleted` = 'no'";
  $countQ = $conn->query($sqlCount);
  $count = $countQ->num_rows;
  
  
  $sqlPosts = "SELECT * FROM `forum_threads` WHERE `deleted` = 'no' AND `pinned` = 'no' AND `locked` = 'no' ORDER BY `pinned` ASC, `id` DESC LIMIT 6";
  $postsResult = $conn->query($sqlPosts);
    
  
  $BoardSQL = "SELECT * FROM `forum_boards` WHERE `id` = '$forumID'";
  $Board = $conn->query($BoardSQL);
  $BoardRow = $Board->fetch_assoc();
  $BoardName = $BoardRow['name'];
  
?>
<body>
    <div class="content">
          
          
          <?php
            while($postRow=$postsResult->fetch_assoc()) {
              $postID = $postRow['id'];
              $authorID = $postRow['author_id'];
              
              $sqlAuthor = "SELECT * FROM `beta_users` WHERE `id`='$authorID'";
              $author = $conn->query($sqlAuthor);
              $authorRow = $author->fetch_assoc();
              
              $sqlReply = "SELECT * FROM `forum_posts` WHERE  `thread_id` = '$postID' ORDER BY `id` DESC";
              $replyResult = $conn->query($sqlReply);
              $replyRow=$replyResult->fetch_assoc();
              $replyNum=$replyResult->num_rows;
              $lastReplyID = $replyRow['author_id'];
              if (empty($lastReplyID)) {
                $sqlReply = "SELECT * FROM `forum_threads` WHERE  `id` = '$postID' ORDER BY `id` DESC";
                $replyResult = $conn->query($sqlReply);
                $replyRow=$replyResult->fetch_assoc();
                $lastReplyID = $replyRow['author_id'];
              }
              
              $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$lastReplyID'";
              $forumUserResult = $conn->query($sqlUser);
              $forumUserRow=$forumUserResult->fetch_assoc();
              
              $postViews = $postRow['views'] -1;
               

              if ($postRow['pinned'] == 'yes') {echo '<i class="fa fa-thumb-tack"></i> ';}
              if ($postRow['locked'] == 'yes') {echo '<i class="fa fa-lock"></i> ';}
              echo '<tr><td><a class="title" href="thread?id=' . $postID .'">' . htmlentities($postRow['title']) . '</a><br><span class="label small"><a href="/user?id='.$authorID.'">by ' . $authorRow['username'] . '</a></span>
</td>
<td>
		<div class="forum-tag" style="margin-top:-15px; height:35px;">
                <p style="text-align:right;margin-top:2.5px;">' . $replyNum . '</p>
		</div>
              </td>
              </tr><hr>';
            }
          ?>
        </tbody>
      </table>
    </div>
  </body>