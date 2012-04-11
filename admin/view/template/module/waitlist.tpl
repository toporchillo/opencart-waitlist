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

<?php echo $footer; ?>
