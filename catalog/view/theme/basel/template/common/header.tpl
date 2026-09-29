<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo htmlspecialchars($direction, ENT_QUOTES, 'UTF-8'); ?>" lang="<?php echo htmlspecialchars($lang, ENT_QUOTES, 'UTF-8'); ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo htmlspecialchars(html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="google-site-verification" content="2-coc_MNYMNhd0GXJ1QoPFgORFmk5ITeXPmQYFtwvyQ" />
<meta name="google-site-verification" content="X7-vQjKku6Wbx4O5ebeiaQddYKHqEyWXjw0ITf6Sv4I" />
<base href="<?php echo htmlspecialchars($base, ENT_QUOTES, 'UTF-8'); ?>" />
<?php if ($description) { ?><meta name="description" content="<?php echo htmlspecialchars(html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" /><?php } ?>
<meta name="robots" content="<?php echo isset($robots) ? htmlspecialchars($robots, ENT_QUOTES, 'UTF-8') : 'index,follow'; ?>" />
<!-- Load essential resources -->
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
<script src="catalog/view/theme/basel/js/slick.min.js"></script>
<script src="catalog/view/theme/basel/js/basel_common.js"></script>
<meta name="facebook-domain-verification" content="rquh0fp20h0xgf0gvt61pku29lpnsz" />

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0b0b45">
<meta name="msapplication-TileColor" content="#0b0b45">
<meta name="theme-color" content="#ffffff">

<!-- TrustBox script -->

<!-- End TrustBox script -->
<!-- Main stylesheet -->
<link href="catalog/view/theme/basel/stylesheet/stylesheet.css?v=1.98" rel="stylesheet">
<!-- Mandatory Theme Settings CSS -->
<style id="basel-mandatory-css"><?php echo $basel_mandatory_css; ?></style>
<!-- Plugin Stylesheet(s) -->
<?php foreach ($styles as $style) { ?>

<?php if ($style['href'] != '//fonts.googleapis.com/css?family=%7C') { ?>
<link href="<?php echo htmlspecialchars(html_entity_decode($style['href'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" rel="<?php echo htmlspecialchars($style['rel'], ENT_QUOTES, 'UTF-8'); ?>" media="<?php echo htmlspecialchars($style['media'], ENT_QUOTES, 'UTF-8'); ?>" />
<?php } ?>
<?php } ?>
<!-- Pluing scripts(s) -->
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>"></script>
<?php } ?>
<!-- Page specific meta information -->
<?php foreach ($links as $link) { ?>
<?php if ($link['rel'] == 'image') { ?>
<meta property="og:image" content="<?php echo htmlspecialchars(html_entity_decode($link['href'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" />
<?php } else { ?>
<link href="<?php echo htmlspecialchars(html_entity_decode($link['href'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" rel="<?php echo htmlspecialchars($link['rel'], ENT_QUOTES, 'UTF-8'); ?>" />
<?php } ?>
<?php } ?>
<!-- Analytic tools -->
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<?php if (isset($basel_styles_status)) { ?>
<!-- Custom Color Scheme -->
<style id="basel-color-scheme"><?php echo $basel_styles_cache; ?></style>
<?php } ?>
<?php if (isset($basel_typo_status)) { ?>
<!-- Custom Fonts -->
<style id="basel-fonts"><?php echo $basel_fonts_cache; ?></style>
<?php } ?>
<?php if ($direction == 'rtl') { ?>
<link href="catalog/view/theme/basel/stylesheet/rtl.css" rel="stylesheet">
<?php } ?>
<?php if ($basel_custom_css_status) { ?>
<!-- Custom CSS -->
<style id="basel-custom-css">
<?php echo $basel_custom_css; ?>
</style>
<?php } ?>
<?php if ($basel_custom_js_status) { ?>
<!-- Custom Javascript -->
<script>
<?php echo $basel_custom_js; ?>
</script>
<?php } ?>


<meta name="facebook-domain-verification" content="2l0m7tod4qkunyfnlf9zs460ibnp3k" />



<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '554649192799698');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=554649192799698&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->



</head>
<body class="<?php echo $class; ?><?php echo $basel_body_class; ?>">
<?php require_once('catalog/view/theme/basel/template/common/mobile-nav.tpl'); ?>
<div class="outer-container main-wrapper">
<?php if ($notification_status) { ?>
<div class="top_notificaiton">
  <div class="container<?php if ($top_promo_close) echo ' has-close'; ?> <?php echo $top_promo_width; ?> <?php echo $top_promo_align; ?>">
    <div class="table">
    <div class="table-cell w100"><div class="ellipsis-wrap"><?php echo $top_promo_text; ?></div></div>
    <?php if ($top_promo_close) { ?>
    <div class="table-cell text-right">
    <a onClick="addCookie('basel_top_promo', 1, 30);$(this).closest('.top_notificaiton').slideUp();" class="top_promo_close">&times;</a>
    </div>
    <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
<?php require_once('catalog/view/theme/basel/template/common/headers/' . $basel_header . '.tpl'); ?>
<!-- breadcrumb -->
<div class="breadcrumb-holder">
<div class="container">
<span id="title-holder">&nbsp;</span>
<div class="links-holder">
<a class="basel-back-btn" onClick="history.go(-1); return false;"><i></i></a><span>&nbsp;</span>
</div>
</div>
</div>
<div class="container">
<?php echo $position_top; ?>
</div>
