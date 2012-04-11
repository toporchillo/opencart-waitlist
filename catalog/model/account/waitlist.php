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

class ModelAccountWaitlist extends Model {

    public function addRow($product_id, $customer_id)
    {
        $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "waitlist SET ".
                         "product_id = '" . (int) $product_id . "', ".
                         "customer_id = '" . (int) $customer_id . "', ".
                         "date_added = NOW()");
        return true;
    }

    public function issetRow($product_id, $customer_id)
    {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "waitlist WHERE ".
                                  "customer_id = '" . (int) $customer_id . "' AND ".
                                  "product_id = '" . (int) $product_id . "' ".
                                  "LIMIT 1");
        return $query->num_rows;
    }

    public function getRowsetByCustomerId($customer_id)
    {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "waitlist WHERE ".
                                  "customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function deleteRowset($product_id, $customer_id)
    {

        $this->db->query("DELETE FROM " . DB_PREFIX . "waitlist WHERE ".
                         "product_id = '" . (int) $product_id . "' AND ".
                         "customer_id = '" . (int) $customer_id . "' ".
                         "LIMIT 1");
        return true;
    }
}
