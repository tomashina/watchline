<?php echo $header; ?><?php echo $column_left; ?>


<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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

    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
          <input type="hidden" name="edit_sale_id" value="<?php echo $sale_data['sale_id']; ?>">

                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_date_start; ?></label>
                              <div class="col-sm-10">
                                  <input type="text" autocomplete="off" autocomplete="false" name="megasale_start" value="<?php if (isset($sale_data['date_start'])) { echo $sale_data['date_start']; } ?>" class="datepick"> <?php echo $text_date_start_must; ?>
                              </div>
                            </div>


                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_date_end; ?></label>
                              <div class="col-sm-10">
                                  <input type="text" autocomplete="off" autocomplete="false" name="megasale_end" value="<?php if (isset($sale_data['date_end'])) { echo $sale_data['date_end']; } ?>" class="datepick"> <?php echo $text_date_end_must; ?>
                              </div>
                            </div>


                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_discount; ?></label>
                              <div class="col-sm-10">
                                  <input type="text" autocomplete="off" autocomplete="false" name="megasale_discount_value" value="<?php if (isset($sale_data['discount_value'])) { echo $sale_data['discount_value']; } ?>">
                              </div>
                            </div>


                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_discount_type; ?></label>
                              <div class="col-sm-10">
                                  <select name="megasale_discount_type">
                                        <option value="percent" <?php if ( (isset($sale_data['discount_type'])) && ($sale_data['discount_type']=="percent") ) { echo ' selected '; } ?>><?php echo $text_percent; ?></option>
                                        <option value="value" <?php if ( (isset($sale_data['discount_type'])) && ($sale_data['discount_type']=="value") ) { echo ' selected '; } ?>><?php echo $text_value_off; ?></option>
                                  </select>
                              </div>
                            </div>

                            
<!-- customer group -->

                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_customer_group; ?></label>
                              <div class="col-sm-10">
                                  
                                      <?php if ( (isset($customer_group_list)) && (count($customer_group_list)>0) ) { ?>
                                        <input type="checkbox" name="select_all_customer_groups" value="all" id="select_all_customer_groups"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                        <div class="list-container">
                                        <?php foreach ($customer_group_list as $customer_group) { ?>
                                            <input class="customer_group" type="checkbox" name="megasale_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if ( (!empty($sale_data['selected_customer_groups'])) && (in_array($customer_group['customer_group_id'], $sale_data['selected_customer_groups'])) ) { echo ' checked '; } ?>> <?php echo $customer_group['name']; ?><br/>
                                        <?php } ?>
                                        </div>
                                       <?php } ?>
                              </div>
                            </div>

