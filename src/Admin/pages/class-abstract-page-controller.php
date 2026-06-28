<?php

namespace WooSearch\Admin\Pages;

class Abstract_Page_Controller {

	public static function render_page(string $page_filepath, $data = array()) {
		extract($data);
		require_once $page_filepath;
	}
}