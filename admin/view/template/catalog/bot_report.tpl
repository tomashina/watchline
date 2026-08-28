<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><link type="text/css" href="view/stylesheet/stylesheet2.css" rel="stylesheet" media="screen" />
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
	<div class="buttons">
	<a onclick="location.href='<?php echo $clearlog; ?>';" class="button"  class="btn btn-danger">Clear Bots Report</a></div>    
  </div>
  <div class="content">

	 <table class="list">
          <thead>
            <tr>              
              <td class="left">Link</td>
              <td class="left">Crawl Count</td>
              <td class="left">Last Crawl Date</td>              
            </tr>
          </thead>
          <tbody>
            
            <?php if (!empty($links)) { ?>
            <?php foreach ($links as $link) { ?>
            <tr>              
              <td class="left"><?php echo $link['link']; ?></td>
              <td class="left"><?php echo $link['cnt']; ?></td>
              <td class="left"><?php echo $link['lastdate']; ?></td>                            			  
            </tr>
			<?php } ?>			
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
		</table>
		 <div class="pagination"><?php echo $pagination; ?></div>
	</div>

	<span style="color:red" class="help">*</span>	
  </div>
</div>

<?php echo $footer; ?>