<!-- product category -->                           

                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_product_category; ?></label>
                              <div class="col-sm-10">

                                  <input type="checkbox" name="enable_category_filter" id="enable_category_filter" value="1" <?php if (!empty($sale_data['selected_categories'])) { echo ' checked '; } ?> > <?php echo $text_for_categories; ?>

                                  <div id="show_categories" class="top-pad" <?php if (empty($sale_data['selected_categories'])) { ?> style="display: none;" <?php } ?>>
                                      <?php if ( (isset($category_list)) && (count($category_list)>0) ) { ?>
                                          <input type="checkbox" name="select_all_categories" value="all" id="select_all_categories"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                          <div class="list-container">
                                            <?php foreach ($category_list as $category) { ?>
                                                    <input class="category" type="checkbox" name="megasale_product_category[]" value="<?php echo $category['category_id']; ?>" <?php if ( (!empty($sale_data['selected_categories'])) && (in_array($category['category_id'], $sale_data['selected_categories'])) ) { echo ' checked '; } ?> > <?php echo $category['name']; ?><br/>
                                            <?php } ?>
                                          </div>
                                       <?php } ?> 

                                      <br/><input type="checkbox" name="exclude_child" value="1"> <b><?php echo $text_exclude_child; ?></b>
                                  </div>

                                  
                              </div>
                            </div>

 <!-- manufacturer -->

                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_manufacturer; ?></label>
                              <div class="col-sm-10">

                                <input type="checkbox" name="enable_manufacturer_filter" id="enable_manufacturer_filter" value="1" <?php if (!empty($sale_data['selected_manufacturers'])) { echo ' checked '; } ?> > <?php echo $text_for_manufacturers; ?>

                                  <div id="show_manufacturers" class="top-pad" <?php if (empty($sale_data['selected_manufacturers'])) { ?> style="display: none;" <?php } ?> >
                                   <?php if ( (isset($manufacturer_list)) && (count($manufacturer_list)>0) ) { ?>
                                      <input type="checkbox" name="select_all_manufacturers" value="all" id="select_all_manufacturers"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                      <div class="list-container">
                                      <?php foreach ($manufacturer_list as $manufacturer) { ?>
                                         <input class="manufacturer" type="checkbox" name="megasale_product_manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if ( (!empty($sale_data['selected_manufacturers'])) && (in_array($manufacturer['manufacturer_id'], $sale_data['selected_manufacturers'])) ) { echo ' checked '; } ?> > <?php echo $manufacturer['name']; ?><br/>       
                                      <?php } ?>
                                      </div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>

 <!-- product filter -->

                    <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_product_filter; ?></label>
                              <div class="col-sm-10">
                                <input type="checkbox" name="enable_filter_filter" id="enable_filter_filter" value="1" <?php if (!empty($sale_data['selected_filters'])) { echo ' checked '; } ?> > <?php echo $text_for_filters; ?>

                                  <div id="show_filters" class="top-pad" <?php if (empty($sale_data['selected_filters'])) { ?> style="display: none;" <?php } ?>>
                                          <?php if ( (isset($filter_list)) && (count($filter_list)>0) ) { ?>
                                              <input type="checkbox" name="select_all_filters" value="all" id="select_all_filters"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                              <div class="list-container">
                                              <?php foreach ($filter_list as $filter) { ?>
                                                  <input class="filter" type="checkbox" name="megasale_product_filter[]" value="<?php echo $filter['filter_id']; ?>" <?php if ( (!empty($sale_data['selected_filters'])) && (in_array($filter['filter_id'], $sale_data['selected_filters'])) ) { echo ' checked '; } ?> > <?php echo $filter['group']; ; ?> > <?php echo $filter['name']; ; ?><br/> 
                                              <?php } ?>
                                              </div>
                                          <?php } ?>
                                  </div>
                              </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="(Autocomplete)"><?php echo $text_products_to_exclude; ?></span></label>
                      <div class="col-sm-10">
                        <input type="text" name="product_name" value="" placeholder="<?php echo $text_products_to_exclude; ?>" id="input-product" class="form-control" />
                        <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                          <?php foreach ($sale_data['excluded_products'] as $product) { ?>
                          <div id="featured-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                            <input type="hidden" name="product[]" value="<?php echo $product['product_id']; ?>" />
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    

 <!-- priority -->


                    <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_priority; ?></label>
                              <div class="col-sm-10">
                                  <select name="megasale_priority">
                                          <option value="0" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==0) ) { echo ' selected '; } ?> >0</option>
                                          <option value="1" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==1) ) { echo ' selected '; } ?> >1</option>
                                          <option value="2" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==2) ) { echo ' selected '; } ?> >2</option>
                                          <option value="3" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==3) ) { echo ' selected '; } ?> >3</option>
                                          <option value="4" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==4) ) { echo ' selected '; } ?> >4</option>
                                          <option value="5" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==5) ) { echo ' selected '; } ?> >5</option>
                                          <option value="6" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==6) ) { echo ' selected '; } ?> >6</option>
                                          <option value="7" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==7) ) { echo ' selected '; } ?> >7</option>
                                          <option value="8" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==8) ) { echo ' selected '; } ?> >8</option>
                                          <option value="9" <?php if ( (isset($sale_data['priority'])) && ($sale_data['priority']==9) ) { echo ' selected '; } ?> >9</option>
                                      </select> <?php echo $text_lower_higher; ?>
                              </div>
                            </div>

                    <div class="form-group" id="price_rounder">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_round_prices; ?><br/></label>
                              <div class="col-sm-10">
                                  <input type="checkbox" name="megasale_round_prices" value="1" <?php if ( (isset($sale_data['round_prices'])) && ($sale_data['round_prices']==1) ) { echo ' checked '; } ?> ><br/><?php echo $text_round_example; ?>
                              </div>
                    </div>

                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_remove_individual_sales; ?></label>
                              <div class="col-sm-10">
                                  <input type="checkbox" name="megasale_remove_individual_sales" value="1" <?php if ( (isset($sale_data['remove_individual_specials'])) && ($sale_data['remove_individual_specials']==1) ) { echo ' checked '; } ?> > <?php echo $text_specials_will_be_removed; ?>
                              </div>
                            </div>

                            <br/><br/><br/><br/><br/><br/><br/>

                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_remove_sale; ?></label>
                              <div class="col-sm-10">
                                  <a href="<?php echo $sale_data['delete_url']; ?>" onclick="return confirm('<?php echo $text_are_you_sure; ?>');" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> <?php echo $text_will_delete; ?>
                              </div>
                            </div>

             

