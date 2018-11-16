<?php

namespace App\Http\Controllers\Rules;

use Illuminate\Http\Request;

class Put extends AbstractRuleController {

    public function index($id, Request $request){
        $ruleDefinition = $request->input();

        // we cannot allow for an id change
        unset($ruleDefinition['id']);

        $rule = $this->ruleRepository->get($id);

        foreach($ruleDefinition as $field => $value) {
            $rule->$field = $value;
        }

        $this->ruleRepository->save($rule);
        
        return $rule;
    }
}
