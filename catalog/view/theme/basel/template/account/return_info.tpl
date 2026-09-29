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
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1 id="page-title"><?php echo $heading_title; ?></h1>
      <table class="table table-bordered margin-b30">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_return_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;"><b><?php echo $text_return_id; ?></b> #<?php echo $return_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
            <td class="text-left" style="width: 50%;"><b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_ordered; ?></b> <?php echo $date_ordered; ?></td>
          </tr>
        </tbody>
      </table>
      <h2><?php echo $text_return_products_title; ?></h2>
      <table class="table table-bordered margin-b30">
        <thead>
          <tr>
            <td class="text-left"><?php echo $entry_product_code; ?></td>
            <td class="text-right"><?php echo $column_quantity; ?></td>
            <td class="text-right"><?php echo $column_price; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($return_products as $return_product) { ?>
          <tr>
            <td class="text-left"><?php echo $return_product['code']; ?></td>
            <td class="text-right"><?php echo $return_product['quantity']; ?></td>
            <td class="text-right"><?php echo $return_product['price']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php if ($refund_iban) { ?>
      <table class="table table-bordered margin-b30">
        <thead>
          <tr>
            <td class="text-left"><?php echo $entry_refund_iban; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $refund_iban; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <h2><?php echo $text_reason; ?></h2>
      <table class="list table table-bordered margin-b30">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_reason; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_opened; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $reason; ?></td>
            <td class="text-left"><?php echo $opened; ?></td>
            <td class="text-left"><?php echo $action; ?></td>
          </tr>
        </tbody>
      </table>
      <?php if ($comment) { ?>
      <table class="table table-bordered margin-b30">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <h2><?php echo $text_history; ?></h2>
      <table class="table table-bordered margin-b30">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_date_added; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_status; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($histories) { ?>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="3"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
