<?php

namespace App\Http\Controllers\Rules;

class Get extends AbstractRuleController {

    public function index($id){
        return $this->ruleRepository->get($id)->jsonSerialize();
    }
}
