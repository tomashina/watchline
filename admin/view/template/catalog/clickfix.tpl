<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<link type="text/css" href="view/stylesheet/stylesheet2.css" rel="stylesheet" media="screen" />
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
      <h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title; ?></h1>
		
		<div class="buttons"><div id="containerx" style="margin: 1px;width: 200px;height: 21px;position: relative;"></div></div>
	</div> 
    <div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		   <table class="list">
            <thead>
              <tr>
                <td class="left" width="200">SEO Feature</td>
				<td class="left">About</td>
				<td class="right" width="220">Action</td>
              </tr>
            </thead>
            
			<tbody>
              <tr>
                <td class="left"><b>SEO URLs</b></td>
                <td class="left">
				
				<?php echo $aboutseourls; ?></td>
                <td class="right">
					<?php if ($fixseourls) { ?>
					<a onclick="location = '<?php echo $fixseourls; ?>'" class="button">Fix SEO URLs</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
              <tr>
                <td class="left"><b>Meta Descriptions</b></td>
                <td class="left">
				
				<?php echo $aboutmetadescription; ?></td>
                <td class="right">
					<?php if ($fixmetadescription) { ?>
					<a onclick="location = '<?php echo $fixmetadescription; ?>'" class="button">Fix Meta Descriptions</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
              <tr>
                <td class="left"><b>Meta Keywords</b></td>
                <td class="left">
				
				<?php echo $aboutmetakeyword; ?></td>
                <td class="right">
					<?php if ($fixmetakeyword) { ?>
					<a onclick="location = '<?php echo $fixmetakeyword; ?>'" class="button">Fix Meta Keywords</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
              <tr>
                <td class="left"><b>Meta Titles</b></td>
                <td class="left">
				
				<?php echo $aboutmetatitle; ?></td>
                <td class="right">
					<?php if ($fixmetatitle) { ?>
					<a onclick="location = '<?php echo $fixmetatitle; ?>'" class="button">Fix Meta Titles</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
              <tr>
                <td class="left"><b>Canonical Links</b></td>
                <td class="left">
				
				<?php echo $aboutcanonicals; ?></td>
                <td class="right">
					<?php if ($fixcanonicals) { ?>
					<a onclick="location = '<?php echo $fixcanonicals; ?>'" class="button">Fix Canonical Links</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
            <tr>
                <td class="left"><b>Multi Language SEO</b></td>
                <td class="left">
				
				<?php echo $aboutmlseo; ?></td>
                <td class="right">
					<?php if ($fixmlseo) { ?>
					<a onclick="location = '<?php echo $fixmlseo; ?>'" class="button">Fix Multi Language SEO</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
            <tr>
                <td class="left"><b>Rich Snippets & Social SEO</b></td>
                <td class="left">
				
				<?php echo $aboutrichsnippets; ?></td>
                <td class="right">
					<?php if ($fixrichsnippets) { ?>
					<a onclick="location = '<?php echo $fixrichsnippets; ?>'" class="button">Fix Rich Snippets & Social SEO</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
            <tr>
                <td class="left"><b>SEO Pagination</b></td>
                <td class="left">
				
				<?php echo $aboutseopagination; ?></td>
                <td class="right">
					<?php if ($fixseopagination) { ?>
					<a onclick="location = '<?php echo $fixseopagination; ?>'" class="button">Fix SEO Pagination</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
            <tr>
                <td class="left"><b>Extended SEO</b></td>
                <td class="left">
				
				<?php echo $aboutextendedseo; ?></td>
                <td class="right">
					<?php if ($fixextendedseo) { ?>
					<a onclick="location = '<?php echo $fixextendedseo; ?>'" class="button">Fix Extended SEO</a>
					<?php } else { ?>
					<a class="button" style="background:green">OK</a>
					<?php } ?>
				</td>
              </tr>
            </tbody>
			
            
          </table>
	</form>
	</div>
   </div>
</div>
<script src="view/javascript/progressbar.js"></script>
<script type="text/javascript">
var bar = new ProgressBar.Line(containerx, {
  strokeWidth: 10,
  easing: 'easeInOut',
  duration: 3000,
  color: '#FFEA82',
  trailColor: '#bbb',
  trailWidth: 10,
  svgStyle: {width: '100%', height: '100%'},
  text: {
    style: {
      // Text color.
      // Default: same as stroke color (options.color)
      color: '#555555',
	  
      position: 'absolute',
      right: '50px',
      top: '0',
      padding: 0,
      margin: 10,
      transform: null
    },
    autoStyleContainer: false
  },
  from: {color: '#00EA82'},
  to: {color: '#006A5A'},
  step: (state, bar) => {
    bar.setText('<b>SEO Meter: ' + Math.floor(bar.value() * 100) + ' %</b>');
  }
});

bar.animate(<?php echo $seometer; ?>); 
</script>
<?php echo $footer; ?>