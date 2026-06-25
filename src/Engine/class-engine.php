<?php

/**
 * The search engine responsible for interacting with an operating to perform search configuration.
 *
 * @package WooSearch\Engine
 */

namespace WooSearch\Engine;

use WooSearch\Indicies\RecordIndexInterface;

/**
 * Engine class responsible for interacting with record indices
 */
class Engine
{

    public function initIndex(RecordIndexInterface $index) {}

    public function removeIndex($index)
    {

        if ($index instanceof RecordIndexInterface) {
        }
    }

    public function saveRecords() {}

    public function clearRecords() {}
}
