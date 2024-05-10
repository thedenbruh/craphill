<?php
include('../SiT_3/header.php');
$error = array();
if(!$loggedIn) {header("Location: ../"); die();}

//update desc

  $userID = $userRow->{'id'};
  
  if(isset($_POST['newBirth'])) {
    $birth_year = mysqli_real_escape_string($conn,intval($_POST['year']));
    $birth_month = mysqli_real_escape_string($conn,intval($_POST['month']));
    if (date('Y')-$birth_year >= 1 && date('Y')-$birth_year <= 124) {
      $birth_date = $birth_year."-".$birth_month."-01";
      $updateDescSQL = "UPDATE `beta_users` SET `birth` = '$birth_date' WHERE `id` = '$userID'";
      $updateDesc = $conn->query($updateDescSQL);
      header("Location: index");
    } else {
      $error[] = "You must be between 1 and 124 years old to play Data Hill.";
    }
  }
  
  if (isset($_POST['desc'])) {
  $newDesc = mysqli_real_escape_string($conn,$_POST['desc']);
  $userID = $userRow->{'id'};
  $updateDescSQL = "UPDATE `beta_users` SET `description` = '$newDesc' WHERE `id` = '$userID'";
  $updateDesc = $conn->query($updateDescSQL);  
  }




//i am lazy to comment that, but the thing down here actually changes your username

//goddamn skidder idiots removed this... well that's their fault anyway

  //old theme table update
  if (isset($_POST['oldtheme'])) {
  $userID = $userRow->{'id'};
  $updateOldThemeSQL = "UPDATE `themes` SET `old-theme` = 'yes' WHERE `id` = '$userID'";
  $updateOldTheme = $conn->query($updateOldThemeSQL);  
  }

  if(isset($_POST['changePass'])) {
    
    $curPass = $_POST['curPass'];
    
    $newP1 = $_POST['newPass'];
    $newP2 = $_POST['newPassConfirm'];
    
    if (password_verify($curPass, $userRow->{'password'}) && $newP1 == $newP2) {
      
      $newPass = password_hash($_POST['newPass'], PASSWORD_BCRYPT);
      
      $changePassSQL = "UPDATE `beta_users` SET `password` = '".$newPass."' WHERE `id` = '".$_SESSION['id']."'";
      $changePass = $conn->query($changePassSQL);
      
      if ($changePass) {
        header("Location: ?msg=pc");
        //echo"<script>location.replace('?msg=pc');</script>";
      } else {
        header("Location: ?msg=ue");
        //echo"<script>location.replace('?msg=ue');</script>";
      }
      
    } else {
      //echo"<script>location.replace('?msg=ip');</script>";
      header('Location: ?msg=ip');  
      die();
      
    }
    
  }
  
  if(isset($_POST['changeTheme'])) {
    
    $theme = $_POST['theme'];
    
    if ($theme != 0) {
      if (!intval($theme) || $theme < -1 || $theme > 7) {
        die("Invalid Theme");
      }
    }
    $theme = mysqli_real_escape_string($conn , $theme); // just incase check fails
    
    $changeThemeSQL = "UPDATE `beta_users` SET `theme` = '$theme' WHERE `id` = '" . $_SESSION['id'] . "'";
    $changeThemeQuery = $conn->query($changeThemeSQL);
    
    if($changeThemeQuery) {
      header("Location: /settings/");
      //echo"<script>location.replace('/settings/');</script>";
      die();
    }
    
  }
  
  if(isset($_POST['sendEmail'])) {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailSQL = "INSERT INTO `emails` (`id`, `user_id`, `email`, `verified`, `date`) VALUES (NULL, '$userID', '$email', 'no', CURRENT_TIMESTAMP)";
      $emailQ = $conn->query($emailSQL);
    }
    header("Location: index");
  }
  if (isset($_POST['username'])) {
  $username = str_replace(PHP_EOL, '', mysqli_real_escape_string($conn,$_POST['username']));
    if(substr($username,-1) == " " || substr($username,0,1) == " ") {$error[] = "You cannot include a space at the beginning or end of your username.";}
    
    //If their username is less than 3 characters or not alnum
    $alnumUsername = str_replace(array('-','_','.',' '), '', $username);
    
    if(strlen($username) < 3 || strlen($username) > 26 || $username != ctype_alnum($alnumUsername)) {
      $error[] = 'Username must be 3-26 betanumeric characters (including [ , ., -, _]).';
    }
    
    if(strpos($username, '  ') !== false || strpos($username, '..') !== false || strpos($username, '--') !== false || strpos($username, '__') !== false) {
      $error[] = 'Spaces, periods, hyphens and underscores must be separated.';
    }

    $usernameL = strtolower(mysqli_real_escape_string($conn, $username));
    
    $checkUsernameSQL = "SELECT * FROM `m33072_goodhill`.`beta_users` WHERE `beta_users`.`usernameL` = '$usernameL'";
    $checkUsername = $conn->query($checkUsernameSQL);
    
    if ($checkUsername->num_rows > 0) {
      $error[] = 'Username taken.';
    }
    
    
    
if($userRow->{'bucks'} >= 250) {
  $newMoney = $userRow->{'bucks'}-250;
if(empty($error)) {header("Location: /settings/");
  $userID = $userRow->{'id'};
  $updateUserSQL = "UPDATE `beta_users` SET `username` = '$username', `usernameL` = '$usernameL', `bucks` = '$newMoney' WHERE `id` = '$userID'";
//lmao they made it to come back
  $updateUser = $conn->query($updateUserSQL);
  }
    }
  }
