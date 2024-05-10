<?php 

  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  if(!$loggedIn) {header('Location: index'); die();}

  $userID = $_SESSION['id'];
  $threadID = $_GET['id'];
  $threadIDSafe = mysqli_real_escape_string($conn, $threadID);
  
  $findThreadQuery = "SELECT * FROM `forum_threads` WHERE `id` = $threadIDSafe";
  $findThread = $conn->query($findThreadQuery);
  
  if ( $findThread->num_rows > 0 ) {
    $threadRow = (object) $findThread->fetch_assoc();
  } else {echo"<script>location.replace('/forum/');</script>";
    
    //header("Location: /forum/");
    }
    
  if ($threadRow->{'locked'} == 'yes' || $threadRow->{'deleted'} == 'yes') {echo"<script>location.replace('/forum/thread?id=$threadID');</script>";}
    //header("Location: /forum/thread?id=$threadID");}
  
        if(isset($_GET['quote'])) {
          $quoteID = mysqli_real_escape_string($conn, $_GET['quote']);
          $findReplyQuery = "SELECT * FROM `forum_posts` WHERE `id` = '$quoteID'";
          $findReply = $conn->query($findReplyQuery);
          $threadRowW = $findReply->fetch_assoc();
          
          $authorID = $threadRowW['author_id'];
        
          // Finding Creator
          $findCreatorSQL = "SELECT * FROM `beta_users` WHERE `id` = '$authorID'";
          $findCreator = $conn->query($findCreatorSQL);
          $creatorRowW = $findCreator->fetch_assoc();
            
            $quotetext = '[quote][i]Quote from [url=http://builderland.ct8.pl/user/'.$authorID.'/]'.$creatorRowW['username'].'[/url], '.$threadRowW['date'].'[/i]
            '.$threadRowW['body'].'[/quote]';
        }
  
  $error = array();
  if (isset($_POST['reply'])) {
    $lastPostSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$userID' ORDER BY `id` DESC";
    $lastPost = $conn->query($lastPostSQL);
    $lastPostRow = $lastPost->fetch_assoc();
    
    $lastThreadSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$userID' ORDER BY `id` DESC";
    $lastThread = $conn->query($lastThreadSQL);
    $lastThreadRow = $lastThread->fetch_assoc();
    
    $last = max(strtotime($lastPostRow['date']),strtotime($lastThreadRow['date']));
    
    
    if(time()-$last >= 30) {
        if(isset($quotetext)){
           $reply = mysqli_real_escape_string($conn,$quotetext.$_POST['reply']);    
        }else{
         $reply = mysqli_real_escape_string($conn,$_POST['reply']);   
        }
      if(strlen(str_replace(array("\r", "\n"), '',$reply)) >= 2 && strlen(str_replace(array("\r", "\n"), '',$reply)) <= 1000) {
        $sendMessageSQL = "INSERT INTO `forum_posts` (`id`, `author_id`, `thread_id`, `body`, `date`) VALUES (NULL, '$userID', '$threadIDSafe', '$reply', '$curDate')";
        $sendMessage = $conn->query($sendMessageSQL);
        $newPostID = $conn->insert_id;
        
        $time = time();
        $updateReplySQL = "UPDATE `forum_threads` SET `latest_post` = '$time' WHERE `id` = '$threadID'";
        $updateReply = $conn->query($updateReplySQL);
        
        $sqlCount = "SELECT * FROM `forum_posts` WHERE `thread_id` = '$threadID'";
        $countQ = $conn->query($sqlCount);
        $count = $countQ->num_rows;
        $page = (int)($count/10)+1;
        echo"<script>location.replace('/forum/thread?id=$threadID&page=$page#post$newPostID');</script>";
        //header("Location: /forum/thread?id=$threadID&page=$page#post$newPostID");
      } else {
        $error[] = "Reply must be between 2 and 1,000 characters";
      }
    } else {
      $error[] = "You are posting too fast";
    }
    
  }
  if ( $findThread->num_rows > 0 ) {
    $boardID = $threadRow->{'board_id'};
    
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
<!DOCTYPEhtml>
<html>
  <head>
    <title>Create Reply - <?php echo $sitename; ?></title>
  </head>
  
  <body>
<div class="main-holder grid">
      
        <?php
        if(!empty($error)) {
          echo '<div id="box" style="padding:10px;"><div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
          foreach($error as $errno) {
            echo $errno."<br>";
          } 
          echo '</div>';
        }
        ?>
      <p><a href="/forum">Forum</a> <i class="fa fa-angle-double-right" style="font-size:1rem;" aria-hidden="true"></i> <a href="/forum/board/<?php echo $boardRow->{'id'} ?>"><?php echo $boardRow->{'name'}; ?></a> <i class="fa fa-angle-double-right" style="font-size:1rem;" aria-hidden="true"></i> <a href="/forum/thread/<?php echo $threadRow->{'id'}; ?>"><?php echo fix($threadRow->{'title'}); ?></a></p>
<div class="card">
<div class="top blue">
<div class="col-7-15">Reply to <?php echo htmlentities($threadRow->{'title'}); ?></div></div>
<div class="content">


        <div>
            <?=bbcode_to_html(fix($quotetext))?>
        </div>
        <form action="" method="POST" id="createReply">
          <textarea name="reply" style="width: 1100px; height: 200px; margin: 10px 0px 0px; font-size: 16px; border: 1px solid black; resize: vertical;padding: 5px 0px 5px 5px;" placeholder="Body (max 3,000 characters)" id="rB"></textarea>
<div class="center-text">
          <input class="button small blue" type="submit" value="REPLY" style="margin:10px">
</div>
    </form>
</div>
    </div> </div></div> </div> 
  </body>
        <?php
      include("../SiT_3/footer.php");
      ?>
</html>