<?php

namespace App\Http\Controllers\Rules;

use Illuminate\Http\Request;

class Post extends AbstractRuleController {

    public function index(Request $request){
        $ruleDefinition = $request->input();

        /** @var \App\Models\Rule\Entity $rule */
        $rule     = \App\Models\Rule\Entity::createFromArray($ruleDefinition);
        $rule->id = md5(microtime(true));

        $this->ruleRepository->save($rule);

        return $rule->jsonSerialize();
    }
}
