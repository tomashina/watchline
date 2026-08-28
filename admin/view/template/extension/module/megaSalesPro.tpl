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

          <ul class="nav nav-tabs">

                        <li class="active"><a data-toggle="tab" href="#tab-active-sales"><?php echo $text_all_sales; ?></a></li>

                        <li class=""><a data-toggle="tab" href="#tab-new-sale"><?php echo $text_add_new_sale; ?></a></li>

                        <li class=""><a data-toggle="tab" href="#tab-remove-all"><?php echo $text_remove_all_sales_tab; ?></a></li>

          </ul>

          <div class="tab-content">

              <div id="tab-active-sales" class="tab-pane active">


                  <div class="table-responsive">
                    
                        <table id="route" class="table table-striped table-bordered table-hover">

                                  <thead>

                                    <tr>

                                      <td class="text-left"><?php echo $text_date_start; ?></td>

                                      <td class="text-left"><?php echo $text_date_end; ?></td>

                                      <td class="text-left"><?php echo $text_discount; ?></td>

                                      <td class="text-left"><?php echo $text_discount_type; ?></td>

                                      <td class="text-left"><?php echo $text_customer_groups; ?></td>

                                      <td class="text-left"><?php echo $text_priority; ?></td>

                                      <td class="text-left"><?php echo $text_categories; ?></td>

                                      <td class="text-left"><?php echo $text_filters; ?></td>

                                      <td class="text-left"><?php echo $text_manufacturers; ?></td>

                                      <td class="text-left"><?php echo $text_excluded_products; ?></td>

                                      <td class="text-left"><?php echo $text_other_settings; ?></td>

                                      <td class="text-left"><?php echo $text_actions; ?></td>

                                    </tr>

                                  </thead>

                                   <tbody>

                                    <?php foreach ($all_sales as $one_sale) { ?>

                                        <tr>
                                              <td class="text-left"><?php echo $one_sale['date_start']; ?></td>
                                              <td class="text-left"><?php echo $one_sale['date_end']; ?></td>
                                              <td class="text-left"><?php echo $one_sale['discount_value']; ?></td>
                                              <td class="text-left"><?php echo $one_sale['discount_type']; ?></td>
                                              <td class="text-left">
                                                  <?php if (!empty($one_sale['customer_groups'])) { ?>
                                                      <?php foreach ($one_sale['customer_groups'] as $one_group) { ?>
                                                          <?php echo $one_group['name']; ?><br/>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </td>
                                               <td class="text-left"><?php echo $one_sale['priority']; ?></td>
                                              <td class="text-left">
                                                  <?php if (empty($one_sale['categories'])) { ?><?php echo $text_all; ?><?php } else { ?>
                                                      <?php foreach ($one_sale['categories'] as $one_category) { ?>
                                                          <?php if (!empty($one_category['path'])) { echo $one_category['path'].' >> '; } ?><?php echo $one_category['name']; ?><br/>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </td>
                                              <td class="text-left">
                                                  <?php if (empty($one_sale['filters'])) { ?><?php echo $text_all; ?><?php } else { ?>
                                                      <?php foreach ($one_sale['filters'] as $one_filter) { ?>
                                                          <?php echo $one_filter['group'].' >> '.$one_filter['name']; ?><br/>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </td>
                                              <td class="text-left">
                                                <?php if (empty($one_sale['manufacturers'])) { ?><?php echo $text_all; ?><?php } else { ?>
                                                      <?php foreach ($one_sale['manufacturers'] as $one_manufacturer) { ?>
                                                          <?php echo $one_manufacturer['name']; ?><br/>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </td>
                                              <td class="text-left">
                                                  <?php if (!empty($one_sale['excluded_products'])) { ?>
                                                      <?php foreach ($one_sale['excluded_products'] as $one_product) { ?>
                                                          <?php echo $one_product['name']; ?><br/>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </td>

                                              <td class="text-left">
                                                  <?php echo $text_exclude_child_label; ?>: <?php if ($one_sale['exclude_child']==1) { echo $text_yes; } else { echo $text_no; } ?><br/>
                                                  <?php echo $text_remove_previous_label; ?>: <?php if ($one_sale['remove_individual_specials']==1) { echo $text_yes; } else { echo $text_no; } ?>
                                              </td>

                                              <td class="text-right">
                                                  <a href="<?php echo $one_sale['edit']; ?>" data-toggle="tooltip" title="<?php echo $text_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                              </td>
                                        </tr>
                                    <?php } ?>
                                  </tbody>

                          </table>

                          <?php if (empty($all_sales)) { ?>
                            <p><?php echo $text_no_sales; ?></p>
                          <?php } ?>
                  </div>  
              </div>

              <!-- new SALE -->
              <div id="tab-new-sale" class="tab-pane">

                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_date_start; ?></label>
                              <div class="col-sm-10">
                                  <input type="text" autocomplete="off" autocomplete="false" name="megasale_start" value="" class="datepick"> <?php echo $text_date_start_must; ?>
                              </div>
                            </div>


                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_date_end; ?></label>
                              <div class="col-sm-10">
                                  <input type="text" name="megasale_end" autocomplete="off" autocomplete="false" value="" class="datepick"> <?php echo $text_date_end_must; ?>
                              </div>
                            </div>


                            <div class="form-group required">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_discount; ?></label>
                              <div class="col-sm-10">
                                  <input type="text" name="megasale_discount_value" autocomplete="off" autocomplete="false" value="">
                              </div>
                            </div>


                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_discount_type; ?></label>
                              <div class="col-sm-10">
                                  <select name="megasale_discount_type">
                                        <option value="percent"><?php echo $text_percent; ?></option>
                                        <option value="value"><?php echo $text_value_off; ?></option>
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
                                            <input class="customer_group" type="checkbox" name="megasale_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>"> <?php echo $customer_group['name']; ?><br/>
                                        <?php } ?>
                                        </div>
                                       <?php } ?>
                              </div>
                            </div>

<!-- product category -->                           

                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_product_category; ?></label>
                              <div class="col-sm-10">

                                  <input type="checkbox" name="enable_category_filter" id="enable_category_filter" value="1"> <?php echo $text_for_categories; ?>

                                  <div id="show_categories" class="top-pad" style="display: none;">
                                      <?php if ( (isset($category_list)) && (count($category_list)>0) ) { ?>
                                          <input type="checkbox" name="select_all_categories" value="all" id="select_all_categories"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                          <div class="list-container">
                                            <?php foreach ($category_list as $category) { ?>
                                                    <input class="category" type="checkbox" name="megasale_product_category[]" value="<?php echo $category['category_id']; ?>"> <?php echo $category['name']; ?><br/>
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

                                <input type="checkbox" name="enable_manufacturer_filter" id="enable_manufacturer_filter" value="1"> <?php echo $text_for_manufacturers; ?>

                                  <div id="show_manufacturers" class="top-pad" style="display: none;">
                                   <?php if ( (isset($manufacturer_list)) && (count($manufacturer_list)>0) ) { ?>
                                      <input type="checkbox" name="select_all_manufacturers" value="all" id="select_all_manufacturers"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                      <div class="list-container">
                                      <?php foreach ($manufacturer_list as $manufacturer) { ?>
                                         <input class="manufacturer" type="checkbox" name="megasale_product_manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>"> <?php echo $manufacturer['name']; ?><br/>       
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
                                <input type="checkbox" name="enable_filter_filter" id="enable_filter_filter" value="1"> <?php echo $text_for_filters; ?>

                                  <div id="show_filters" class="top-pad" style="display: none;">
                                          <?php if ( (isset($filter_list)) && (count($filter_list)>0) ) { ?>
                                              <input type="checkbox" name="select_all_filters" value="all" id="select_all_filters"> <b><?php echo $text_select_all; ?></b><br/><br/>
                                              <div class="list-container">
                                              <?php foreach ($filter_list as $filter) { ?>
                                                  <input class="filter" type="checkbox" name="megasale_product_filter[]" value="<?php echo $filter['filter_id']; ?>"> <?php echo $filter['group']; ; ?> > <?php echo $filter['name']; ; ?><br/> 
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
                          <?php foreach ($products as $product) { ?>
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
                                          <option value="0">0</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                          <option value="6">6</option>
                                          <option value="7">7</option>
                                          <option value="8">8</option>
                                          <option value="9">9</option>
                                      </select> <?php echo $text_lower_higher; ?>
                              </div>
                            </div>

                    <div class="form-group" id="price_rounder">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_round_prices; ?><br/></label>
                              <div class="col-sm-10">
                                  <input type="checkbox" name="megasale_round_prices" value="1" checked><br/><?php echo $text_round_example; ?>
                              </div>
                    </div>

                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_remove_individual_sales; ?></label>
                              <div class="col-sm-10">
                                  <input type="checkbox" name="megasale_remove_individual_sales" value="1"> <?php echo $text_specials_will_be_removed; ?>
                              </div>
                            </div>

              </div>



              <div id="tab-remove-all" class="tab-pane">
                    <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-width"><?php echo $text_remove_all_sales; ?></label>
                              <div class="col-sm-10">
                                  <input type="checkbox" name="clear_all_sales" value="1" onclick="return confirm('<?php echo $text_are_you_sure; ?>');" >
                              </div>
                   </div>
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
      // Animation complete.
    });
  });

  $( "#enable_manufacturer_filter" ).click(function() {
    $( "#show_manufacturers" ).toggle( "slow", function() {
      // Animation complete.
    });
  });

  $( "#enable_filter_filter" ).click(function() {
    $( "#show_filters" ).toggle( "slow", function() {
      // Animation complete.
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