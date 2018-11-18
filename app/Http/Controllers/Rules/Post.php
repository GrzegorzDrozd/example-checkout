<?php
namespace App\Http\Controllers\Rules;

use Illuminate\Http\Request;

/**
 * Create new rule controller
 *
 * @package App\Http\Controllers\Rules
 */
class Post extends AbstractRuleController {

    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request) : array {

        // get rule definition from request body
        $ruleDefinition = $request->input();

        try {
            // create entity from rule definition
            /** @var \App\Models\Rule\Entity $rule */
            $rule     = \App\Models\Rule\Entity::createFromArray($ruleDefinition);
            // @todo use better algorithm for id generation, uuid?
            $rule->id = md5(microtime(true));


            $this->getRuleRepository()->save($rule);

            return $rule->jsonSerialize();
        } catch (\Exception $e) {
            return ['status'=>$e->getMessage()];
        }
    }
}
