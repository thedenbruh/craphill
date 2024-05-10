<?php 
include('../SiT_3/config.php');
include('../SiT_3/header.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Awards - <?php echo $sitename; ?></title>
</head>
<body>
  <!--<div id="body">
    <div id="box" style="background-color: unset; border: 0; padding: 0px;">


<table width="100%" cellspacing="1" cellpadding="4" border="0" style="background-color:#000;">
        <tbody>
          <tr>
            <th width="50%">
              <p class="title" style="color:#FFF;">Membership</p>
            </th>
          </tr>

          

                     <tr class="forumColumn">
            <td>
              <?php
          $awardsSQL = "SELECT * FROM awards WHERE category = '1'";
          $awardsQuery = $conn->query($awardsSQL);

          while($awardRow = $awardsQuery->fetch_assoc()) {
            $awardRow = (object) $awardRow;
          ?>
          <jj>
            <jjj><img src="/assets/awards/<?php echo $awardRow->{'id'}; ?>.png" style="border: 1px solid #000;background-color: #FDFDFD;width: 118px;height: 118px;float: left;margin-right: 5px;"></jjj>
            <j style="display: block;">
              <span style="font-size: 25px;font-weight: bold;color: #333;"><?php echo $awardRow->{'name'}; ?></span>
              <p style=" padding: 3px; font-size: 16px; margin-top: 0px; margin-left: 124px;"><?php echo $awardRow->{'description'}; ?></p>
            </j>
          </jj>
          <br><br><br>
          <?php
          }
          ?>
            </td>
            </tr>

        </tbody>
      </table>

<br>

<table width="100%" cellspacing="1" cellpadding="4" border="0" style="background-color:#000;">
        <tbody>
          <tr>
            <th width="50%">
              <p class="title" style="color:#FFF;">Community</p>
            </th>
          </tr>

          

                     <tr class="forumColumn">
            <td>
              <?php 
          $awardsSQL = "SELECT * FROM awards WHERE category = '2'";
          $awardsQuery = $conn->query($awardsSQL);

          while($awardRow = $awardsQuery->fetch_assoc()) {
            $awardRow = (object) $awardRow;
          ?>
          <jj>
            <jjj><img src="/assets/awards/<?php echo $awardRow->{'id'}; ?>.png" style="border: 1px solid #000;background-color: #FDFDFD;width: 118px;height: 118px;float: left;margin-right: 5px;"></jjj>
            <j style="display: block;">
              <span style="font-size: 25px;font-weight: bold;color: #333;"><?php echo $awardRow->{'name'}; ?></span>
              <p style=" padding: 3px; font-size: 16px; margin-top: 0px; margin-left: 124px;"><?php echo $awardRow->{'description'}; ?></p>
            </j>
          </jj>
          <br><br><br>
          <?php 
          }
          ?>
            </td>
            </tr>

        </tbody>
      </table>

<br>

<table width="100%" cellspacing="1" cellpadding="4" border="0" style="background-color:#000;">
        <tbody>
          <tr>
            <th width="50%">
<p class="title" style="color:#FFF;">Excellence</p>
            </th>
          </tr>

          <style>.centertitle{text-align: center !important; display: block !important;} .description{text-align: center !important;}</style>

                     <tr class="forumColumn">
            <td>
              <?php 
          $awardsSQL = "SELECT * FROM awards WHERE category = '3'";
          $awardsQuery = $conn->query($awardsSQL);

          while($awardRow = $awardsQuery->fetch_assoc()) {
            $awardRow = (object) $awardRow;
          ?>
          <jj>
            <jjj><img src="/assets/awards/<?php echo $awardRow->{'id'}; ?>.png" style="border: 1px solid #000;background-color: #FDFDFD;width: 118px;height: 118px;float: left;margin-right: 5px;"></jjj>
            <j style="display: block;">
              <span style="font-size: 25px;font-weight: bold;color: #333;"><?php echo $awardRow->{'name'}; ?></span>
              <p style=" padding: 3px; font-size: 16px; margin-top: 0px; margin-left: 124px;"><?php echo $awardRow->{'description'}; ?></p>
            </j>
          </jj>
          <br><br><br>
          <?php 
          }
          ?>
            </td>
            </tr>

        </tbody>
      </table>







    </div>
  </div>
</body>
</html>-->
  <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top red">
Membership
</div>
<div class="content">
<?php
          $awardsSQL = "SELECT * FROM awards WHERE category = '1'";
          $awardsQuery = $conn->query($awardsSQL);

          while($awardRow = $awardsQuery->fetch_assoc()) {
            $awardRow = (object) $awardRow;
          ?>
          <div class="award-card">
<img src="/assets/awards/<?php echo $awardRow->{'id'}; ?>.png">
<div class="data">
<div class="very-bold"><?php echo $awardRow->{'name'}; ?></div>
<div style="padding:1px;"></div>
<span><?php echo $awardRow->{'description'}; ?></span>
</div>
</div>
          <?php
          }
          ?>

</div>
</div>
<div class="card">
<div class="top green">
Community
</div>
<div class="content">
<?php
          $awardsSQL = "SELECT * FROM awards WHERE category = '2'";
          $awardsQuery = $conn->query($awardsSQL);

          while($awardRow = $awardsQuery->fetch_assoc()) {
            $awardRow = (object) $awardRow;
          ?>
          <div class="award-card">
<img src="/assets/awards/<?php echo $awardRow->{'id'}; ?>.png">
<div class="data">
<div class="very-bold"><?php echo $awardRow->{'name'}; ?></div>
<div style="padding:1px;"></div>
<span><?php echo $awardRow->{'description'}; ?></span>
</div>
</div>
          <?php
          }
          ?>
</div>
</div>
<div class="card">
<div class="top orange">
Excellence
</div>
<div class="content">
<?php
          $awardsSQL = "SELECT * FROM awards WHERE category = '3'";
          $awardsQuery = $conn->query($awardsSQL);

          while($awardRow = $awardsQuery->fetch_assoc()) {
            $awardRow = (object) $awardRow;
          ?>
          <div class="award-card">
<img src="/assets/awards/<?php echo $awardRow->{'id'}; ?>.png">
<div class="data">
<div class="very-bold"><?php echo $awardRow->{'name'}; ?></div>
<div style="padding:1px;"></div>
<span><?php echo $awardRow->{'description'}; ?></span>
</div>
</div>
          <?php
          }
          ?>
</div>
</div>
</div></div></div></div></div>
<?php
//include('../SiT_3/config.php');
include('../SiT_3/footer.php');
?>