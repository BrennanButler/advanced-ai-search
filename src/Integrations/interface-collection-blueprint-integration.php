<?php
/**
 * Collection Blueprint Integration
 *
 * @package WooSearch\Integrations
 */

namespace WooSearch\Integrations;

interface Collection_Blueprint_Integration_Interface {

    public function get_name(): string;

    public function get_slug(): string;

    public function get_description(): string;

    public function get_options(): array;

    public function index_supports(): array;

    public function get_index_class(): string;

    public function register_rest_routes() : void;   
}