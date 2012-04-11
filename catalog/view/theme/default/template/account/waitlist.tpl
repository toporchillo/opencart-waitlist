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
<?php echo $column_left; ?>
<?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($products) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="waitlist">
    <div class="wishlist-product">
      <table>
        <thead>
          <tr>
            <td class="remove"><?php echo $column_remove; ?></td>
            <td class="image"><?php echo $column_image; ?></td>
            <td class="name"><?php echo $column_name; ?></td>
            <td class="model"><?php echo $column_model; ?></td>
            <td class="price"><?php echo $column_price; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="remove"><input type="checkbox" name="remove[]" value="<?php echo $product['product_id']; ?>" /></td>
            <td class="image"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
              <?php } ?></td>
            <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
            <td class="model"><?php echo $product['model']; ?></td>
            <td class="price"><?php if ($product['price']) { ?>
              <div class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <s><?php echo $product['price']; ?></s> <b><?php echo $product['special']; ?></b>
                <?php } ?>
              </div>
              <?php } ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </form>
  <div class="buttons">
    <div class="left"><a href="<?php echo $back; ?>" class="button"><span><?php echo $button_back; ?></span></a></div>
    <div class="right"><a onclick="$('#waitlist').submit();" class="button"><span><?php echo $button_update; ?></span></a></div>
  </div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
