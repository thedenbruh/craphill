<?php
include("../SiT_3/configuration.php");

$error = array();

$key = "maint.php";
if (!empty($_POST)) {
    if ($_POST["key"] != $key) {
        $error[] = "<p class='error-message'>Incorrect Password!</p>";
    }

    if (empty($error)) {
        $_SESSION['canAccess'] = "true";
        header("Location: /");
    }                             //as far as i remember, that's the jp's workaround, idk
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline! (dead)</title>
    
    <!-- Google Fonts for modern typography -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom CSS styles -->
    <style>
        /* Page styling */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h3 {
            margin-bottom: 20px;
        }

        .error-message {
            color: #FF0000;
        }

        form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        input.form-control {
            margin-bottom: 10px;
            padding: 10px;
            border: none;
            border-radius: 4px;
        }

        button.btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        button.btn:hover {
            background-color: #0056b3;
        }

        button.btn i {
            margin-right: 10px;
        }

        #timer {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div>
        <?php if (!empty($error)) { ?>
            <?= $error[0] ?>
        <?php } ?>
        <h3><?php echo $sitename; ?> is undergoing maintenance.</h3>
        <p>Our web developers are working hard to release new features!<br>
        <i>We should be back up soon, so no need to keep checking in.</i></p>
        <hr>
        <p>Expected downtime: <span id="timer"></span></p>

        <form method="POST" action="#">
            <input type="password" class="form-control" name="key" placeholder="Password" required>
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Submit
            </button>
        </form>
    </div>

    <script>
        // Countdown timer script
        const countDownDate = new Date("Jun 7, 2023 1:37:25").getTime(); //time goes here
        const timer = document.getElementById("timer");

        const intervalId = setInterval(() => {
            const now = new Date().getTime();
            const distance = countDownDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance >= 0) {
                timer.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            } else {
                clearInterval(intervalId);
                timer.innerHTML = "<strong>The Site Will Be Hacked By Super Skibidi Hacker 1488.</strong>";
//imo i would just use the header() thing to restart the page everytime for making it come back to the landing page later, shown in virto!beta but idk if ed0 will ever use it in future /shrug
            }
        }, 1000);
    </script>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>

</html>