?>

<!DOCTYPE html>
<?php
include('../SiT_3/alert.php');
  ?>
  <head>
    <title>Settings - <?php echo $sitename; ?></title>
  </head>
  <body>
    
        

            <div class="main-holder grid">
<div class="col-10-12 push-1-12">
</div>
<div class="col-10-12 push-1-12">

<vue-comp id="settings-v" data-v-app=""><div class="settings"><div class="card"><div class="blue top">Settings</div><div class="content"><div>

                    <span class="dark-gray-text very-bold block">Information</span>
                    <div class="block">
                        <span class="dark-gray-text" style="padding-right:5px;">Username:</span>
                        <span class="light-gray-text"><?php echo $userRow->{'username'}; ?></span>
                        <i class="f-right light-gray-text far fa-edit" style="cursor:pointer;" data-modal-open="username"></i>
                    </div>
  
                    <div class="block">
                        <span class="dark-gray-text" style="padding-right:5px;">Password:</span>
                        <span class="light-gray-text">*********</span>
                        <i class="f-right light-gray-text far fa-edit" style="cursor:pointer;" data-modal-open="password"></i>
                    </div>
                    <div class="block">
                        <span class="dark-gray-text" style="padding-right:5px;">Email:</span>
                        <span class="light-gray-text"><?php $emailSQL = "SELECT * FROM `emails` WHERE `user_id` = '$userID' ORDER BY `id` DESC";
          $emailQ = $conn->query($emailSQL);
          if($emailQ->num_rows > 0) {
            $emailRow = $emailQ->fetch_assoc();
            $email = $emailRow['email'];
            $email = $email[0].$email[1].preg_replace('/[^@]+@([^\s]+)/', '***@$1', $email);
            echo $email;
          } else {
            echo 'You have no email';
          }
          
          ?></span>
                        <i class="f-right light-gray-text far fa-edit" style="cursor:pointer;" data-modal-open="email"></i>
                    </div>
                    <div class="block">
                        <form action="" style="display:inline;" method="POST">
                            <input type="hidden" name="_token" value="YATvGeTvRAiL0Uqoqk6S1JlpaDQKZQ12UdhkQ1BD">                            <input type="hidden" name="type" value="theme">
                            <span class="dark-gray-text" style="padding-right:5px;">Theme:</span>
                            <div class="inline">
                                <select class="form-control mb-3" name="theme">
<option id="default" type="radio" name="theme" for="default">Default</option>
<option id="dark" type="radio" name="theme" value="2" for="dark">Dark</option>
<option id="dark" type="radio" name="theme" value="3" for="Roblox">Roblox</option>                               
<option id="dark" type="radio" name="theme" value="1" for="dark">Seasonal Light</option>
<option id="dark" type="radio" name="theme" value="4" for="dark">Seasonal Dark</option>
<option id="dark" type="radio" name="theme" value="5" for="future">Halloween</option>
<option id="dark" type="radio" name="theme" value="6" for="98">Windows 98</option>
<option id="dark" type="radio" name="theme" value="7" for="classic">Classic</option>
  </select>
                              </div>
 <button type="submit" value="Save" name="changeTheme" class="f-right blue button small"> Save </button>
   
                             
 </form>
                    </div>
                    <hr>
                    <form method="POST" action="">
