<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  if(!$loggedIn) {header("Location: /index"); die();}
  $forumID = mysqli_real_escape_string($conn, intval($_GET['id']));
  
  if($power <= 0 && $forumID <= 0) {echo"<script>location.replace('board?id=1');</script>";}
    //header("board?id=1");
  
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));} else {$page = 0;}
  $page = max($page,1);
  
  $sqlCount = "SELECT * FROM `forum_threads` WHERE `board_id` = '$forumID' AND `deleted` = 'no'";
  $countQ = $conn->query($sqlCount);
  $count = $countQ->num_rows;
  
  $page = min($page,max((int)($count/20),1));
  
  $limit = ($page-1)*20;
  $sqlPosts = "SELECT * FROM `forum_threads` WHERE  `board_id` = '$forumID' AND `deleted` = 'no' ORDER BY `pinned` ASC, `latest_post` DESC LIMIT $limit,20";
  $postsResult = $conn->query($sqlPosts);
    
  
  $BoardSQL = "SELECT * FROM `forum_boards` WHERE `id` = '$forumID'";
  $Board = $conn->query($BoardSQL);
  $BoardRow = $Board->fetch_assoc();
  $BoardName = $BoardRow['name'];
  
?>

<title> <?php echo $BoardName ?> - <?php echo $sitename; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="<?php echo $BoardRow['description'] ?>">
  <meta name="keywords" content="free,game">
  
  <meta property="og:title" content="<?php echo $BoardRow['name'] ?>" />
  <meta property="og:description" content="<?php echo $BoardRow['description'] ?>" />
  <meta property="og:type" content="website" />
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="col-8-12">
<div class="card forum-links inline">
<div class="content">
<div class="inline">
<a href="/rules">Rules</a>
<span class="divide"></span>
<div class="inline">
<a href="/forum/bookmarks">Bookmarked
</a>
</div>
<a href="/forum/my_posts">My Posts</a>
<a href="/forum/drafts">Drafts</a>
</div>
</div>
</div>
</div>
</div>
<div class="col-10-12 push-1-12">
<div class="push-right mobile-col-1-1 pr0">
<a class="button small blue" href="/forum/create?id=<?php echo $forumID; ?>">CREATE</a>
</div>
<div class="forum-bar weight600" style="padding:10px 5px 10px 0;">
<a href="/forum/">Forum</a> <i class="fa fa-angle-double-right" style="font-size:1rem;" aria-hidden="true"></i> <a href="/forum/<?php echo $forumID; ?>/"><?php echo $BoardName ?></a>
</div>
<div class="card">
<div class="top blue">
<div class="col-7-12"><?php echo $BoardName ?></div>
<div class="no-mobile overflow-auto topic text-center">
<div class="col-3-12 stat">Replies</div>
<div class="col-3-12 stat">Views</div>
<div class="col-5-12"></div>
</div>
</div>
<div class="content" style="padding: 0px">
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
              echo '
<div class="hover-card m0 thread-card ">
<div class="col-7-12 topic ellipsis">';
//<span class="thread-label blue">Pinned</span>
//<span class="thread-label blue">Locked</span>
if ($postRow['pinned'] == 'yes') {echo '<span class="thread-label blue">Pinned</span>';}
if ($postRow['locked'] == 'yes') {echo '<span class="thread-label blue">Locked</span>';}
  $timeago = $postRow['date'];
  echo'
<a href="/forum/thread/' . $postID .'/"><span class="small-text label dark">' . htmlentities($postRow['title']) . '</span></a><br>
<span class="label smaller-text">By <a href="/user/8389/" class="darkest-gray-text">' . $authorRow['username'] . '</a></span>
</div>
<div class="no-mobile overflow-auto topic">
<div class="col-3-12 pt2 stat center-text">
<span class="title">' . $replyNum . '</span>
</div>
<div class="col-3-12 pt2 stat center-text">
<span class="title">' . $postViews . '</span>
</div>
<div class="col-6-12 post ellipsis text-right">
<span class="label dark small-text">'.$postRow['date'].'</span><br>
<span class="label dark-gray-text smaller-text">By
<a class="darkest-gray-text" href="/user/8389/">
' . $forumUserRow['username'] . '
</a>
</span>
</div>
</div>
</div>

';
            }
          ?>
        </div>
</div>
      <?php
      echo '</div><div class="numButtonsHolder" style="margin-left:auto;margin-right:auto;margin-top:10px;">';
      if($page-2 > 0) {
          echo '<a href="board?id='.$forumID.'&page=0">1</a> ... ';
      }
      if($count/20 > 1) {
              for($i = max($page-2,0); $i < min($count/20,$page+2); $i++)
              {
                echo '<a href="board?id='.$forumID.'&page='.($i+1).'">'.($i+1).'</a> ';
              }
            }
            if($count/20 > 4) {
                echo '... <a href="board?id='.$forumID.'&page='.(int)($count/20).'">'.(int)($count/20).'</a> ';
            }
      
      echo '</div>';
      ?>

    </div>
  </div>
  </div>
  </div>
    <?php
    include("../SiT_3/footer.php");
    ?>
  </body>