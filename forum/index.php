<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
?>
<html>
  <head>
    <title>Forum - <?php echo $sitename; ?></title>
  </head>
  <body>
<div class="main-holder grid">
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
<div class="col-8-12">
<div class="card">
<div class="top blue">
<div class="col-7-12"><?php echo $sitename; ?></div>
<div class="no-mobile overflow-auto topic text-center">
<div class="col-3-12 stat">Threads</div>
<div class="col-3-12 stat">Replies</div>
<div class="col-6-12"></div>
</div>
</div>
<div class="content">
<?php
          if(!$loggedIn || $power <= 0) {$sqlForum = "SELECT * FROM `forum_boards` WHERE `id` >= 1 ORDER BY `id` ASC LIMIT 5";}
          else {$sqlForum = "SELECT * FROM `forum_boards` ORDER BY `id` ASC LIMIT 5" ;}
          $boardResult = $conn->query($sqlForum);
          $table = '';
          
          while($forumRow=$boardResult->fetch_assoc()) {
            $forumID = $forumRow['id'];
            
            $sqlThread = "SELECT * FROM `forum_threads` WHERE  `board_id` = '$forumID' AND `deleted` = 'no' ORDER BY `latest_post` DESC";
            $threadResult = $conn->query($sqlThread);
            $threadRow = $threadResult->fetch_assoc();
            $threadID = $threadRow['id'];
            
            $sqlPost = "SELECT * FROM `forum_posts` WHERE  `thread_id` = '$threadID' ORDER BY `id` DESC";
            $postResult = $conn->query($sqlPost);
            $postRow = $postResult->fetch_assoc();
            if ($postResult->num_rows == 0) {//($postRow['author_id']->num_rows == 0) {
              $sqlPost = "SELECT * FROM `forum_threads` WHERE  `id` = '$threadID' ORDER BY `id` DESC";
              $postResult = $conn->query($sqlPost);
              $postRow = $postResult->fetch_assoc();
            }
            
            $authorID = $postRow['author_id'];
            $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$authorID'";
            $forumUserResult = $conn->query($sqlUser);
            $forumUserRow=$forumUserResult->fetch_assoc();
            
            $topicsSQL = "SELECT * FROM `forum_threads` WHERE `board_id` = '$forumID'";
            $topicsResult = $conn->query($topicsSQL);
            
            $postsSQL = "SELECT * FROM `forum_posts`";
            $postsResult = $conn->query($postsSQL);
            $count = 0;
            while ($postsRow=$postsResult->fetch_assoc()) {
              $threadParent = $postsRow['thread_id'];
              $threadsSQL = "SELECT * FROM `forum_threads` WHERE `id` = '$threadParent' AND `board_id` = '$forumID'";
              $threadsResult = $conn->query($threadsSQL);
              if ($threadsResult->num_rows != 0) {$count += 1;}
            }
            
            //this is where is slows down
            $table .= '<div class="board-info mb1">
<div class="col-7-12 board">
<div><a class="label dark" href="board/' . $forumID .'">' . $forumRow['name'] . '</a></div>
 <span class="label small">' . $forumRow['description'] . '</span>
</div>
<div class="no-mobile overflow-auto board ellipsis" style="overflow:hidden;">
<div class="col-3-12 stat">
<span class="title">' . $topicsResult->num_rows . '</span>
</div>
<div class="col-3-12 stat">
<span class="title">' . ($count+$topicsResult->num_rows) . '</span>
</div>
<div class="col-6-12 text-right ellipsis pt2" style="max-width:180px;">
<a href="/forum/thread/' . $threadRow['id'] . '/" class="label dark">' . htmlentities($threadRow['title']) . '</a><br>
<span class="label small">' . $postRow['date'] . '</span>
</div></div></div>
';
            }
            echo $table;
          ?>
</div>
</div>
<div class="card">
<div class="top green">
<div class="col-7-12">Random</div>
<div class="no-mobile overflow-auto topic text-center">
<div class="col-3-12 stat">Threads</div>
<div class="col-3-12 stat">Replies</div>
<div class="col-6-12"></div>
</div>
</div>
<div class="content">
<?php
          if(!$loggedIn || $power <= 0) {$sqlForum = "SELECT * FROM `forum_boards` WHERE `id` >= 1 ORDER BY `id` ASC LIMIT 5 OFFSET 5";}
          else {$sqlForum = "SELECT * FROM `forum_boards` ORDER BY `id` ASC LIMIT 5 OFFSET 5" ;}
          $boardResult = $conn->query($sqlForum);
          $table = '';
          
          while($forumRow=$boardResult->fetch_assoc()) {
            $forumID = $forumRow['id'];
            
            $sqlThread = "SELECT * FROM `forum_threads` WHERE  `board_id` = '$forumID' AND `deleted` = 'no' ORDER BY `latest_post` DESC";
            $threadResult = $conn->query($sqlThread);
            $threadRow = $threadResult->fetch_assoc();
            $threadID = $threadRow['id'];
            
            $sqlPost = "SELECT * FROM `forum_posts` WHERE  `thread_id` = '$threadID' ORDER BY `id` DESC";
            $postResult = $conn->query($sqlPost);
            $postRow = $postResult->fetch_assoc();
            if ($postResult->num_rows == 0) {//($postRow['author_id']->num_rows == 0) {
              $sqlPost = "SELECT * FROM `forum_threads` WHERE  `id` = '$threadID' ORDER BY `id` DESC";
              $postResult = $conn->query($sqlPost);
              $postRow = $postResult->fetch_assoc();
            }
            
            $authorID = $postRow['author_id'];
            $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$authorID'";
            $forumUserResult = $conn->query($sqlUser);
            $forumUserRow=$forumUserResult->fetch_assoc();
            
            $topicsSQL = "SELECT * FROM `forum_threads` WHERE `board_id` = '$forumID'";
            $topicsResult = $conn->query($topicsSQL);
            
            $postsSQL = "SELECT * FROM `forum_posts`";
            $postsResult = $conn->query($postsSQL);
            $count = 0;
            while ($postsRow=$postsResult->fetch_assoc()) {
              $threadParent = $postsRow['thread_id'];
              $threadsSQL = "SELECT * FROM `forum_threads` WHERE `id` = '$threadParent' AND `board_id` = '$forumID'";
              $threadsResult = $conn->query($threadsSQL);
              if ($threadsResult->num_rows != 0) {$count += 1;}
            }
            
            //this is where is slows down
            $table .= '<div class="board-info mb1">
<div class="col-7-12 board">
<div><a class="label dark" href="board/' . $forumID .'">' . $forumRow['name'] . '</a></div>
 <span class="label small">' . $forumRow['description'] . '</span>
</div>
<div class="no-mobile overflow-auto board ellipsis" style="overflow:hidden;">
<div class="col-3-12 stat">
<span class="title">' . $topicsResult->num_rows . '</span>
</div>
<div class="col-3-12 stat">
<span class="title">' . ($count+$topicsResult->num_rows) . '</span>
</div>
<div class="col-6-12 text-right ellipsis pt2" style="max-width:180px;">
<a href="/forum/thread/' . $threadRow['id'] . '/" class="label dark">' . htmlentities($threadRow['title']) . '</a><br>
<span class="label small">' . $postRow['date'] . '</span>
</div></div></div>
';
            }
            echo $table;
          ?>
