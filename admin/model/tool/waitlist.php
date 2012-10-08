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


class ModelToolWaitlist extends Model {

    public function notify($product_id, $data)
    {
        // Load dependencies
        $this->language->load('mail/waitlist');
        $this->load->model('catalog/product');

        $mail = new Mail();

        // Config mail
        $mail->protocol  = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname  = $this->config->get('config_smtp_host');
        $mail->username  = $this->config->get('config_smtp_username');
        $mail->password  = $this->config->get('config_smtp_password');
        $mail->port      = $this->config->get('config_smtp_port');
        $mail->timeout   = $this->config->get('config_smtp_timeout');

        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));

        // Set alert subject
        $mail->setSubject(sprintf($this->language->get('text_title'),
                                  $this->config->get('config_name')));

        // Get product info
        $product = $this->model_catalog_product->getProduct($product_id);

        // Only for available products
        if ($product['quantity'] == '0' && $data['quantity'] > 0) {

            // Send notifications if module enabled
            if ($this->config->get('waitlist_enabled')) {

                // Get product subscribes
                $customers = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "waitlist WHERE product_id = '" . (int) $product_id . "'");

                if ($customers->num_rows) {
                    foreach ($customers->rows as $customer) {

                        // Get subscribed customer data
                        $customer_data = $this->db->query("SELECT email, firstname FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer['customer_id'] . "' AND status = '1' LIMIT 1");

                        if (strlen($customer_data->row['email']) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $customer_data->row['email'])) {

                            // Format message
                            $message  = sprintf($this->language->get('text_body_hello'), $customer_data->row['firstname']) . "\n\n";
                            $message .= $this->language->get('text_body_subscribe');
                            $message .= '"' . $product['name'] . '"' . "\n";
                            $message .= $this->language->get('text_body_hurrah') . "\n\n";
                            $message .= $this->language->get('text_body_readmore') . "\n";
                            $message .= HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $product_id . "\n\n";
                            $message .= '--' . "\n";
                            $message .= $this->config->get('config_name');

                            // Send notification
                            $mail->setText($message);
                            $mail->setTo($customer_data->row['email']);
                            $mail->send();
                        }
                    }
                }
            }

            // Reset waitlist table
            $this->removeByProductId($product_id);
        }
    }


    public function removeByProductId($product_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "waitlist WHERE product_id = '" . (int) $product_id . "'");
    }


    public function removeByCustomerId($customer_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "waitlist WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function getProductIds()
    {
        $result = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "waitlist GROUP BY product_id ORDER BY date_added");

        return $result->rows;
    }

    public function getCustomersByProductId($product_id)
    {
        $result = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "waitlist WHERE product_id = '" . (int) $product_id . "'");

        return $result->rows;
    }

    public function getSubscribeDate($product_id, $customer_id)
    {
        $result = $this->db->query("SELECT date_added FROM " . DB_PREFIX . "waitlist WHERE product_id = '" . (int) $product_id . "' AND customer_id = '" . (int) $customer_id . "' LIMIT 1");

        return $result->row['date_added'];
    }
}
