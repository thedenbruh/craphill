<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  
  if(isset($_GET['search'])) {
    $searchQ = htmlentities($_GET['search']);
  } else {
    $searchQ = '';
  }
?>

<!DOCTYPE html>
  <head>
    <title>Shop - <?php echo $sitename; ?></title>
  </head>
  <body>
<?php
include('shopheader.php');
?>
<div class="col-10-12 push-1-12" id="crate">
</div>
<script>
    $(window).scroll(debouncer(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !shopEnd) {
            loadItems($('.shop-categories .active a').data('itemType'), $('#shopSort :selected').data('sort'), currentSearch, shopPage + 1)
        }
    }));

    loadItems()
</script>
<div class="col-10-12 push-1-12">
<div style="text-align:center;margin-top:20px;padding-bottom:25px;">

</div>
</div>
</div>
    
<script>
  
  window.onload = function() {
      getPage('all','',0);
    };
  
    function getPage(type, search, page) {
      $("#crate").load("/shop/crate?item="+type+"&search="+search+"&page="+page);
    };
</script>
    </div>
    <?php
      include("../SiT_3/footer.php");
    ?>
  </body>
<html>