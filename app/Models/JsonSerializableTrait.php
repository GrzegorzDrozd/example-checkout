<?php

namespace App\Models;


trait JsonSerializableTrait  {

    public function jsonSerialize (){

        $return = [];
        foreach (get_object_vars($this) as $field => $value) {
            $return[$field] = $value;
        }

        return $return;
    }
}
