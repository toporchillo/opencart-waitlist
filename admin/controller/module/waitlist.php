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

class ControllerModuleWaitlist extends Controller {

    private $error = array();

    public function index()
    {

        // Load dependencies
        $this->load->language('module/waitlist');
        $this->load->model('setting/setting');


        // Save received data if exists
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validate()) {

            $this->model_setting_setting->editSetting('waitlist', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        // Get subscribers list
        $this->load->model('tool/waitlist');
        $this->load->model('catalog/product');
        $this->load->model('sale/customer');

        $this->data['products'] = array();
        $products = $this->model_tool_waitlist->getProductIds();

        $this->data['count_products'] = 0;
        foreach ($products as $product) {
            $this->data['count_products']++;

            $customers = array();

            $customers_by_product_id = $this->model_tool_waitlist->getCustomersByProductId($product['product_id']);
            foreach ($customers_by_product_id as $customer_by_product_id) {
                $customer_info = $this->model_sale_customer->getCustomer($customer_by_product_id['customer_id']);
                $customer_subscribe_date['subscribe_date'] = $this->model_tool_waitlist->getSubscribeDate($product['product_id'], $customer_by_product_id['customer_id']);
                $customers[] = array_merge($customer_info, $customer_subscribe_date);
            }

            $this->data['products'][] = array(
                'customers' => $customers,
                'info'      => $this->model_catalog_product->getProduct($product['product_id'])
            );
        }

        // Set view variables
        $this->document->setTitle($this->language->get('heading_title'));
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_module'] = $this->language->get('button_add_module');
        $this->data['button_remove'] = $this->language->get('button_remove');

        $this->data['entry_status_out_of_stock'] = $this->language->get('entry_status_out_of_stock');
        $this->data['entry_status_alert'] = $this->language->get('entry_status_alert');
        $this->data['entry_enabled'] = $this->language->get('entry_enabled');

        $this->data['token'] = $this->session->data['token'];
        $this->data['action'] = $this->url->link('module/waitlist', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['waitlist_config_enabled'] = $this->config->get('waitlist_enabled');

        // Set errors if exists
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        // Generate breadcrumbs
        $this->_breadcrumbs();

        // Load child components
        $this->template = 'module/waitlist.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        // Output
        $this->response->setOutput($this->render());
    }

    // Config validation
    private function _validate()
    {
        if (!$this->user->hasPermission('modify', 'module/waitlist')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function _breadcrumbs()
    {
        // Load dependencies
        $this->load->language('module/waitlist');


        // Set view variables
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/waitlist', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
    }
}
?>
