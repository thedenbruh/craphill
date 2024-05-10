<?php
  include('../SiT_3/config.php');
  include('../SiT_3/header.php');
  
  if($loggedIn) {header('Location: /dashboard/'); die();}
  
  $error = array();
  if (isset($_POST['submit'])) {
        $data = array(
                    'secret' => "0x261B64d2E40DC8cc3Ad6C6A41c1C0536502A8BFf",
                    'response' => $_POST['h-captcha-response']
                );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        // var_dump($response);
        $responseData = json_decode($response);
        if($responseData->success) {
            $username = str_replace(PHP_EOL, '', mysqli_real_escape_string($conn,$_POST['username']));
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirmPassword = $_POST['passwordConfirm'];
    $key = mysqli_real_escape_string($conn, $_POST['key']);
    $IP = $_SERVER['REMOTE_ADDR'];
    $curDate = date('Y-m-d');
    
    if(substr($username,-1) == " " || substr($username,0,1) == " ") {$error[] = "You cannot include a space at the beginning or end of your username.";}
    
    //If their username is less than 4 characters or not alnum
    $alnumUsername = str_replace(array('-','_','.',' '), '', $username);
    
    if(strlen($username) < 4 || strlen($username) > 100 || $username != ctype_alnum($alnumUsername)) {
      $error[] = '<div class="grid"><div class="alert error">Username must be 4-26 characters long</div></div>';
    }
    
    if(strpos($username, '  ') !== false || strpos($username, '..') !== false || strpos($username, '--') !== false || strpos($username, '__') !== false) {
      $error[] = '<div class="grid"><div class="alert error">Spaces, periods, hyphens and underscores must be separated</div></div>';
    }
    //
    
    
    
    if ( $password !== $confirmPassword ) {
      $error[] = '<div class="grid"><div class="alert error">Your passwords do not match</div></div>';
    }
    
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error[] = '<div class="grid"><div class="alert error">Please enter a valid email</div></div>';
    }
    
    
    
    /*$birth_year = mysqli_real_escape_string($conn,intval($_POST['year']));
    $birth_month = mysqli_real_escape_string($conn,intval($_POST['month']));
    if (date('Y')-$birth_year >= 1 && date('Y')-$birth_year <= 124) {
      $birth_date = $birth_year."-".$birth_month."-01";
    } else {
      $error[] = "You must be between 1 and 124 years old to play Brick Hill.";
    }*/
    
    
    
    
    
    $usernameL = strtolower(mysqli_real_escape_string($conn, $username));
    
    $checkUsernameSQL = "SELECT * FROM `user`.`beta_users` WHERE `beta_users`.`usernameL` = '$usernameL'";
    $checkUsername = $conn->query($checkUsernameSQL);
    
    if ($checkUsername->num_rows > 0) {
      $error[] = '<div class="grid"><div class="alert error">This username is taken</div></div>';
    }
    
    
   /* 
    
   $findKeySQL = "SELECT * FROM `reg_keys` WHERE `key_content` = '$key' AND `used` = 0";
      
    $findKey = $conn->query($findKeySQL);
    
    if(empty($error)) {
      if ($findKey->num_rows == 0) {
          $error[] = '<div class="grid"><div class="alert error">Invalid Beta Key</div></div>';
          
      } elseif($findKey->num_rows > 0) {
        
        $keyRow = $findKey->fetch_assoc();
        $keyID = $keyRow['id'];
        
        $updateKeySQL = "UPDATE `reg_keys` SET `used` = '1' WHERE `id` = '$keyID' ";
        $updateKey = $conn->query($updateKeySQL);
        
      }
    }
    */
    if(empty($error)) {
      
      
    
      $password = password_hash($password, PASSWORD_BCRYPT);
      $username = mysqli_real_escape_string($conn, $username);
      
      do {    $uid = bin2hex(random_bytes(20));
        $uidCheckSQL = "SELECT * FROM `beta_users` WHERE `unique_key`='$uid'";
        $uidCheck = $conn->query($uidCheckSQL);
      } while ($uidCheck->num_rows > 0);
      
      $createUserSQL = "INSERT INTO `beta_users` (`id`, `username`, `usernameL`, `password`, `IP`, `birth`, `gender`, `date`, `last_online`, `daily_bits`, `views`, `description`, `bucks`, `bits`, `power`, `unique_key`, `theme`) VALUES (NULL, '$username', '$usernameL', '$password', '$IP', '$birth_date', '$gender', now(), '$curDate', '$curDate', '0', '', '1', '10', '0', '$uid','0')";
      
      $createUser = $conn->query($createUserSQL);
      if ($createUser) {
        $userID = $conn->insert_id;
        
        $emailSQL = "INSERT INTO `emails` (`id`, `user_id`, `email`, `verified`, `date`) VALUES (NULL, '$userID', '$email', 'no', CURRENT_TIMESTAMP)";
        $emailQ = $conn->query($emailSQL);
        
        do {    $uid = bin2hex(random_bytes(20));
          $uidCheckSQL = "SELECT * FROM `beta_users` WHERE `unique_key`='$uid'";
          $uidCheck = $conn->query($uidCheckSQL);
        } while ($uidCheck->num_rows > 0);
        
        if(substr($usernameL,-1) == 's') {$title = $username."' Set";}
        else {$title = $username."'s Set";}
        
        $gameSQL = "INSERT INTO `games` (`id`,`creator_id`,`name`,`description`,`playing`,`visits`,`date`,`last_updated`,`address`,`uid`,`active`) VALUES (NULL,  '$userID',  '$title',  '',  '0',  '0', CURRENT_TIME, CURRENT_TIME, '127.0.0.1', '$uid', '0')";
        $game = $conn->query($gameSQL);
        
        $tshirt = rand(1);
        $serialSQL = "SELECT * FROM `crate` WHERE `item_id`='$tshirt' ORDER BY `serial` DESC"; //find the serial SQL
        $serialQ = $conn->query($serialSQL); //
        $serialRow = $serialQ->fetch_assoc(); //
        $serial = $serialRow['serial']+1; //find the serial
        
        $addTshirtSQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`) VALUES (NULL,'$userID','$tshirt','$serial')";
        $addTshirt = $conn->query($addTshirtSQL);
        
        $torsoColors = array('c60000','3292d3','85ad00','e58700');
        $legColors = array('650013','1c4399','1d6a19','76603f');
        $torso = $torsoColors[rand(0,3)];
        $leg = $legColors[rand(0,3)];
        $avatarSQL = "INSERT INTO `avatar` (`user_id`,`head_color`,`torso_color`,`right_arm_color`,`left_arm_color`,`right_leg_color`,`left_leg_color`,`face`,`shirt`,`pants`,`tshirt`,`hat1`,`hat2`,`hat3`,`hat4` ,`hat5`,`tool`,`head` ,`cache`) VALUES ('$userID',  'f3b700',  '$torso',  'f3b700',  'f3b700',  '$leg',  '$leg',  '0',  '0',  '0',  '$tshirt',  '0',  '0',  '0',  '0',  '0',  '0',  '0',  '0')";
        $avatar = $conn->query($avatarSQL);
        $_SESSION['id'] = $userID;
$messager = "INSERT INTO `messages` (`id`, `author_id`, `recipient_id`, `date`, `title`, `message`, `read`) VALUES (NULL, '1', '{$_SESSION['id']}', CURRENT_TIMESTAMP, 'Welcome to SAU!', 'Hello there, {$username}!

Welcome to SAU, we\'re glad to have you here. We hope you have a wonderful time, but to make sure you can make the most here, we ask that you brush up on a few of our guidelines.

Here are some basic rules to get you settled in with the crowd! If you\'re unsure about anything, then you can always take a look at our Terms of Service, which will always be situated at the footer of each page!

Thanks for stopping by,
Epic ', '0')";
$msgSQL = $conn->query($messager);
header('Location: /customize/?regen');

      } else {
        $error[] = 'Database error';
      }
    }
        }
        else {
           $error[] = '<div class="grid"><div class="alert error">Please fill in the Captcha</div></div>';
        }
      
    
    
  }
?>
<!DOCTYPE html>
  <head>
    <title>Register - <?php echo $sitename; ?></title>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
  </head>
  <body>
          
          <!--<?php
          if(!empty($error)) {
            echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
            foreach($error as $line) {
              echo $line.'<br>';
            }
            echo '</div>';
          }
        ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
            <div class="card card-body">
                <h2>Register</h2>
                    <p>If you already have an account, you can <a href="/auth/login">login here</a>.</p>
                <form action="" method="POST" id="sub-form">
                    <input type="text" placeholder="Username" name="username" class="form-control mb-1">
                    <input type="text" placeholder="Email" name="email" class="form-control mb-1">
                    <input type="password" placeholder="Password" name="password" class="form-control mb-1">
                    <input type="password" placeholder="Confirm Password" name="passwordConfirm" class="form-control mb-1">
                    <!--input type="text" placeholder="Invite Key" name="key" class="form-control mb-1">
                    <div class="h-captcha" data-sitekey="f2485193-464a-4ad0-8eb9-dfce6c226fcf"></div-->
                    <!--  <input name="__RequestVerificationToken" type="hidden" value="CfDJ8KI4wG7Y_lxMlWQNw9RZp1wTFYJfkjbto3835Ge0AuiLvEwBrgH8YsjeN17YcgpBrBIok5UFKX49ptYOQIUwUQgxiFPdRpTlkqWQUaW-qx9HLgtjP6OGUSXe34YyiG2AvQ8SO9Tr4nFh7nHZbKUgjaI">-->
                    <!--<input class="btn btn-primary" type="submit" value="Register" id="login-submit" name="submit">
                </form>
            </div>
        </div>
    </div>
</div><br>-->
    
    <div class="main-holder grid">
<style>
    h3 {
        margin: 2px 0;
    }
    h6 {
        margin: 2px 0;
        font-size: 12px;
    }
    input:not([type="radio"]) {
        display: block;
        margin-bottom: 5px;
    }
    .genders {
        margin-bottom: 5px;
    }
    input[type="radio" i] {
        margin: 3px 3px 0 0;
    }
    label {
        margin-right: 5px;
    }
</style>
      <?php
          if(!empty($error)) {
            echo '';
            foreach($error as $line) {
              echo $line.'';
            }
            echo '';
          }
        ?>
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Register
</div>
<div class="content">
<div class="col-8-12">
<form method="POST" action="">
<input type="hidden" name="_token" value="2wVmG4IFMlOIvWh8TUNwQGxKWtQbY3sRCpyLRuB0"> <h3 class="dark-gray-text">Username</h3>
<h6 class="light-gray-text">How will people recognize you?</h6>
<input id="username" type="text" name="username" value placeholder="Username" required autofocus>
<h3 class="dark-gray-text">Password</h3>
<h6 class="light-gray-text">Only you will know this!</h6>
<input id="password" minlength="6" type="password" name="password" placeholder="Password" value required>
<input id="password_confirmation" type="password" name="passwordConfirm" placeholder="Confirm Password" value required>
<h3 class="dark-gray-text">Email</h3>
<h6 class="light-gray-text">This must be valid so we can contact you!</h6>
<input id="email" type="email" name="email" placeholder="Email" value>
<!--h3 class="dark-gray-text">Beta Key</h3>
<h6 class="light-gray-text">This must be valid so we can sign you up!</h6>
<input id="key" type="text" name="key" placeholder="Key" value-->
<div class="col-1-1" style="margin-top:5px;">
<div class="h-captcha" data-sitekey="f2485193-464a-4ad0-8eb9-dfce6c226fcf"></div>
</div>
<div class="col-1-1">
<div style="padding-top:5px;"></div>
<button type="submit" name="submit" class="blue">
Register
</button>
</div>
<div class="col-1-1">
<span class="gray-text" style="font-size:14px;">By signing up to <?php echo $sitename; ?>, you confirm that you have read and agree to the <a class="dark-gray-text bold" href="/terms" target="_blank">Terms of Service</a>, as well as our <a class="dark-gray-text bold" href="/privacy" target="_blank">Privacy Policy</a>.</span>
</div>
</form>
</div>
<div class="col-4-12" style="position:relative;min-height:310px;">
<div class="col-12-12" style="position:absolute;top:5px;right:5px;">
<div style="border-radius:5px;border:1px solid #D9D9D9;padding:5px;">
<h3 class="dark-gray-text">Already have an account?</h3>
<span class="light-gray-text" style="font-size:15px;">If you've forgotten your password go to <a class="dark-gray-text bold" href="/password/forgot">forgot password</a>.<br><br>To login, go to <a class="dark-gray-text bold" href="/login">login</a>.<br><br>Can't play? Go to <a class="dark-gray-text bold" href="/download">download</a> and install the client!</span>
</div>
</div>
</div>
</div>
</div>
</div>
      </div>
    </div>
    <?php
      include('../SiT_3/footer.php');
    ?>
  </body>
</html>