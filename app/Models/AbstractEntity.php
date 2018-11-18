<?php
namespace App\Models;

/**
 * Base class for all entities
 * 
 * @package App\Models
 */
abstract class AbstractEntity {

    /**
     * Create entity from array
     *
     * This is crude method that will copy array keys to fields with simple check if property is defined in the object
     *
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