<script type="text/javascript"><!--
$('input[name=\'product_name\']').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  select: function(item) {
    $('input[name=\'product_name\']').val('');
    
    $('#featured-product' + item['value']).remove();
    
    $('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product[]" value="' + item['value'] + '" /></div>');  
  }
});
  
$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script>

  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>

  $(function() {

    $("#select_all_customer_groups").click(function() {
      $(".customer_group").prop("checked", this.checked);
    });

    $('.customer_group').click(function() {
      if ($('.customer_group:checked').length == $('.customer_group').length) {
        $('#select_all_customer_groups').prop('checked', true);
      } else {
        $('#select_all_customer_groups').prop('checked', false);
      }
    });



    $("#select_all_categories").click(function() {
      $(".category").prop("checked", this.checked);
    });

    $('.category').click(function() {
      if ($('.category:checked').length == $('.category').length) {
        $('#select_all_categories').prop('checked', true);
      } else {
        $('#select_all_categories').prop('checked', false);
      }
    });


    $("#select_all_manufacturers").click(function() {
      $(".manufacturer").prop("checked", this.checked);
    });

    $('.manufacturer').click(function() {
      if ($('.manufacturer:checked').length == $('.manufacturer').length) {
        $('#select_all_manufacturers').prop('checked', true);
      } else {
        $('#select_all_manufacturers').prop('checked', false);
      }
    });

    $("#select_all_filters").click(function() {
      $(".filter").prop("checked", this.checked);
    });

    $('.filter').click(function() {
      if ($('.filter:checked').length == $('.filter').length) {
        $('#select_all_filters').prop('checked', true);
      } else {
        $('#select_all_filters').prop('checked', false);
      }
    });



    $( ".datepick" ).datepicker({dateFormat: 'yy-mm-dd'});

    $('select[name="megasale_discount_type"]').change(function(e) {
      var target = $('select[name="megasale_discount_type"]').val();

      if (target=='percent') {
        $( "#price_rounder" ).show();
      } else {
        $( "#price_rounder" ).hide();
      }
    });
  });

  $( "#enable_category_filter" ).click(function() {
    $( "#show_categories" ).toggle( "slow", function() {
    });
  });

  $( "#enable_manufacturer_filter" ).click(function() {
    $( "#show_manufacturers" ).toggle( "slow", function() {
    });
  });

  $( "#enable_filter_filter" ).click(function() {
    $( "#show_filters" ).toggle( "slow", function() {
    });
  });
  </script>

  <style>
    #ui-datepicker-div {
      background: white;
      padding: 8px;
      border: 1px solid #eee;
    }

    #ui-datepicker-div th, #ui-datepicker-div td {
      padding: 3px;
    }

    .ui-datepicker-next { margin-left: 10px; } 

    .list-container {
        height: 150px;
        overflow-y: scroll;
        border: 1px solid #eee;
        padding: 4px;
        width: 50%;
    }

    .top-pad {
      padding-top: 15px;
      padding-left: 15px;
    }
  </style>  

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>