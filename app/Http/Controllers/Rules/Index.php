<?php

namespace App\Http\Controllers\Rules;

class Index extends AbstractRuleController {

    public function index(){
        return $this->ruleRepository->getAll();
    }
}
