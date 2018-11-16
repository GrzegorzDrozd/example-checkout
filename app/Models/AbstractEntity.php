<?php

namespace App\Models;


abstract class AbstractEntity {

    /**
     * @param array $data
     * @return AbstractEntity
     */
    public static function createFromArray(array $data) {
        $instance = new static();

        foreach ($data as $field => $value) {
            // @todo use zend-hydrator to use setters?
            if (property_exists($instance, $field)){
                $instance->$field = $value;
            }
        }

        return $instance;
    }
}