<input type="hidden" name="_token" name="desc" value="wGfYHVszadgOYOEouCRRiCHqLkW0qgRGVw1bxxyN"> <textarea name="desc" class="width-100 mb1" style="height:80px;" placeholder="Hi, my name is <?php echo $userRow->{'username'}; ?>"><?php echo $userRow->{'description'}; ?></textarea>
<button type="submit" class="button-small blue">SAVE</button>
</form>
                </div>
                <div class="modal" style="display:none;" data-modal="username">
                    <div class="modal-content">
                        <form action="" style="display:inline;" method="POST">
                            <input type="hidden" name="_token" value="YATvGeTvRAiL0Uqoqk6S1JlpaDQKZQ12UdhkQ1BD">                            <input type="hidden" name="type" value="username">
                            <span class="close" data-modal-close="username">×</span>
                            <span>Change Username</span>
                            <hr>
                            <input type="text" name="username" placeholder="New Username">
                            <span style="color:red;font-size:11px;">WARNING: This will take 250 bucks</span>
                            <div class="modal-buttons">
                                <button class="green" style="margin-right:10px;" type="submit">Buy</button>
                                <button type="button" class="cancel-button" data-modal-close="username">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
  <div class="modal" style="display:none;" data-modal="disname">
                    <div class="modal-content">
                        <form action="" style="display:inline;" method="POST">
                            <input type="hidden" name="_token" value="YATvGeTvRAiL0Uqoqk6S1JlpaDQKZQ12UdhkQ1BD">                            <input type="hidden" name="type" value="username">
                            <span class="close" data-modal-close="username">×</span>
                            <span>Change Display Name</span>
                            <hr>
                            <input type="text" name="disname" placeholder="New Display Name">
                            <span style="color:red;font-size:11px;">WARNING: You will not be charged</span>
                            <div class="modal-buttons">
                                <button class="green" style="margin-right:10px;" type="submit">Get</button>
                                <button type="button" class="cancel-button" data-modal-close="username">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal" style="display:none;" data-modal="password">
                    <div class="modal-content">
                        <form action="" style="display:inline;" method="POST">
                            <input type="hidden" name="_token" value="YATvGeTvRAiL0Uqoqk6S1JlpaDQKZQ12UdhkQ1BD">                            <input type="hidden" name="type" value="password">
                            <span class="close" data-modal-close="password">×</span>
                            <span>Change Password</span>
                            <hr>
                            <input type="password" name="curPass" placeholder="Current Password">
                            <input type="password" name="newPass" placeholder="New Password">
                            <input type="password" name="newPassConfirm" placeholder="Confirm New Password">
                            <div class="modal-buttons">
                                <button class="green" style="margin-right:10px;" name="changePass" type="submit">Save</button>
                                <button type="button" class="cancel-button" data-modal-close="password">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal" style="display:none;" data-modal="email">
                    <div class="modal-content">
                        <form action="" style="display:inline;" method="POST">
                            <input type="hidden" name="_token" value="YATvGeTvRAiL0Uqoqk6S1JlpaDQKZQ12UdhkQ1BD">                            <input type="hidden" name="type" value="email">
                            <span class="close" data-modal-close="email">×</span>
                            <span>Change Email</span>
                            <hr>
                            <input type="text" name="current_email" placeholder="Current Email">
                            <input type="text" name="new_email" placeholder="New Email">
                            <input type="password" name="password" placeholder="Current Password">
                            <div class="modal-buttons">
                                <button class="green" style="margin-right:10px;" type="submit">Save</button>
                                <button type="button" class="cancel-button" data-modal-close="email">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        var _token;

        $(() => _token = $('meta[name="csrf-token"]').attr('content'));
        $('[data-dropdown-open]').click(function(event) {
            var dropdown = $(this).attr('data-dropdown-open');
            var object = `[data-dropdown="${dropdown}"]`;
            var opened = $(object).hasClass('active');

            if (!opened) {
                if (targetMatches(true, event.target, `[data-dropdown-open="${dropdown}"], [data-dropdown-open="${dropdown}"] *`)) {
                    const self = this;

                    $(object).addClass('active').css({
                        top: ($(self).height() + 30) + 'px',
                        left: $(self).offset().left + 'px'
                    });

                    window.onresize = function() {
                        $(object).css({
                            top: ($(self).height() + 30) + 'px',
                            left: $(self).offset().left + 'px'
                        });
                    };
                }
            } else {
                if (targetMatches(false, event.target, `${dropdown}, ${dropdown} *`)) {
                    $(object).removeClass('active');

                    window.onresize = null;
                }
            }
        });


        function targetMatches(does, eventTarget, target)
        {
            if (does)
                return (eventTarget.matches) ? eventTarget.matches(target) : eventTarget.msMatchesSelector(target);

            return (eventTarget.matches) ? !eventTarget.matches(target) : !eventTarget.msMatchesSelector(target);
        }
    </script>
  <script src="../SiT_3/js/main.js"></script>
        </div>
<?php
    include("../SiT_3/footer.php");
    ?>
  </body>
</html>