</div>
</div>
<div class="card">
<div class="top orange">
<div class="col-7-12">Idk what to do with this</div>
<div class="no-mobile overflow-auto topic text-center">
<div class="col-3-12 stat">Threads</div>
<div class="col-3-12 stat">Replies</div>
<div class="col-6-12"></div>
</div>
</div>
<div class="content">
<?php
          if(!$loggedIn || $power <= 0) {$sqlForum = "SELECT * FROM `forum_boards` WHERE `id` >= 1 ORDER BY `id` ASC LIMIT 5 OFFSET 10";}
          else {$sqlForum = "SELECT * FROM `forum_boards` ORDER BY `id` ASC LIMIT 5 OFFSET 10" ;}
          $boardResult = $conn->query($sqlForum);
          $table = '';
          
          while($forumRow=$boardResult->fetch_assoc()) {
            $forumID = $forumRow['id'];
            
            $sqlThread = "SELECT * FROM `forum_threads` WHERE  `board_id` = '$forumID' AND `deleted` = 'no' ORDER BY `latest_post` DESC";
            $threadResult = $conn->query($sqlThread);
            $threadRow = $threadResult->fetch_assoc();
            $threadID = $threadRow['id'];
            
            $sqlPost = "SELECT * FROM `forum_posts` WHERE  `thread_id` = '$threadID' ORDER BY `id` DESC";
            $postResult = $conn->query($sqlPost);
            $postRow = $postResult->fetch_assoc();
            if ($postResult->num_rows == 0) {//($postRow['author_id']->num_rows == 0) {
              $sqlPost = "SELECT * FROM `forum_threads` WHERE  `id` = '$threadID' ORDER BY `id` DESC";
              $postResult = $conn->query($sqlPost);
              $postRow = $postResult->fetch_assoc();
            }
            
            $authorID = $postRow['author_id'];
            $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$authorID'";
            $forumUserResult = $conn->query($sqlUser);
            $forumUserRow=$forumUserResult->fetch_assoc();
            
            $topicsSQL = "SELECT * FROM `forum_threads` WHERE `board_id` = '$forumID'";
            $topicsResult = $conn->query($topicsSQL);
            
            $postsSQL = "SELECT * FROM `forum_posts`";
            $postsResult = $conn->query($postsSQL);
            $count = 0;
            while ($postsRow=$postsResult->fetch_assoc()) {
              $threadParent = $postsRow['thread_id'];
              $threadsSQL = "SELECT * FROM `forum_threads` WHERE `id` = '$threadParent' AND `board_id` = '$forumID'";
              $threadsResult = $conn->query($threadsSQL);
              if ($threadsResult->num_rows != 0) {$count += 1;}
            }
            
            //this is where is slows down
            $table .= '<div class="board-info mb1">
<div class="col-7-12 board">
<div><a class="label dark" href="board/' . $forumID .'">' . $forumRow['name'] . '</a></div>
 <span class="label small">' . $forumRow['description'] . '</span>
</div>
<div class="no-mobile overflow-auto board ellipsis" style="overflow:hidden;">
<div class="col-3-12 stat">
<span class="title">' . $topicsResult->num_rows . '</span>
</div>
<div class="col-3-12 stat">
<span class="title">' . ($count+$topicsResult->num_rows) . '</span>
</div>
<div class="col-6-12 text-right ellipsis pt2" style="max-width:180px;">
<a href="/forum/thread/' . $threadRow['id'] . '/" class="label dark">' . htmlentities($threadRow['title']) . '</a><br>
<span class="label small">' . $postRow['date'] . '</span>
</div></div></div>
';
            }
            echo $table;
          ?>
</div>
</div>
</div>
<div class="col-4-12">
<div class="card">
<div class="top">
Recent Topics
</div>
<?php
  include("recent.php");
  ?>
</div>
</div>
</div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div>
    <?php
    include("../SiT_3/footer.php");
    ?>
  </body>
</html>