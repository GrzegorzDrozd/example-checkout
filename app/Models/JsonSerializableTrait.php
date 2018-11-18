<?php
namespace App\Models;

/**
 * Serialize object to json. \JsonSerializable compatible.
 *
 * @package App\Models
 */
trait JsonSerializableTrait  {

    public function jsonSerialize (){

        $return = [];
        foreach (get_object_vars($this) as $field => $value) {
            $return[$field] = $value;
        }

        return $return;
    }
}
