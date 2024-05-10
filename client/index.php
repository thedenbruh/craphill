<?php include("../SiT_3/header.php"); ?>
<title>Download - <?php echo $sitename; ?></title>
  <div class="main-holder grid">
<style>
    body, html {
        background-color: #3C3C3C !important;
    }
    .splash {
        width: 713px;
        height: 603px;
        background-image: url('https://www.brick-hill.com/images/shuttle.png');
        background-position: center;
        background-repeat: no-repeat;
        margin: auto;
        margin-top: -24%;
        padding-top: 40%;
        box-sizing: content-box;
    }
    a.download {
        float:left;
        text-decoration:none;
    }
    a.download h5 {
        margin:0;
    }
    a.download:hover button {
        cursor:pointer;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
    }
</style>
<div class="splash">
<a href="https://web.archive.org/web/20200429134439/https://brkcdn.com/downloads/BrickHillSetup.exe" class="download" style="width:100%;">
<button class="orange">
<h1 style="margin:0.6em 0.8em">Download</h1>
</button>
<h5 style="color:#000">V0.3, 13.58MB</h5>
</a>
<a href="https://brickhill.gitlab.io/open-source/node-hill/" class="download" style="margin-top:30px;">
<button class="nh-button">
<h1 style="margin:0.6em 0.8em">NODE-HILL</h1>
</button>
<div class="small-text dark-gray-text">V11.0.3, 492KB</div>
</a>
</div>
</div>
<?php include("../SiT_3/footer.php"); ?>