<?php

namespace WooSearch\Records\Attributes;

class RecordAttribute implements AttributeInterface {

    protected string $name;

    protected $data;

    protected Array $facet;

    public function __construct( string $name, $data, $facet = [] ) {
        $this->name = $name;
        $this->data = $data;
        $this->facet = $facet;
    }

    public static function create( string $name, $data, $facet = [] ) {
        $instance = new self( $name, $data, $facet );
        return $instance;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getData() {
        return $this->data;
    }

    public function getFacet(): array {
        return $this->facet;
    }
}