 <?php   include("../SiT_3/configuration.php");
include("adminheader.php");

 if($power < 1) {header("Location: ../");die();} //topher doesn't know how to do this 🤫

     $bannerSQL = "SELECT * FROM `alert`";
     $banner = $conn->query($bannerSQL);
     $bannerRow = $banner->fetch_assoc();  
							//funnily enough, there are even
							//worse php coders than me LOL
							//who even uses php opening and closing 3 times in a ROW?
  if(isset($_POST['banner1'])) {
    $desc = mysqli_real_escape_string($conn,$_POST['banner']);
    $updateSQL = "UPDATE `site_banner` SET `text`='$desc'";
	//letting kids to do the funny
    $update = $conn->query($updateSQL);
  }
  ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
    <?php
if(isset($_POST['banner1']) && empty($error)) {
          echo '<div class="site-announcement" style="background: rgb(62, 123, 0); color: rgb(255, 255, 255); font-size: 14px;">
<div class="grid-x align-middle grid-margin-x">
<div class="auto cell">
<b>Successfully changed banner!!</b></div>
</div>
</div>';
        }

  if(isset($_POST['banner2'])) {
    $desc = mysqli_real_escape_string($conn,$_POST['bannertext2']);
    $updateSQL = "UPDATE `sitesettings` SET `BannerAlertMessage2`='$desc'";
    $update = $conn->query($updateSQL);
  }

if(isset($_POST['banner2']) && empty($error)) {
          echo '<div class="site-announcement" style="background: rgb(62, 123, 0); color: rgb(255, 255, 255); font-size: 14px;">
<div class="grid-x align-middle grid-margin-x">
<div class="auto cell">
<b>Successfully changed banner!!</b></div>
</div>
</div>';
        }
        ?>
<title>Site Settings - <?php echo $sitename; ?></title>
<div class="container">
<div class="row">
<div class="col-4">
<h3>Admin</h3>
</div>
<div class="col-8 text-right">
</div>
</div>
<ul class="breadcrumb bg-white">
<li class="breadcrumb-item"><a href="/panel/">Admin</a></li>
<li class="breadcrumb-item active">Site Settings</li>
</ul>
    <div class="card">
        <div class="card-body">
                
                <div class="row">
                    <div class="col-md-4">
                        <strong></strong>
                        <strong>First Alert Message</strong><br>
                      <form action="" method="POST">
                        <textarea class="form-control mb-2" type="text" name="banner" placeholder="Site alert here..." rows="5"><?php echo''.$bannerRow['BannerAlertMessage'].''; ?></textarea>
                      <button class="btn btn-block btn-success mt-1" type="submit" name="banner1">Update</button>
                        </form>
                      
                        <strong></strong>
                        <div class="row">
                            <div class="col-6">
                                
                            </div>
                            <div class="col-6">
                                <label for="alert_text_color"></label><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <strong>banners work now</strong>
                        <div class="card">
                            <div class="card-body" style="padding:5px;">
                                
                                    <div><small>but not "Maintenance Passwords"</small>
					<img src="/assets/tophertrolla.png"></img></div>
                    </div>
                        
                                
                                    
                            </div>
                        </div>
                    </div>
                </div>
                
            
        </div>
    </div>

 <?php include("adminfooter.php"); ?>