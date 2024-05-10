 <?php   include("../SiT_3/configuration.php");
include("adminheader.php");

 if($power < 1) {header("Location: ../");die();} 

if($loggedIn && isset($_POST['post'])){
$me = $_SESSION['id'];
$title = mysqli_real_escape_string($conn,$_POST['title']);
$body = mysqli_real_escape_string($conn,$_POST['body']);
$post = $conn->query("INSERT INTO `news` (`id`, `uid`, `title`, `body`, `lupd`) VALUES (NULL, '$me', '$title', '$body', current_timestamp())");
}
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
    <title>Create Blog - <?php echo $sitename; ?></title>
</head>

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
<li class="breadcrumb-item active">Create Blog</li>
</ul>
    <div class="card">
        <div class="card-body">
                
                      <form action="" method="POST">
                        <textarea class="form-control mb-2" type="text" name="title" placeholder="Blog title, will be displayed in News section of your dashboard" rows="2"></textarea>
                        <textarea class="form-control mb-2" type="text" name="body" placeholder="Blog body" rows="5"></textarea>
                      <button class="btn btn-block btn-success mt-1" type="submit" name="post">Create</button>
                        </form>
        <i><b>For the blog post removal, ask the owners but not denny.<b></i>

                    </div>
                </div>
                
            
        </div>
    </div>

 <?php include("adminfooter.php"); ?>