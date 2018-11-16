<?php

namespace App\Http\Controllers\Rules;

class Delete extends AbstractRuleController {

    public function index($id){
        
        $this->ruleRepository->delete($id);
        return ['status'=>'ok'];
    }
}
