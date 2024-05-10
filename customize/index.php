<?php
    include("../SiT_3/config.php");
    include("../SiT_3/head.php");
    
    if(!$loggedIn) {header("Location: index"); die();}
    
    $userId = $_SESSION['id'];
    
    
    if (isset($_POST['Purge'])) {
      $purgeSQL = "UPDATE `avatar` SET `shirt`='0', `pants`='0', `tshirt`='0', `hat1`='0', `cache`='0' WHERE `user_id`='$userId'";
      $purge = $conn->query($purgeSQL);
      header("Location: /customize/?regen");
    }
  
  $findUserAvatarSQL = "SELECT * FROM `avatar` WHERE `user_id` = '$userId'";
  $findUserAvatar = $conn->query($findUserAvatarSQL);
  $userAvatar = (object) $findUserAvatar->fetch_assoc();
  
  $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$userId'";
  $userResult = $conn->query($sqlUser);
  $userRow=$userResult->fetch_assoc();
  
  /*
  First Color = Head Color
  Second Color = Torso Color
  Third Color = Right Arm Color
  Fourth Color = Left Arm Color
  Fifth Color = Right Leg Color
  Sixth Color = Left Leg Color
  */
  
  if(isset($_POST['radio'])){
      $wantedPose = $_POST['radio'];
      
      if($wantedPose == "0"){
        $equipPOSESQL = "UPDATE `avatar` SET `pose` = 0 WHERE `user_id` = '". $_SESSION['id'] ."'";
        $equipPOSE = $conn->query($equipPOSESQL);
      }else if($wantedPose == "1"){
        $equipPOSESQL = "UPDATE `avatar` SET `pose` = 1 WHERE `user_id` = '". $_SESSION['id'] ."'";
        $equipPOSE = $conn->query($equipPOSESQL);
      }else{
          echo "<div style='border: 1px solid #b57500;background-color: #ffa500;color: #fff;text-align:center;padding: 3px;'>
          Invalid Pose!
      </div>";
      }
  }
  
  if (isset($_POST['color'])) {
    
    if (isset($_GET['part'])) {
      
      $partArray = array(
        "head",
        "torso",
        "rightArm",
        "leftArm",
        "rightLeg",
        "leftLeg"
      );
      
      //echo $_GET['part'];
      
      if (in_array( $_GET['part'], $partArray )) {
        //echo'yay';
      } else {
        header('Location: /customize/?error=ip');
        die();
      }
      
      $colorArray = array (
        "f3b700",
        "d34a05",
        "c60000",
        "c81879",
        "1c4399",  
        "3292d3",  
        "c2dc7f",      
        "1d6a19",
        "85ad00",
        "441209",  
        "c15b2c",  
        "f1f1f1",  
        "fcfcc9",  
        "fcff81",  
        "e087b6",
        "815ea6",
        "7eb2e6",  
        "39b2ca",
        "b9ded1",
        "caad64",
        "eab372",
        "ddddd0",
        "e58700",
        "810058",
        "ac93c6",
        "4578bb",
        "4f607a",
        "507051",
        "76603f",
        "ffffff",
        "897f7e",
        "7b8183",
        "650013",  
        "220965",
        "3b1e81",  
        "586e85",
        "248233",
        "6e703f",
        "936941",  
        "8d290b",  
        "3b3f44",  
        "936b1c",  
        "0a1b32",
        "103a21",
        "210c07",
        "000000",
        "37302c",
        "3b3f44",  
        "eeec9f",  
        "e1a479",
        "de9c93",
        "d97b87",
        "e4b9d4",
        "b9b6d7",  
        "cbe2ec",  
        "9ec6eb",  
        "d7e3f3",
        "a2c8a5",
        "bff59e",
        "eeea98",  
        "bdb4b0",  
        "e9eaee",  
        "9ec6eb"  
      );
      
      if (in_array( $_POST['color'], $colorArray )) {
        
        $partReplace = array(
          "head" => "head_color",
          "torso" => "torso_color",
          "rightArm" => "right_arm_color",
          "leftArm" => "left_arm_color",
          "rightLeg" => "right_leg_color",
          "leftLeg" => "left_leg_color"
        );
        
        $part = strtr($_GET['part'], $partReplace);
        $color = mysqli_real_escape_string($conn, $_POST['color']);
        
        $updateColorSQL = "UPDATE `avatar` SET `$part` = '$color' WHERE `user_id` = '".$_SESSION['id']."'";
        $updateColor = $conn->query($updateColorSQL);
        if ($updateColor) {
          //header("Location: /customize/?error=suc&regen");
          die("<script>window.location = '/customize/?error=suc&regen';</script>");
          //die();
        } else {
          //header("Location: /customize/?error=ue");
          die("<script>window.location = '/customize/?error=ue';</script>");
          //die();
        }
        
      } else {
        //header('Location: /customize/?error=ihc');
        die("<script>window.location = '/customize/?error=ihc';</script>");
        //die();
      }
    }
  }
  
  if (isset($_POST['remove'])) {
    
    $hatNum = $_POST['remove'];

    $hatThings = array(
    "h1",
    "h2",
    "h3",
    "h4",
    "h5",
    "s",
    "tool",
    "p",
    "t",
    "f",
    "h"
    );
    
    if (in_array($hatNum, $hatThings)) {
      
      $hatTypes = array(
      "h1" => "hat1",
      "h2" => "hat2",
      "h3" => "hat3",
      "h4" => "hat4",
      "h5" => "hat5",
      "s" => "shirt",
      "tool" => "tool",
      "p" => "pants",
      "t" => "tshirt",
      "f" => "face",
      "h" => "head"
      );
      
      $hatNumber = strtr($hatNum, $hatTypes);
      
      $removeHatSQL = "UPDATE `avatar` SET `$hatNumber` = '0' WHERE `user_id` = '".$_SESSION['id']."'";
      $removeHat = $conn->query($removeHatSQL);
      
      if (!$removeHat) {
        die("luke did 9/11");
        header("Location: ?msg=ue");
        die();
      } else {
      
      header("Location: ?msg=srh");
      die();
      
      }
    }
    
  }
  
  if (isset($_POST['wear'])) {
    
    $userCurrentItems = array(
    $userAvatar->{'hat1'},
    $userAvatar->{'hat2'},
    $userAvatar->{'hat3'},
    $userAvatar->{'hat4'},
    $userAvatar->{'hat5'},
    $userAvatar->{'shirt'},
    $userAvatar->{'tool'},
    $userAvatar->{'pants'},
    $userAvatar->{'tshirt'},
    $userAvatar->{'head'}
    );
    
    $itemID = $_POST['wear'];
    $itemIDSafe = mysqli_real_escape_string($conn , $itemID); // Make it safe to query
    
    if (in_array($itemID, $userCurrentItems)) {
      //header("Location: ?msg=ue");
      die("<script>window.location = '?msg=ue';</script>");
      //die();
    }
    
    $itemExistsSQL = "SELECT * FROM `shop_items` WHERE `id` = '$itemIDSafe'";
    $itemExists = $conn->query($itemExistsSQL);
  
    if ($itemExists->num_rows > 0) {
      
      $userHasItemSQL = "SELECT * FROM `crate` WHERE `item_id` = '$itemIDSafe' AND `own`='yes' AND `user_id` = '" . $_SESSION['id'] ."'";
      $userHasItem = $conn->query($userHasItemSQL);

      if ($userHasItem->num_rows > 0) {
        
        $itemRow = (object) $itemExists->fetch_assoc();
        if ($itemRow->{'approved'} !== "yes") {
          die("no u");
        }
        $itemType = $itemRow->{'type'};
        if ($itemType == "hat") {
          
          if ($userAvatar->{'hat1'} == 0) {
          $equipHatSQL = "UPDATE `avatar` SET `hat1` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          } elseif ($userAvatar->{'hat2'} == 0) {
          $equipHatSQL = "UPDATE `avatar` SET `hat2` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          } elseif ($userAvatar->{'hat3'} == 0) {
          $equipHatSQL = "UPDATE `avatar` SET `hat3` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          } elseif ($userAvatar->{'hat4'} == 0) {
          $equipHatSQL = "UPDATE `avatar` SET `hat4` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          } elseif ($userAvatar->{'hat5'} == 0) {
          $equipHatSQL = "UPDATE `avatar` SET `hat5` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          } else {
          $equipHatSQL = "UPDATE `avatar` SET `hat1` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          }
          
          $equipHat = $conn->query($equipHatSQL);
          
          if (!$equipHat) {
          //header("Location: ?msg=ue2");
          die('error');
          } else {
          //header("Location: ?msg=swh");
          die("<script>window.location = '?msg=swh';</script>");
          //die();
          }
        } elseif ($itemType == "shirt") {
          
          $equipShirtSQL = "UPDATE `avatar` SET `shirt` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          
          $equipHat = $conn->query($equipShirtSQL);
        } elseif ($itemType == "pants") {
          
          $equipPantsSQL = "UPDATE `avatar` SET `pants` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          
          $equipPants = $conn->query($equipPantsSQL);
          
        } elseif ($itemType == "tool") {
          
          $equipToolSQL = "UPDATE `avatar` SET `tool` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          
          $equipTool = $conn->query($equipToolSQL);
          
          if (!$equipHat) {
            //header("Location: ?msg=ue2");
            die("<script>window.location = '?msg=ue2';</script>");
            //die();
          } else {
            //header("Location: ?msg=swh");
            die("<script>window.location = '?msg=swh';</script>");
            //die();
          }
            
        }  elseif ($itemType == "tshirt") {
          
          $equipTShirtSQL = "UPDATE `avatar` SET `tshirt` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          
          $equipTShirt = $conn->query($equipTShirtSQL);
        } elseif ($itemType == "face") {
          $equipFaceSQL = "UPDATE `avatar` SET `face` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          
          $equipFace = $conn->query($equipFaceSQL);
        }  elseif ($itemType == "head") {
          $equipheadSQL = "UPDATE `avatar` SET `head` = '$itemIDSafe' WHERE `user_id` = '". $_SESSION['id'] ."'";
          
          $equiphead = $conn->query($equipheadSQL);
        }
        
      } else {
        header("Location: ?msg=ue3");
      die();
      }
      
    } else {
      
    header("Location: ?msg=ue1");
    die();
    
    }
    
  }
  

  
    ?>
<title>Customize - <?php echo $sitename; ?></title>
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<?php if($showalr == true){ ?>
<div class="alert warning">
<?=$alert?>
</div>
<?php } ?>
</div>
<div class="col-10-12 push-1-12">
<div class="col-5-12">
    <div class="card">
        <div class="top blue">Avatar</div>
        <div class="content text-center">
            <div id="avatar">
                <img src="/avatar/render/avatars/<?php echo $userRow['id']; ?>.png?c=<?php echo $userRow['avatar_id']; ?>" style="display:block;margin:0 auto;">
            </div>
            <hr>
            <button onClick="avatarRedraw()" class="button blue">Render</button>
        </div>
    </div>
     
     <div class="card">
        <div class="top blue">Colors</div>
        <div class="content">
          
            <!--<h3 id="currently-editing" style="margin-left:10px;">Part Editing: Head</h3>-->
          <h3>Part Editing: <span style="margin-left:10px;" id="ce">Head</span></h3>
            <div style="display:block;margin:0 auto;">
                <div style="display:flex;flex-wrap:wrap;justify-content:center;">
                    <form action="?regen&part=head" method="POST" id="colors">
        
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #f3b700; !important;margin:3px;border-radius:5px;" name="color" value="f3b700"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #d34a05; !important;margin:3px;border-radius:5px;" name="color" value="d34a05"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #c60000; !important;margin:3px;border-radius:5px;" name="color" value="c60000"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #c81879; !important;margin:3px;border-radius:5px;" name="color" value="c81879"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #1c4399; !important;margin:3px;border-radius:5px;" name="color" value="1c4399"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #3292d3; !important;margin:3px;border-radius:5px;" name="color" value="3292d3"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #c2dc7f; !important;margin:3px;border-radius:5px;" name="color" value="c2dc7f"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #1d6a19; !important;margin:3px;border-radius:5px;" name="color" value="1d6a19"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #85ad00; !important;margin:3px;border-radius:5px;" name="color" value="85ad00"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #441209; !important;margin:3px;border-radius:5px;" name="color" value="441209"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #c15b2c; !important;margin:3px;border-radius:5px;" name="color" value="c15b2c"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #f1f1f1; !important;margin:3px;border-radius:5px;" name="color" value="f1f1f1"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #fcfcc9; !important;margin:3px;border-radius:5px;" name="color" value="fcfcc9"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #fcff81; !important;margin:3px;border-radius:5px;" name="color" value="fcff81"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #e087b6; !important;margin:3px;border-radius:5px;" name="color" value="e087b6"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #815ea6; !important;margin:3px;border-radius:5px;" name="color" value="815ea6"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #7eb2e6; !important;margin:3px;border-radius:5px;" name="color" value="7eb2e6"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #39b2ca; !important;margin:3px;border-radius:5px;" name="color" value="39b2ca"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #b9ded1; !important;margin:3px;border-radius:5px;" name="color" value="b9ded1"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #caad64; !important;margin:3px;border-radius:5px;" name="color" value="caad64"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #eab372; !important;margin:3px;border-radius:5px;" name="color" value="eab372"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #ddddd0; !important;margin:3px;border-radius:5px;" name="color" value="ddddd0"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #e58700; !important;margin:3px;border-radius:5px;" name="color" value="e58700"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #810058; !important;margin:3px;border-radius:5px;" name="color" value="810058"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #ac93c6; !important;margin:3px;border-radius:5px;" name="color" value="ac93c6"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #4578bb; !important;margin:3px;border-radius:5px;" name="color" value="4578bb"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #4f607a; !important;margin:3px;border-radius:5px;" name="color" value="4f607a"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #507051; !important;margin:3px;border-radius:5px;" name="color" value="507051"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #76603f; !important;margin:3px;border-radius:5px;" name="color" value="76603f"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #ffffff; !important;margin:3px;border-radius:5px;" name="color" value="ffffff"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #897f7e; !important;margin:3px;border-radius:5px;" name="color" value="897f7e"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #7b8183; !important;margin:3px;border-radius:5px;" name="color" value="7b8183"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #650013; !important;margin:3px;border-radius:5px;" name="color" value="650013"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #220965; !important;margin:3px;border-radius:5px;" name="color" value="220965"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #3b1e81; !important;margin:3px;border-radius:5px;" name="color" value="3b1e81"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #586e85; !important;margin:3px;border-radius:5px;" name="color" value="586e85"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #248233; !important;margin:3px;border-radius:5px;" name="color" value="248233"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #6e703f; !important;margin:3px;border-radius:5px;" name="color" value="6e703f"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #936941; !important;margin:3px;border-radius:5px;" name="color" value="936941"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #8d290b; !important;margin:3px;border-radius:5px;" name="color" value="8d290b"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #3b3f44; !important;margin:3px;border-radius:5px;" name="color" value="3b3f44"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #936b1c; !important;margin:3px;border-radius:5px;" name="color" value="936b1c"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #0a1b32; !important;margin:3px;border-radius:5px;" name="color" value="0a1b32"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #103a21; !important;margin:3px;border-radius:5px;" name="color" value="103a21"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #210c07; !important;margin:3px;border-radius:5px;" name="color" value="210c07"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #000000; !important;margin:3px;border-radius:5px;" name="color" value="000000"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #37302c; !important;margin:3px;border-radius:5px;" name="color" value="37302c"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #3b3f44; !important;margin:3px;border-radius:5px;" name="color" value="3b3f44"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #eeec9f; !important;margin:3px;border-radius:5px;" name="color" value="eeec9f"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #e1a479; !important;margin:3px;border-radius:5px;" name="color" value="e1a479"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #de9c93; !important;margin:3px;border-radius:5px;" name="color" value="de9c93"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #d97b87; !important;margin:3px;border-radius:5px;" name="color" value="d97b87"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #e4b9d4; !important;margin:3px;border-radius:5px;" name="color" value="e4b9d4"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #b9b6d7; !important;margin:3px;border-radius:5px;" name="color" value="b9b6d7"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #cbe2ec; !important;margin:3px;border-radius:5px;" name="color" value="cbe2ec"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #9ec6eb; !important;margin:3px;border-radius:5px;" name="color" value="9ec6eb"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #d7e3f3; !important;margin:3px;border-radius:5px;" name="color" value="d7e3f3"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #a2c8a5; !important;margin:3px;border-radius:5px;" name="color" value="a2c8a5"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #bff59e; !important;margin:3px;border-radius:5px;" name="color" value="bff59e"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #eeea98; !important;margin:3px;border-radius:5px;" name="color" value="eeea98"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #bdb4b0; !important;margin:3px;border-radius:5px;" name="color" value="bdb4b0"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #e9eaee; !important;margin:3px;border-radius:5px;" name="color" value="e9eaee"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #9ec6eb; !important;margin:3px;border-radius:5px;" name="color" value="9ec6eb"></button>
              
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #d39f99; !important;margin:3px;border-radius:5px;" name="color" value="d39f99"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #e5e5c9; !important;margin:3px;border-radius:5px;" name="color" value="e5e5c9"></button>
              <button class="colorPallete"  style="height:42px;width:42px!important;background: #5aaeae; !important;margin:3px;border-radius:5px;" name="color" value="5aaeae"></button>
        </form>
                    <!--<button onclick="setBodyColor(<?=++$clrID?>)" style="height:42px;width:42px!important;background:#<?=$color?>!important;margin:3px;border-radius:5px;"></button>-->
                    
                </div>
            </div>
        </div>
    </div>
</div>
  
<div class="col-7-12">
  <div class="card">
        <div class="top blue">Crate</div>
    
        <div class="content">
         <div style=" color: black; font-weight: 600; padding-bottom: 15px;text-align:center;">                        <span><a class="getPageClicker" onclick="getPage('hat', 0)" style=" color: black; font-weight: 600;">Hats</a></span>
                                  <span>|</span> <span><a class="getPageClicker" onclick="getPage('head', 0)" style=" color: black; font-weight: 600;">Heads</a></span>

                      <span>|</span> <span><a class="getPageClicker" onclick="getPage('face', 0)" style=" color: black; font-weight: 600;">Faces</a></span>
          <span>|</span> <span><a class="getPageClicker" onclick="getPage('tool', 0)" style=" color: black; font-weight: 600;">Tools</a></span>
          <span>|</span> <span><a class="getPageClicker" onclick="getPage('shirt', 0)" style=" color: black; font-weight: 600;">Shirts</a></span>
          <span>|</span> <span><a class="getPageClicker" onclick="getPage('pants', 0)" style=" color: black; font-weight: 600;">Pants</a></span>
          <span>|</span> <span><a class="getPageClicker" onclick="getPage('tshirt', 0)" style=" color: black; font-weight: 600;">T-Shirts</a></span>
          <span>|</span> <span><a href="/shop/upload/" style=" color: black; font-weight: 600;">Create</a></span><br>
                    </div>
          <div id="inventory"></div>
            
        </div>
    </div>
  <div class="card">
        <div class="top blue">Wearing</div>
        <div class="content" id="wearing">
            
        </div>
    </div>
    <div class="card">
        <div class="top blue">Body Parts</div>
        <div class="content">
           <div id="upper-half" style="margin:0 auto;width:max-content;">
                        <button class="avatar-body-part" onclick="headColor()" style="background-color: #<?php echo $userAvatar->{'head_color'}; ?>;padding:25px;margin-top:-1px;" data-part="head"></button>
                    </div>
                    <div id="middle-half" style="margin:0 auto;width:max-content;">
                        <button class="avatar-body-part" onclick="lArmColor()" style="background-color: #<?php echo $userAvatar->{'left_arm_color'}; ?>;padding:50px;padding-right:0px;" data-part="left_arm"></button>
                        <button class="avatar-body-part" onclick="torsoColor()" style="background-color: #<?php echo $userAvatar->{'torso_color'}; ?>;padding:50px;" data-part="torso"></button>
                        <button class="avatar-body-part" onclick="rArmColor()" style="background-color: #<?php echo $userAvatar->{'right_arm_color'}; ?>;padding:50px;padding-right:0px;" data-part="right_arm"></button>
                    </div>
                    <div>
                      <div id="middle-half" style="margin:0 auto;width:max-content;">
                        <button class="avatar-body-part" onclick="lLegColor()" style="background-color: #<?php echo $userAvatar->{'left_leg_color'}; ?>;padding:50px;padding-right:0px;padding-left:47px;" data-part="left_leg"></button>
                        <button class="avatar-body-part" onclick="rLegColor()" style="background-color: #<?php echo $userAvatar->{'right_leg_color'}; ?>;padding:50px;padding-right:0px;padding-left:47px;" data-part="right_leg"></button>
                    </div>
                </div>
      
      <!--<div id="upper-half" style="margin:0 auto;width:max-content;height:45px!important;">
    <button style="height:45px;width:45px!important;background:#<?=$avatar->head_color?>!important;margin:0!important;border-radius:10px 10px 0px 0px;padding:0;" onclick="setBodyPart('Head')"></button></div>
<div id="middle-half" style="margin:0 auto;width:max-content;">
    <button style="height:84px;width:42px!important;background:#<?=$avatar->left_arm_color?>!important;margin:0!important;border-radius:10px 0px 0px 10px;" onclick="setBodyPart('Left Arm')"></button><button style="height:84px;width:84px!important;background:#<?=$avatar->torso_color?>!important;margin:0!important;" onclick="setBodyPart('Torso')"></button><button style="height:84px;width:42px!important;background:#<?=$avatar->right_arm_color?>!important;margin:0!important;border-radius:0px 10px 10px 0px;" onclick="setBodyPart('Right Arm')"></button>
</div>
<div id="middle-half" style="margin:0 auto;width:max-content;">
    <button style="height:84px;width:42px!important;background:#<?=$avatar->left_leg_color?>!important;margin:0!important;border-radius:0px 0px 0px 10px;" onclick="setBodyPart('Left Leg')"></button><button style="height:84px;width:42px!important;background:#<?=$avatar->right_leg_color?>!important;margin:0!important;border-radius:0px 0px 10px 0px;" onclick="setBodyPart('Right Leg')"></button>
</div>-->
            
        </div>
    </div>
</div>
</div>
</div>
  <script src="javascript/color.js">
  </script>
  <script>
  window.onload = function() {
    getWearing();
    getPage('hat',0);
    <?php if(isset($_GET['regen'])) {echo 'avatarRedraw();';} ?>
  };
  
    function avatarRedraw() {
    document.getElementById("avatar").innerHTML = '<iframe style="width:235px;height:280px;border:0px;" src="/avatar/render/<?php if ($_SESSION['id'] == 4) { echo 'isaiah'; } ?>"></iframe>';
    };
    
    function wear(item_id) {
      $.post("", {wear: item_id}, function(result){
      avatarRedraw();
      getWearing();
    });
      
    };
    
    function remove(item_id2) {
      $.post("", {remove: item_id2}, function(result){
      avatarRedraw();
      getWearing();
    });
      
    };
  
  function getWearing() {
    $("#wearing").load("/customize/currently");
  };
  
  function getPage(type, page) {
    $("#inventory").load("/customize/inventory?type="+type+"&page="+page);
  };
  </script>
<?php
      include('../SiT_3/footer.php');
    ?>