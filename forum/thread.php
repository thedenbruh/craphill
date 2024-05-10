<?php
				//mess.
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");

  $threadID = $_GET['id'];
  $threadIDSafe = mysqli_real_escape_string($conn, intval($threadID));
  $threadID = $threadIDSafe;
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));} else {$page = 1;}
  $page = max($page,1);
  
  if($power >= 1) {
    if(isset($_GET['scrub'])) {
      $postID = mysqli_real_escape_string($conn,$_GET['scrub']);
      $scrubSQL = "UPDATE `forum_posts` SET `body`='[ Content Removed ]' WHERE `id`='$postID'";
      $scrub = $conn->query($scrubSQL);
      header("Location: /forum/thread/".$threadIDSafe);
    }
    if(isset($_GET['delete'])) {
      $deleteSQL = "UPDATE `forum_threads` SET `deleted`='yes' WHERE `id`='$threadIDSafe'";
      $delete = $conn->query($deleteSQL);
      header("Location: /forum/thread/".$threadIDSafe);
    }
    if(isset($_GET['lock'])) {
      $deleteSQL = "UPDATE `forum_threads` SET `locked`='yes' WHERE `id`='$threadIDSafe'";
      $delete = $conn->query($deleteSQL);
      header("Location: /forum/thread/".$threadIDSafe);
    }
    if(isset($_GET['pin'])) {
      $deleteSQL = "UPDATE `forum_threads` SET `pinned`='yes' WHERE `id`='$threadIDSafe'";
      $delete = $conn->query($deleteSQL);
      header("Location: /forum/thread/".$threadIDSafe);
    }
    if(isset($_GET['unlock'])) {
      $deleteSQL = "UPDATE `forum_threads` SET `locked`='no' WHERE `id`='$threadIDSafe'";
      $delete = $conn->query($deleteSQL);
      header("Location: /forum/thread/".$threadIDSafe);
    }
    if(isset($_GET['unpin'])) {
      $deleteSQL = "UPDATE `forum_threads` SET `pinned`='no' WHERE `id`='$threadIDSafe'";
      $delete = $conn->query($deleteSQL);
      header("Location: /forum/thread/".$threadIDSafe);
    }
  }
  
  $findThreadQuery = "SELECT * FROM `forum_threads` WHERE `id` = '$threadIDSafe'";
  $findThread = $conn->query($findThreadQuery);
  
  $sqlCount = "SELECT * FROM `forum_posts` WHERE `thread_id` = '$threadIDSafe'";
  $countQ = $conn->query($sqlCount);
  $count = $countQ->num_rows;
  
  $page = min(((int)($count/10)+1),$page);
  
  $limit = ($page-1)*10;
  $findReplyQuery = "SELECT * FROM `forum_posts` WHERE `thread_id` = '$threadIDSafe' ORDER BY `id` LIMIT $limit,10;";
  $findReply = $conn->query($findReplyQuery);
     
  $findViewsQuery = "SELECT * FROM `forum_threads` WHERE `id`='$threadIDSafe'";
  $findViews = $conn->query($findViewsQuery);
  $viewRow = $findViews->fetch_assoc();
  $views = $viewRow['views']+1;
  $addViewQuery = "UPDATE `forum_threads` SET `views`='$views' WHERE `id`='$threadIDSafe'";
  $addView = $conn->query($addViewQuery);
      
  if ( $findThread->num_rows > 0 ) {
      $threadRow = (object) $findThread->fetch_assoc();
      $boardID = $threadRow->{'board_id'};
      
      if($power <= 0 && $boardID <= 0) {header("Location /forum/");}
      
      // Finding Board
      
      $boradSQL = "SELECT * FROM `forum_boards` WHERE `id` = '$boardID'";
      $board = $conn->query($boradSQL);
      $boardRow = (object) $board->fetch_assoc();
  }
  
  function bbcode_to_html($bbtext){
    $bbtags = array(
      '[heading1]' => '<h1>','[/heading1]' => '</h1>',
      '[heading2]' => '<h2>','[/heading2]' => '</h2>',
      '[heading3]' => '<h3>','[/heading3]' => '</h3>',
      '[h1]' => '<h1>','[/h1]' => '</h1>',
      '[h2]' => '<h2>','[/h2]' => '</h2>',
      '[h3]' => '<h3>','[/h3]' => '</h3>',
  
      '[paragraph]' => '<p>','[/paragraph]' => '</p>',
      '[para]' => '<p>','[/para]' => '</p>',
      '[p]' => '<p>','[/p]' => '</p>',
      '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
      '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
      '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
      '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',
  
      '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
      '[italic]' => '<i>','[/italic]' => '</i>',
      '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
      '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
      '[i]' => '<i>','[/i]' => '</i>',
      '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
      '[s]' => '<s>','[/s]' => '</s>',
      '[break]' => '<br>',
      '[br]' => '<br>',
      '[newline]' => '<br>',
      '[nl]' => '<br>',
      
      '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
      '[ul]' => '<ul>','[/ul]' => '</ul>',
    
      '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
      '[ol]' => '<ol>','[/ol]' => '</ol>',
      '[list]' => '<li>','[/list]' => '</li>',
      '[li]' => '<li>','[/li]' => '</li>',
        
      '[*]' => '<li>','[/*]' => '</li>',
      '[code]' => '<pre>','[/code]' => '</pre>',
      '[quote]' => '<blockquote>','[/quote]' => '</blockquote>',
      '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
      '[pre]' => '<pre>','[/pre]' => '</pre>',  
      
      //Emojis
      //Created by Tech
      //I wouldn't brag about that, buddy - Luke
      
      ':)' => '<img src="/assets/emojis/smile.png"></img>',
      ':(' => '<img src="/assets/emojis/sad.png"></img>',
      ':P' => '<img src="/assets/emojis/tongue.png"></img>', ':p' => '<img src="/assets/emojis/tonuge.png"></img>',       
      ':*' => '<img src="/assets/emojis/kiss.png"></img>',    
      ':|' => '<img src="/assets/emojis/none.png"></img>',    
      ':^)' => '<img src="/assets/emojis/oops.png"></img>',   
      ':D' => '<img src="/assets/emojis/grin.png"></img>',


  // deleting links !!!
  'goatse.info' => '[ Link Removed ]',
  'pornhub.com' => '[ Link Removed ]',
    );
    
    $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);
  
    $bbextended = array(
      "/\[url](.*?)\[\/url]/i" => "<a style=\"color:#444\" href=\"$1\">$1</a>",
      "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a style=\"color:#444\" href=\"$1\">$2</a>",
      "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
      "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
      "/\[youtube\]([^[]*)\[\/youtube\]/i" => "<iframe src=\"https://youtube.com/embed/$1\" width=\"560\" height=\"315\"></iframe>",
    );
    
    /*
      "/\[img\]([^[]*)\[\/img\]/i" => "<img class=\"forumImage\" src=\"$1\" alt=\" \" />",
      "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
      "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
      "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",*/
  
    foreach($bbextended as $match=>$replacement){
      $bbtext = preg_replace($match, $replacement, $bbtext);
    }
    return $bbtext;
  }
  
