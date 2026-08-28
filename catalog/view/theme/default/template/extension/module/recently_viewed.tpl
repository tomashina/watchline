    <div class="container">  
    <div class="widget widget-related">

      <div class="widget-title">
        <p class="main-title"><span><?php echo $heading_title; ?></span></p>
        <p class="widget-title-separator"><i class="icon-line-cross"></i></p>
        </div>


   <div class="grid grid-holder related carousel grid4">
   <?php foreach ($products as $product) { ?>
              <?php require('catalog/view/theme/basel/template/product/single_product.tpl'); ?>
            <?php } ?>
  </div>

</div>
</div>