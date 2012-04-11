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

class ControllerAccountWaitlist extends Controller {

    public function index()
    {
        // If wait list is disabled, redirect to home page
        if (!$this->config->get('waitlist_enabled')) {
            $this->redirect($this->url->link('account/account'));
        }

        // If customer not logged, redirect to login page
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/waitlist', '', 'SSL');
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/waitlist');
        $this->load->model('account/waitlist');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        // Remove product from wait list
        if (isset($this->request->post['remove'])) {

            foreach ($this->request->post['remove'] as $product_id) {
                $this->model_account_waitlist->deleteRowset($product_id, $this->customer->getId());
            }

            $this->redirect($this->url->link('account/waitlist'));
        }

        // Set view vriables
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['heading_title']   = $this->language->get('heading_title');
        $this->data['text_empty']      = $this->language->get('text_empty');
        $this->data['column_remove']   = $this->language->get('column_remove');
        $this->data['column_image']    = $this->language->get('column_image');
        $this->data['column_name']     = $this->language->get('column_name');
        $this->data['column_model']    = $this->language->get('column_model');
        $this->data['column_price']    = $this->language->get('column_price');
        $this->data['column_cart']     = $this->language->get('column_cart');
        $this->data['button_cart']     = $this->language->get('button_cart');
        $this->data['button_update']   = $this->language->get('button_update');
        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['button_back']     = $this->language->get('button_back');
        $this->data['continue']        = $this->url->link('account/account', '', 'SSL');
        $this->data['back']            = $this->url->link('account/account', '', 'SSL');
        $this->data['action']          = $this->url->link('account/waitlist');
        $this->data['products']        = array();

        // Get waiting products
        $wait_list_products = $this->model_account_waitlist->getRowsetByCustomerId($this->customer->getId());

        foreach ($wait_list_products as $wait_list_product) {
            $product_info = $this->model_catalog_product->getProduct($wait_list_product['product_id']);

            if ($product_info) {

                // Resizing images to wishlist format, if exists
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
                } else {
                    $image = false;
                }

                // Get price
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                // Get special price
                if ((float)$product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                // Result array
                $this->data['products'][] = array(
                    'product_id' => $product_info['product_id'],
                    'thumb'      => $image,
                    'name'       => $product_info['name'],
                    'model'      => $product_info['model'],
                    'price'      => $price,
                    'special'    => $special,
                    'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                );
            }
        }

        // Generate breadcrumbs
        $this->_breadcrumbs();

        // Load template
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/waitlist.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/waitlist.tpl';
        } else {
            $this->template = 'default/template/account/waitlist.tpl';
        }

        // Load modules
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        // Output
        $this->response->setOutput($this->render());
    }

    public function add()
    {
        // Load dependencies
        $this->language->load('account/waitlist');
        $this->load->model('account/waitlist');
        $this->load->model('catalog/product');

        // Default variables
        $json['success'] = $this->language->get('undefined_error');
        $json = array();

        // Only registered users
        if ($this->customer->isLogged()) {

            if (isset($this->request->post['product_id'])) {

                $product_id = $this->request->post['product_id'];
                $customer_id = $this->customer->getId();

                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {

                    $this->model_account_waitlist->addRow($product_id, $customer_id);
                    $json['success'] = sprintf($this->language->get('text_success'),
                        $this->url->link('product/product',
                                         'product_id=' . $this->request->post['product_id']),
                                         $product_info['name'],
                                         $this->url->link('account/waitlist'));
                }
            }

        // If user is not registered or logged
        } else {
            $json['success'] = sprintf($this->language->get('text_login'),
                                       $this->url->link('account/login', '', 'SSL'),
                                       $this->url->link('account/register', '', 'SSL'),
                                       $this->url->link('account/waitlist'));
        }

        // Output
        $this->response->setOutput(json_encode($json));
    }

    private function _breadcrumbs()
    {
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/waitlist'),
            'separator' => $this->language->get('text_separator')
        );
    }
}
