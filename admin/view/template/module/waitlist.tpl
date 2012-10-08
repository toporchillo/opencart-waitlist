<?php

/**
 * OpenCart Ukrainian Community
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License, Version 3
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@opencart.ua so we can send you a copy immediately.
 *
 * @category   OpenCart
 * @package    OCU Waitlist
 * @copyright  Copyright (c) 2011 Eugene Kuligin by OpenCart Ukrainian Community (http://opencart.ua)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License, Version 3
 */



/**
 * @category   OpenCart
 * @package    OCU Waitlist
 * @copyright  Copyright (c) 2011 Eugene Kuligin by OpenCart Ukrainian Community (http://opencart.ua)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License, Version 3
 */

?>

<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">

      <div id="tabs" class="htabs">
        <a href="#tab-subscribers" class="selected"><?php echo $this->language->get('tab_subscribers'); ?></a>
        <a href="#tab-settings"><?php echo $this->language->get('tab_settings'); ?></a>
      </div>

      <div id="tab-subscribers">
        <?php if ($count_products > 0) { ?>
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $this->language->get('text_product'); ?></td>
              <td class="left"><?php echo $this->language->get('text_quantity'); ?></td>
              <td class="left"><?php echo $this->language->get('text_available'); ?></td>
              <td class="left"><?php echo $this->language->get('text_status'); ?></td>
              <td class="left"><?php echo $this->language->get('text_subscribers'); ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
              <tr>
                <td class="left">
                  <a href="<?php echo $this->url->link('catalog/product/update', 'product_id=' . $product['info']['product_id'] . '&token=' . $token); ?>"><?php echo $product['info']['name']; ?> <?php echo $product['info']['model']; ?></a>
                </td>
                <td class="left">
                  <span style="color:<?php echo ($product['info']['quantity'] ? 'green' : 'red'); ?>"><?php echo $product['info']['quantity']; ?></span>
                </td>
                <td class="left">
                  <?php
                    $this->load->model('localisation/stock_status');
                    $this->load->model('localisation/stock_status');
                    $stock_status = $this->model_localisation_stock_status->getStockStatus($product['info']['stock_status_id']);
                  ?>
                  <?php echo $stock_status['name']; ?>
                </td>
                <td class="left">
                  <?php echo ($product['info']['status'] ? $this->language->get('text_product_on') : $this->language->get('text_product_off')); ?>
                </td>
                <td class="left">
                    <?php foreach ($product['customers'] as $customer) { ?>
                      <div>
                        <a href="<?php echo $this->url->link('sale/customer/update', 'customer_id=' . $customer['customer_id'] . '&token=' . $token); ?>"><?php echo $customer['firstname'] ?> <?php echo $customer['lastname'] ?></a>
                          &nbsp;&rarr;&nbsp;
                        <?php echo $customer['subscribe_date']; ?>
                      </div>
                    <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } else { ?>
          <?php echo $this->language->get('text_empty'); ?>
        <?php } ?>
      </div>
      <div id="tab-settings">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
              <tr>
                <td>
                  <?php echo $entry_enabled; ?>
                </td>
                <td>
                  <select name="waitlist_enabled">
                     <option value="1" <?php echo ( $waitlist_config_enabled ? 'selected="selected"' : false); ?>><?php echo $text_yes; ?></option>
                    <option value="0" <?php echo ( !$waitlist_config_enabled ? 'selected="selected"' : false); ?>><?php echo $text_no; ?></option>
                  </select>
                </td>
              </tr>
            </table>
          </form>
      </div>
  </div>
</div>


<script type="text/javascript">
 <!--
 $('#tabs a').tabs();
 //-->
 </script>

<?php echo $footer; ?>