?>
<!DOCTYPE html>
<html>

  <head>
  <title> <?php echo htmlentities ( $threadRow->{'title'} ); ?> - <?php echo $sitename; ?> </title>
  
  <meta charset="UTF-8">
  <meta name="description" content="<?php echo $threadRow->{'body'} ?>">
  <meta name="keywords" content="free,game">
 <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  
  <meta property="og:title" content="<?php echo $threadRow->{'title'} ?>" />
  <meta property="og:description" content="<?php echo $threadRow->{'body'} ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="/forum/thread?id=<?php echo $threadID ?>" />
  </head>

    
  <body>
  
    <div id="body">
    <?php
    if ( $findThread->num_rows > 0) {
      if($threadRow->{'deleted'} == 'no') {
      //$threadRow = (object) $findThread->fetch_assoc();
      $authorID = $threadRow->{'author_id'};
      
      // Finding Creator
      
      $findCreatorSQL = "SELECT * FROM `beta_users` WHERE `id` = '$authorID'";
      $findCreator = $conn->query($findCreatorSQL);
      $creatorRow = (object) $findCreator->fetch_assoc();
      
      //Find creator of thread
      $findCreatorThreadSQL = "SELECT * FROM `forum_posts` WHERE `id` = '$threadIDSafe'";
      $findCreatorThread = $conn->query($findCreatorThreadSQL);
      $creatorThreadRow = (object) $findCreatorThread->fetch_assoc();
    ?>
      <meta property="og:image" content="<?php echo '/avatar/render/avatars/'; ?><?php echo $creatorRow->{'id'};?><?php echo ".png?c=";?><?php echo $creatorRow->{'avatar_id'}; ?>" />
  <div class="main-holder grid">

<div class="col-10-12 push-1-12">
<?php include("bar.php"); ?>
<div class="forum-bar mb2 ellipsis">
<div class="inline mt2">

<a href="/forum/">Forum</a>
<i class="fa fa-angle-double-right" aria-hidden="true"></i>
<a href="/forum/board/<?php echo $boardID; ?>/">
<?php echo $boardRow->{'name'}; ?>
  </a>
<i class="fa fa-angle-double-right" aria-hidden="true"></i>
<a href="/forum/thread/<?php echo $threadID; ?>/">
<span class="very-bold">
<?php $threadTitle = htmlentities($threadRow->{'title'});
        echo $threadTitle;?>
</span>
</a>
</div>
<div class="push-right">
<a class="button small blue" href="/forum/create?id=<?php echo $boardID; ?>">CREATE</a>
</div>
</div>
<div class="card">
<div class="top blue">
<?php
          
          $threadTitle = htmlentities($threadRow->{'title'});
          $locked = $threadRow->{'locked'};
          if ( $threadRow->{'pinned'} == 'yes' ) {
            echo '<span class="thread-label blue">Pinned</span> ';}
          if ( $threadRow->{'locked'} == 'yes' ) {
            echo '<span class="thread-label blue">Locked</span> ';}
          echo $threadTitle;
          
        ?>

</div>
<div class="content">
        
      <?php
      // ok
      
         $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$authorID'";
          $postCount = $conn->query($postCountSQL);
          $posts = $postCount->num_rows;
          
          $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$authorID'";
          $threadCount = $conn->query($threadCountSQL);
          $threads = $threadCount->num_rows;
          
          $userPostCount = ($threads+$posts);
      
          $threadCreator = $creatorRow->{'id'};
          $bannerSQL = "SELECT * FROM `forum_banners` WHERE `user_id` = '$threadCreator'";
          $banner = $conn->query($bannerSQL);
          $bannerRow = (object) $banner->fetch_assoc();
        if($page == 1) {
          echo '<div class="thread-row" style="position:relative;">
<div class="overflow-auto">
<div class="col-3-12 center-text ellipsis">
<a href=/user/"'.$creatorRow->{'id'}.'">
<img src="/avatar/render/avatars/'.$creatorRow->{'id'}.'.png?c='.$creatorRow->{'avatar_id'}.'" style="width:150px;">
</a>
<br>
<a href="/user/'.$creatorRow->{'id'}.'">
'.$creatorRow->{'username'}.'
</a>
<br>
<span class="light-gray-text">
Joined '.$creatorRow->{'date'}.'
</span>
<br>
<span class="light-gray-text">
Posts '.$userPostCount.'
</span>
            ';
          if($creatorRow->{'power'} > '0'){
            echo '<br><span style="color: #bf0000;"><i class="fa fa-gavel" aria-hidden="true"></i> Administrator</span><br>';
          }
          echo'
</div>
<div class="col-9-12">
<div class="weight600 light-gray-text text-right mobile-center-text" style="text-align:right;">
'. $threadRow->{'date'} .'
</div>
<div class="p">
'.bbcode_to_html(nl2br(htmlentities($threadRow->{'body'}))).'
</div>
</div>
</div>';
          echo'<div class=" col-1-1  weight600 dark-grey-text forum-options" style="text-align:right;" data-post-id="2411477">';
          if($power >= 1) {
              echo '<a class="forum-reply mr4" href="/forum/thread/'.$threadID.'/&scrub">SCRUB</a>
              <a class="forum-reply mr4" href="/forum/thread/'.$threadID.'/&delete">DELETE</a>';
            // echo' <a class="forum-reply mr4" href="move?id='.$threadID.'">Move</a></span>';
          
          if($threadRow->{'locked'} == 'no') {
                echo '<a class="forum-reply mr4" href="/forum/thread/'.$threadID.'/&lock">LOCK</a>';
              } else {
                echo '<a class="forum-reply mr4" href="/forum/thread/'.$threadID.'/&unlock">UNLOCK</a>';
              }
            
          if($threadRow->{'pinned'} == 'no') {
                echo '<a class="forum-reply mr4" href="/forum/thread/'.$threadID.'/&pin">PIN</a>';
              } else {
                echo '<a class="forum-reply mr4" href="/forum/thread/'.$threadID.'/&unpin">UNPIN</a>';
              }
          }
      echo'


<a class="forum-reply mr4" href="/forum/reply/2411477/">REPLY</a>
<a class="report" href="/report/forumthread/2411477/">REPORT</a>
</div>
</div>
<hr>';
          
      
        ?>
        </div>

          <?php
        }
          ?>
          

      <?php
        while ($threadRow = $findReply->fetch_assoc()) {
          $authorID = $threadRow['author_id'];
          
          // Finding Creator
          $findCreatorSQL = "SELECT * FROM `beta_users` WHERE `id` = '$authorID'";
          $findCreator = $conn->query($findCreatorSQL);
          $creatorRow = (object) $findCreator->fetch_assoc();
          
                    
          echo '<div class="content">
            <div class="thread-row" style="position:relative;" id="post10357185">
<div class="overflow-auto">
<div class="col-3-12 center-text ellipsis">
<a href="/user/'.$creatorRow->{'id'}.'">
<img src="/avatar/render/avatars/'.$creatorRow->{'id'}.'.png?c='.$creatorRow->{'avatar_id'}.'" style="width:150px;">
</a>
<br>
<a href="/user/'.$creatorRow->{'id'}.'">
'.$creatorRow->{'username'}.'
</a>
<br>
<span class="light-gray-text">
Joined '.$creatorRow->{'date'}.'
</span>
<br>
<span class="light-gray-text">
Posts '.$userPostCount.'
</span>
             ';
          if($creatorRow->{'power'} > '0'){
            echo '<br><span style="color: #bf0000;"><i class="fa fa-gavel" aria-hidden="true"></i> Administrator</span><br>';
          }
          echo'
</div>
<div class="col-9-12">
<div class="weight600 light-gray-text mobile-center-text" style="text-align:right;">
'.$threadRow['date'].'
            </div>
<div class="p">
'.bbcode_to_html(nl2br(htmlentities($threadRow['body']))).'
</div>
</div>
</div>
<div class=" col-1-1  weight600 dark-grey-text forum-options" style="text-align:right;" data-post-id="10357185">
<a class="forum-reply mr4" href="/forum/reply/2411477/">REPLY</a>
<a class="report" href="/report/forumpost/10357185/">REPORT</a>
</div>
</div>
<hr>';
          $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$authorID'";
          $postCount = $conn->query($postCountSQL);
          $posts = $postCount->num_rows;
          
          $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$authorID'";
          $threadCount = $conn->query($threadCountSQL);
          $threads = $threadCount->num_rows;
          
          $userPostCount = ($threads+$posts);
          
          
              
          
          
        }
        
        
        ?>
        
      <?php
      echo '</div><div class="numButtonsHolder" style="margin-left:auto;margin-right:auto;margin-top:10px;">';
      
      if($count/10 > 1) {
        for($i = 0; $i < min($count/10,4); $i++)
        {
          echo '<a href="thread?id='.$threadIDSafe.'&page='.($i+1).'">'.($i+1).'</a> ';
        }
      }
      if($count/20 > 4) {
          echo '... <a href="board?id='.$threadIDSafe.'&page='.(round($count/10)+1).'">'.(round($count/10)+1).'</a> ';
      }
      
      echo '</div>';
      ?>
    <?php
      } else {
        echo '<div id="box" style="padding: 10px;tect-align:center;">
        Thread not found!
      </div>';
      }
    } else {
      ?>
      <div id="box" style="padding: 10px;tect-align:center;">
        Thread not found!
      </div>
      <?php
    }
    ?>
    </div>
<div class="center-text"><a class="button small blue" href="/forum/reply?id=<?php echo $threadID; ?>">REPLY</a></div>
            </div>
            </div>
      </div>


    <?php
    include("../SiT_3/footer.php");
    ?>
  </body>
  
</html>