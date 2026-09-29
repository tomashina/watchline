<div class="container">
<?php echo $position_bottom_half; ?>
</div>
<div class="container">
<?php echo $position_bottom; ?>
</div>
<div class="legal-guarantee-bar">
  <div class="container">
    <a class="legal-guarantee-trigger" href="<?php echo $legal_guarantee_url; ?>" data-toggle="modal" data-target="#legal-guarantee-modal" aria-controls="legal-guarantee-modal" aria-haspopup="dialog">
      <i class="fa fa-shield" aria-hidden="true"></i>
      <span><?php echo $text_legal_guarantee; ?></span>
      <i class="fa fa-angle-right" aria-hidden="true"></i>
    </a>
  </div>
</div>
<div id="footer">
<div class="container">
<?php if ($footer_block_1 && $footer_block_1 != '<p><br></p>') { ?>
<div class="footer-top-block">
<?php echo $footer_block_1; ?>
</div>
<?php } ?>
<div class="row links-holder">
<div class="col-xs-12 col-sm-12">
  <div class="row">
  <?php if ($custom_links) { ?>
    <?php foreach($basel_footer_columns as $column) { ?>
    <div class="footer-column col-xs-12 col-sm-6 <?php echo $basel_columns_count; ?> eq_height">
      <?php if ($column['title']){ ?>
        <p class="h5"><?php echo $column['title']; ?></p>
      <?php } ?>
      <?php if(isset($column['links'])){ ?>
      <?php usort($column['links'], function ($a, $b) { return strcmp($a['sort'], $b['sort']); }); ?>
      <ul class="list-unstyled">
      <?php foreach($column['links'] as $key => $link){ ?>
      <li><a href="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a></li>
      <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <?php } ?>
  <?php } else { ?>
      <?php if ($informations) { ?>
      <div class="footer-column col-xs-6 col-sm-3 eq_height">
         <p class="h5"><?php echo $text_information; ?></p>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </div>
      <?php } ?>
      <div class="footer-column col-xs-6 col-sm-3 eq_height">
         <p class="h5"><?php echo $text_extra; ?></p>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="footer-column col-xs-6 col-sm-3 eq_height">
         <p class="h5"><?php echo $text_account; ?></p>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li class="is_wishlist"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>

          <li> <a href="https://ec.europa.eu/consumers/odr/main/index.cfm?event=main.home2.show&amp;lng=HR">Internetsko rješavanje sporova </a></li>
        </ul>
      </div>
      <div class="footer-column col-xs-6 col-sm-3">
<div class="footer-custom-wrapper">
<?php if (!empty($footer_block_title)) { ?>
 <p class="h5"><?php echo $footer_block_title; ?></p>
<?php } ?>
<?php if ($footer_block_2 && $footer_block_2 != '<p><br></p>') { ?>
<div class="custom_block"><?php echo $footer_block_2; ?></div>
<?php } ?>
<?php if (!empty($footer_infoline_1)) { ?>
<p class="infoline"><?php echo $footer_infoline_1; ?></p>
<?php } ?>
<?php if (!empty($footer_infoline_2)) { ?>
<p class="infoline"><?php echo $footer_infoline_2; ?></p>
<?php } ?>
<?php if (!empty($footer_infoline_3)) { ?>
<p class="infoline"><?php echo $footer_infoline_3; ?></p>
<?php } ?>
<?php if ($payment_img) { ?>
<img class="payment_img" src="<?php echo $payment_img; ?>" alt="" />
<?php } ?>
</div>
</div>
 <?php } ?>
</div><!-- .row ends -->
</div><!-- .col-md-8 ends -->
<div class="col-xs-12 text-center" style="margin-top:15px;margin-bottom:15px">

 <img  src="https://www.wspay.info/payment-info/wsPayWebSecureLogo-118x50-transparent.png" alt="WSPAY">  
</div>
<div class="col-xs-12 text-center" style="margin-top:15px;margin-bottom:15px" >
 <img style="width: 55px;margin-right:3px" src="image/catalog/credit-cards/visa.svg" alt="visa">
<img style="width: 55px;;margin-right:3px" src="image/catalog/credit-cards/maestro.svg" alt="maestro"> 
<img style="width: 55px;;margin-right:3px" src="image/catalog/credit-cards/mastercard.svg" alt="master card"> 
<img style="width: 55px;;margin-right:3px" src="image/catalog/credit-cards/diners.svg" alt="diners">

</div>
</div><!-- .row ends -->
<?php if (isset($basel_copyright)) { ?>
<div class="footer-copyright"><?php echo $basel_copyright; ?></div>
<?php } ?>
</div>
</div>

<div class="modal fade legal-guarantee-modal" id="legal-guarantee-modal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="legal-guarantee-modal-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $text_legal_guarantee_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="legal-guarantee-modal-title"><?php echo $text_legal_guarantee_title; ?></h4>
      </div>
      <div class="modal-body">
        <div class="legal-guarantee-notice-frame" tabindex="0" role="region" aria-label="<?php echo $text_legal_guarantee_notice_alt; ?>">
          <a href="<?php echo $legal_guarantee_notice_svg; ?>" target="_blank" rel="noopener" aria-label="<?php echo $text_legal_guarantee_open_full; ?>">
            <img src="<?php echo $legal_guarantee_notice_svg; ?>" alt="<?php echo $text_legal_guarantee_notice_alt; ?>" width="595" height="842" loading="lazy" decoding="async" />
          </a>
        </div>
        <p class="legal-guarantee-more">
          <a href="<?php echo $legal_guarantee_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $text_legal_guarantee_more; ?> <i class="fa fa-external-link" aria-hidden="true"></i></a>
        </p>
      </div>
    </div>
  </div>
</div>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link href="catalog/view/theme/basel/js/lightgallery/css/lightgallery.css" rel="stylesheet" />
<script src="catalog/view/theme/basel/js/jquery.matchHeight.min.js"></script>
<script src="catalog/view/theme/basel/js/countdown.js"></script>
<script src="catalog/view/theme/basel/js/live_search.js?v=1.1"></script>
<script src="catalog/view/theme/basel/js/featherlight.js"></script>
<?php if ($view_popup) { ?>
<!-- Popup -->
<script>
$(document).ready(function() {
if ($(window).width() > <?php echo $popup_width_limit; ?>) {
setTimeout(function() {
$.featherlight({ajax: 'index.php?route=extension/basel/basel_features/basel_popup', variant:'popup-wrapper'});
}, <?php echo $popup_delay; ?>);
}
});



</script>
<?php } ?>
<script>
$("#clear-cart").click( function(e){
    e.preventDefault();
    document.cookie = "basel_popup=; expires=Thu, 01 Jan 1970 00:00:00 GMT";

    location.reload();
});

</script>
<?php if ($sticky_columns) { ?>
<!-- Sticky columns -->
<script>
if ($(window).width() > 767) {
$('#column-left, #column-right').theiaStickySidebar({containerSelector:$(this).closest('.row'),additionalMarginTop:<?php echo $sticky_columns_offset; ?>});
}
</script>
<?php } ?>
<?php if ($view_cookie_bar) { ?>
<!-- Cookie bar -->
<div class="basel_cookie_bar">
<div class="table">
<div class="table-cell w100"><?php echo $basel_cookie_info; ?></div>
<div class="table-cell button-cell">
<a class="btn btn-tiny btn-light-outline" onclick="$(this).parent().parent().parent().fadeOut(400);"><?php echo $basel_cookie_btn_close; ?></a>
<?php if (!empty($href_more_info)) { ?>
<a class="more-info anim-underline light" href="<?php echo $href_more_info; ?>"><?php echo $basel_cookie_btn_more_info; ?></a>
<?php } ?>
</div>
</div>
</div>
<?php } ?>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
BASEL VERSION <?php echo $basel_version; ?> - OPENCART VERSION 2.3 (<?php echo VERSION; ?>)
//-->
</div><!-- .outer-container ends -->

<!-- Load Facebook SDK for JavaScript -->


<?php if ($ruta == 'https://www.watchline.hr/index.php?route=') { ?>
<a id="news" class="float-first btn-base-color <?php echo $ruta; ?>"> <span>Preuzmite 10% popusta!</span></a>
<?php } ?>
<style>


</style>



<script>
$("#news").click( function(e){
    e.preventDefault();
    document.cookie = "basel_popup=; expires=Thu, 01 Jan 1970 00:00:00 GMT";

    location.reload();
});

</script>
    

<a class="scroll-to-top primary-bg-color hidden-sm hidden-xs" onclick="$('html, body').animate({scrollTop:0});"><i class="icon-arrow-right"></i></a>
<div id="featherlight-holder"></div>
</body></html>
