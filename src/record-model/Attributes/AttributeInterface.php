<?php

namespace WooSearch\Records\Attributes;

interface AttributeInterface {

    public static function create( string $name, $data );

    public function getName() : string;

    public function getData();

    public function getFacet() : array;

    public function getBlockSupports();
}