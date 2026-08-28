<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
 <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Edit companyshipping Module</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_api_user; ?></label>
            <div class="col-sm-10">
             <input type="text" style="width:300px" name="cpnshipping[api_user]" value="<?php echo isset($cpnshipping['api_user']) ? $cpnshipping['api_user'] : ''; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_api_key; ?></label>
            <div class="col-sm-10">
              <input type="text" style="width:300px" name="cpnshipping[api_key]" value="<?php echo isset($cpnshipping['api_key']) ? $cpnshipping['api_key'] : ''; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-5 control-label">  <?php echo $text_info; ?></label>
            <label class="col-sm-7 control-label">  </label>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_sender_name; ?></label>
            <div class="col-sm-10">
              <input type="text" style="width:300px" name="cpnshipping[sender_name]" value="<?php echo isset($cpnshipping['sender_name']) ? $cpnshipping['sender_name'] : ''; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_sender_address; ?></label>
            <div class="col-sm-10">
               <input type="text" style="width:300px" name="cpnshipping[sender_address]" value="<?php echo isset($cpnshipping['sender_address']) ? $cpnshipping['sender_address'] : ''; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_sender_zipcode; ?></label>
            <div class="col-sm-10">
                <input type="text" style="width:300px" name="cpnshipping[sender_zipcode]" value="<?php echo isset($cpnshipping['sender_zipcode']) ? $cpnshipping['sender_zipcode'] : ''; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_sender_city; ?></label>
            <div class="col-sm-10">
                <input type="text" style="width:300px" name="cpnshipping[sender_city]" value="<?php echo isset($cpnshipping['sender_city']) ? $cpnshipping['sender_city'] : ''; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">  <?php echo $entry_sender_country; ?></label>
            <div class="col-sm-10">
                <input type="text" style="width:300px" name="cpnshipping[sender_country]" value="<?php echo isset($cpnshipping['sender_country']) ? $cpnshipping['sender_country'] : ''; ?>">
            </div>
          </div>        
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

//--></script> 
<?php echo $footer; ?>