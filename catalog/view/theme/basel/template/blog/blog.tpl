<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-md-9 col-sm-8'; ?>
    <?php } else { ?>
    <?php $class = 'col-md-8 col-md-offset-2'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    <div class="blog blog_post">
    
    <?php if($main_thumb && $blogsetting_post_thumb){ ?>
    <div class="main_thumb">
    <img src="<?php echo htmlspecialchars($main_thumb_url, ENT_QUOTES, 'UTF-8'); ?>" width="<?php echo (int)$img_width; ?>" height="<?php echo (int)$img_height; ?>" alt="<?php echo htmlspecialchars($heading_title, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($heading_title, ENT_QUOTES, 'UTF-8'); ?>" fetchpriority="high" decoding="async" />
    <?php if($post_date_added_status){ ?>
    <div class="date_added">
    <span class="day"><?php echo date("d",strtotime($date_added_full));?></span>
    <b class="month"><?php echo date("M",strtotime($date_added_full));?></b>
    </div>
    <?php } ?>
    </div>
    <?php } ?>
    
	<h1 id="page-title" class="contrast-font"><?php echo $heading_title; ?></h1>
	
    <div class="blog_stats">
	<?php if($post_author_status){ ?><i><?php echo $text_posted_by; ?>: <?php echo $author; ?></i><?php } ?>
	<?php if($post_page_view_status){ ?><i><?php echo $text_read; ?>: <?php echo $new_read_counter_value; ?></i><?php } ?>
	<?php if($post_comments_count_status){ ?><i><?php echo $text_comments; ?>: <?php echo $comment_total; ?></i><?php } ?>
	</div>
    
    <div class="main_description">
	<?php echo $description; ?>
    </div>
    
    <?php if ($tags) { ?>
	<p class="post_tags">
    Tagovi:
	<?php for ($i = 0; $i < count($tags); $i++) { ?>
	<?php if ($i < (count($tags) - 1)) { ?>
	<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>, 
	<?php } else { ?>
	<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
	<?php } ?>
	<?php } ?>
	</p>
	<?php } ?>
	
    <?php if($share_status){ ?>
	<div class="lg-share">
    <div class="social-icons round inversed">
    <a class="icon facebook fb_share external" rel="nofollow"><i class="fa fa-facebook"></i></a>
    <a class="icon twitter twitter_share external" rel="nofollow"><i class="fa fa-twitter"></i></a>
    <a class="icon google google_share external" rel="nofollow"><i class="icon-google-plus"></i></a>
    <a class="icon pinterest pinterest_share external" rel="nofollow"><i class="fa fa-pinterest"></i></a>
    <a class="icon vk vk_share external" rel="nofollow"><i class="fa fa-vk"></i></a>
    </div>
    </div>
    <?php } ?>
	
    <!-- Related Products -->
    <?php if ($products) { ?>
      <h3 class="section-title"><b><?php echo $text_related_products; ?></b></h3>
        <div class="grid-holder grid grid<?php echo $rel_prod_per_row; ?>" style="margin:0px">
        <?php foreach ($products as $product) { ?>
        <?php require('catalog/view/theme/basel/template/product/single_product.tpl'); ?>
        <?php } ?>
      </div>
      <?php } ?>
	 <!-- Related Products End -->
     
     
     
     <?php if ($related_blogs) { ?>
		<h3 class="section-title"><b><?php echo $text_related_blog; ?></b></h3>
        <div class="grid-holder grid<?php echo $rel_per_row; ?>">
            <?php foreach ($related_blogs as $blog) { ?>
            <div class="item single-blog related">
                <?php if(($blog['image']) && ($rel_thumb_status)){ ?>
                <div class="banner_wrap hover-zoom hover-darken">
				<img class="zoom_image" src="<?php echo $blog['image']; ?>" alt="<?php echo $blog['title']; ?>" title="<?php echo $blog['title']; ?>" />
                <a href="<?php echo $blog['href']; ?>" class="effect-holder"></a>
                <?php if($date_added_status){ ?>
                <div class="date_added">
                <span class="day"><?php echo date("d",strtotime($blog['date_added_full']));?></span>
                <b class="month"><?php echo date("M",strtotime($blog['date_added_full']));?></b>
                </div>
                <?php } ?>
                <?php if ($blog['tags']) { ?>
                <div class="tags-wrapper">
                <div class="tags primary-bg-color">
                <?php $i = 0; foreach ($blog['tags'] as $tag) { ?><a href="index.php?route=extension/blog/home&tag=<?php echo trim($tag); ?>"><?php echo trim($tag); ?></a><?php if (++$i == 2) break; } ?>
                </div>
                </div>
                <?php } ?>
                </div>
				<?php } ?>
                <div class="summary">
                <h3 class="blog-title"><a href="<?php echo $blog['href']; ?>"><?php echo $blog['title']; ?></a></h3>
                <div class="blog_stats">
                <?php if($author_status){ ?><i><?php echo $text_posted_by; ?>: <?php echo $blog['author']; ?></i><?php } ?>
				<?php if($comments_count_status){ ?><i><?php echo $text_comments; ?>: <?php echo $blog['comment_total']; ?></i><?php } ?>
                <?php if($page_view_status){ ?><i><?php echo $text_read; ?>: <?php echo $blog['count_read']; ?></i><?php } ?>
                </div>
				<p class="short-description"><?php echo $blog['short_description']; ?></p>
                <a class="u-lined" href="<?php echo $blog['href']; ?>"><?php echo $text_read_more; ?></a>
                </div>
               </div>
			<?php } ?>            
		</div>
	<?php } ?>
	 <!-- Related Blog End -->
	 
  
      
      </div>
     
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>


<script><!--

   
// Sharing buttons
var share_url = encodeURIComponent(window.location.href);
var page_title = '<?php echo $heading_title ?>';
<?php if ($main_thumb) { ?>
var thumb = '<?php echo $main_thumb ?>';
<?php } ?>
$('.fb_share').attr("href", 'https://www.facebook.com/sharer/sharer.php?u=' + share_url + '');
$('.twitter_share').attr("href", 'https://twitter.com/intent/tweet?source=' + share_url + '&text=' + page_title + ': ' + share_url + '');
$('.google_share').attr("href", 'https://plus.google.com/share?url=' + share_url + '');
$('.pinterest_share').attr("href", 'http://pinterest.com/pin/create/button/?url=' + share_url + '&media=' + thumb + '&description=' + page_title + '');
$('.vk_share').attr("href", 'http://vkontakte.ru/share.php?url=' + share_url + '');
</script>

<?php if (!empty($blog_json_ld)) { ?>
<script type="application/ld+json"><?php echo $blog_json_ld; ?></script>
<?php } ?>
<?php echo $footer; ?>
