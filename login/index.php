<?php 
  include('../SiT_3/config.php');
  include('../SiT_3/head.php');
  
  if($loggedIn) {header('Location: /dashboard/'); die();}
  
  $error = array();
  if (isset($_POST['ln'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
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
        $usernameL = strtolower(mysqli_real_escape_string($conn, $username));
    
    $checkUsernameSQL = "SELECT * FROM `beta_users` WHERE `beta_users`.`usernameL` = '$usernameL'";
    $checkUsername = $conn->query($checkUsernameSQL);
    
    if ($checkUsername->num_rows > 0) {
      
      $username = mysqli_real_escape_string($conn, $username);
      
      $userReqRow = (object) $checkUsername->fetch_assoc();
      
      $userPass = $userReqRow->{'password'};
      
      if (password_verify($password, $userPass)) { //logged in
        $_SESSION['id'] = $userReqRow->{'id'};
        $userID = $_SESSION['id'];
        $userIP = $_SERVER['REMOTE_ADDR'];
        $logSQL = "INSERT INTO `log` (`id`,`action`,`date`) VALUES (NULL,'User $userID logged in from 0',CURRENT_TIMESTAMP)";
        $log = $conn->query($logSQL);
        header('Location: /dashboard/');
        die();
      } else {
        $error[] = '<div class="grid"><div class="alert error">Incorrect password</div></div>';
      }
      
    } else {
      $error[] = '<div class="grid"><div class="alert error">User does not exist</div></div>';
      
    }
        } 
        else {
        $error[] = '<div class="grid"><div class="alert error">Captcha Required</div></div>';
        }
    
  }
?>
<head>
  
    <title>Login - <?php echo $sitename; ?></title>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
  </head>
  <body>
    
                            
                                
                <?php
            if(!empty($error)) {
              echo '';
              foreach($error as $line) {
                echo $line.'';
              }
              echo '';
            }
            ?>
    <div class="main-holder grid">
          <div class="col-1-3 push-1-3">
<div class="card">
<div class="top green">
Login
</div>
<div class="content">
      
<form action="" method="POST">
<input type="text" id="username" placeholder="Username" name="username" class="form-control mb-1">
<div style="height:5px;"></div>
<input type="password" id="password" placeholder="Password" name="password" class="form-control mb-1">
<br><a href="/password/forgot" style="font-size:15px;">Forgot password?</a>
<div style="padding-top:5px;"></div>
<div class="h-captcha" data-sitekey="f2485193-464a-4ad0-8eb9-dfce6c226fcf"></div>
<input name="__RequestVerificationToken" type="hidden" value="CfDJ8KI4wG7Y_lxMlWQNw9RZp1wTFYJfkjbto3835Ge0AuiLvEwBrgH8YsjeN17YcgpBrBIok5UFKX49ptYOQIUwUQgxiFPdRpTlkqWQUaW-qx9HLgtjP6OGUSXe34YyiG2AvQ8SO9Tr4nFh7nHZbKUgjaI">
<button type="submit" class="green" name="ln">
Login
</button>
</form>
</div>
</div>
</div>
</div>
                     
                              
    <?php
      include('../SiT_3/footer.php');
    ?>
  </body>
</html>