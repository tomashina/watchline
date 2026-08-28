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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left">Rich Snippets</td>
            <td class="left">Enable</td>            			
          </tr>
        </thead>
        
        <tbody>
          <tr>
            <td class="left">Use Breadcrumbs RDF</td>
           	<td class="left">
				<?php if (isset($richsnippets['breadcrumbs'])) { ?>
                <input type="checkbox" name="richsnippets[breadcrumbs]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[breadcrumbs]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use Product Schema</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['product'])) { ?>
                <input type="checkbox" name="richsnippets[product]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[product]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  <tr>
            <td class="left">Use Offer Schema</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['offer'])) { ?>
                <input type="checkbox" name="richsnippets[offer]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[offer]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  <tr>
            <td class="left">Use Rating Schema</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['rating'])) { ?>
                <input type="checkbox" name="richsnippets[rating]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[rating]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  <tr>
            <td class="left">Use Structured Data for Store Info (JSON-LD)</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['store'])) { ?>
                <input type="checkbox" name="richsnippets[store]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[store]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  
        </tbody>
       
             
      </table>	  
	  <table id="module" class="list">
        <thead>
          <tr>
            <td class="left">Social SEO</td>
            <td class="left">Enable</td>            			
          </tr>
        </thead>
        
        <tbody>
          <tr>
            <td class="left">Use Twitter Card for Website with username: <input type="text" name="richsnippets[twitteruser]" value="<?php echo isset($richsnippets['twitteruser']) ? $richsnippets['twitteruser'] : ''; ?>" id="input-twitteruser; ?>" /> (optional)</td>
           	<td class="left">
				<?php if (isset($richsnippets['twittersite'])) { ?>
                <input type="checkbox" name="richsnippets[twittersite]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[twittersite]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use Twitter Card for Products</td>
           	<td class="left">
				<?php if (isset($richsnippets['twitterproduct'])) { ?>
                <input type="checkbox" name="richsnippets[twitterproduct]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[twitterproduct]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use Facebook Opengraph for Website</td>
           	<td class="left">
				<?php if (isset($richsnippets['ogsite'])) { ?>
                <input type="checkbox" name="richsnippets[ogsite]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[ogsite]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use Facebook Opengraph for Products</td>
           	<td class="left">
				<?php if (isset($richsnippets['ogproduct'])) { ?>
                <input type="checkbox" name="richsnippets[ogproduct]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[ogproduct]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use Google Publisher with Google+ ID: <input type="text" name="richsnippets[googleid]" value="<?php echo isset($richsnippets['googleid']) ? $richsnippets['googleid'] : ''; ?>" id="input-googleid; ?>" /></td>
           	<td class="left">
				<?php if (isset($richsnippets['googlepublisher'])) { ?>
                <input type="checkbox" name="richsnippets[googlepublisher]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[googlepublisher]" value="1" />
                <?php } ?></td>            
          </tr>
		  
        </tbody>
       
             
      </table>	  
    </form>
  </div>
</div>

<?php echo $footer; ?>