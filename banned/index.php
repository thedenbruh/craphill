<?php
include("../SiT_3/header.php");
$currentID = $_SESSION['id'];

$bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$currentID'";
  $banned = $conn->query($bannedSQL);
  if($banned->num_rows != 0) {
    $bannedRow = $banned->fetch_assoc();
      $banID = $bannedRow['id'];
      $currentDate = strtotime($curDate);
    $banEnd = strtotime($bannedRow['issued'])+($bannedRow['length']*60);
   if($bannedRow['length'] <= 0) {$title = "Warning";}
      elseif($bannedRow['length'] < 60) {$title = " ".$bannedRow['length']." minutes";}
      elseif($bannedRow['length'] >= 60) {$title = " ".round($bannedRow['length']/60)." hours";}
      elseif($bannedRow['length'] >= 1440) {$title = " ".round($bannedRow['length']/1440)." days";}
      elseif($bannedRow['length'] >= 43200) {$title = " ".round($bannedRow['length']/43200)." months";}
      elseif($bannedRow['length'] >= 525600) {$title = " ".round($bannedRow['length']/525600)." years";}
      elseif($bannedRow['length'] >= 36792000) {$title = "Terminated";}
    
      /*echo '<head>
          <title>Suspended - Planet Hill</title>
        <style>
      .suspended-header-icon i {
        font-size: 18px;
        margin-right: 10px;
        vertical-align: middle;
      }
      
      .suspended-divider {
        margin: 15px 0;
        background: #373840;
        width: 100%;
        height: 1px;
      }
      
      .suspended-content {
        background: #383945;
        box-shadow: 0 1px 1px #121721;
        padding: 15px;
        border-radius: 5px;
      }
    </style>
        </head>
        <body>
        
        <div class="grid-x grid-margin-x">
      <div class="large-8 large-offset-2 cell">
        <div class="container-header md-padding">
          <strong><span class="suspended-header-icon"><i class="material-icons">gavel</i></span><span>Suspension issued against account</span></strong>
        </div>
        <div class="container border-wh md-padding">
          <div>A suspension has been issued against your account for violating our community guidelines and/or terms and conditions.</div>
          <div class="suspended-divider"></div>
                    <div class="push-25"></div>

          <div class="grid-x grid-margin-x">
            <div class="large-4 cell"><p>Length: <strong>'.$title.'</strong></p></div>
            <div class="large-4 cell"><p>Reason provided: <strong>' . $bannedRow['admin_note'] . '</strong></p></div>
            <div class="large-4 cell"><p>Issued on: <strong>' . gmdate('m/d/Y',strtotime($bannedRow['issued'])) . '</strong></p></div>
          </div>

                    <div class="suspended-divider"></div>
                    <div class="push-25"></div>
                    </div>';*/
        echo'<title>Banned - '.$sitename.'</title>
          <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top red">
Your account has been suspended
</div>
<div class="content">
<span class="dark-gray-text sbold">We have deemed that your account has violated our Terms of Service, and as such a punishment has been applied to your account. Further incompetence or violations to our Terms of Service will result in a termination of your account.</span>
<div style="padding-left:20px;margin-top:20px;">
<div class="block" style="margin-bottom:20px;">
<b class="dark-gray-text">Ban Length:</b>
<span class="light-gray-text">
'.$title.'
</span>
</div>
<div class="block" style="margin-bottom:20px;">
<b class="dark-gray-text">Ban Reason:</b>
<span class="light-gray-text">' . $bannedRow['reason'] . '</span>
</div>
</div>
<div style="padding-left:20px;margin-top:20px;">
<div class="block" style="margin-bottom:20px;">
<b class="dark-gray-text">Moderator Note:</b>
<span class="light-gray-text">' . $bannedRow['admin_note'] . '</span>
</div>
</div>
<span class="dark-gray-text" style="font-size:16px;">Please make sure that you have read our <a class="darker-gray-text bold" href="/terms" target="_blank">Terms of Service</a> before returning to make sure you and others have the best experience on '.$sitename.'.</span>
<hr>
<div style="margin-bottom:10px;">
<span class="dark-gray-text" style="font-size:16px;">If you wish to appeal, make a ticket on our <a class="darkest-gray-text" href="https://discord.gg/My9mjUD5jq"> Discord Server</a>.';
    if($currentDate >= $banEnd) {
        if(isset($_POST['unban'])) {
          $unbanSQL = "UPDATE `moderation` SET `active`='no' WHERE `id`='$banID'";
          $unban = $conn->query($unbanSQL);
          echo'<script>location.reload();</script>';
          //header("Refresh:0");
        }
        echo '</div>
        <form action="" method="POST">
          <input type="submit" class="button blue upload-submit" name="unban" value="Reactivate Account">
        </form>';
         } else {
        
      }
echo'</div>
</div>
</div>
</div>
  </div>';

        
        
        include("../SiT_3/footer.php");
      echo '
             
            </div>
          </div>
        </body>';

      exit;
    
  
  
  }

        



echo'</div>
</div>
  
          ';
      
      
     
  
?>
<?php
  include("../SiT_3/footer.php");
  ?>