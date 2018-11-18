<?php
namespace App\Http\Controllers\Rules;

use App\Models\Rule\NotFoundException;
use Illuminate\Http\Request;

/**
 * Update rule controller
 *
 * @package App\Http\Controllers\Rules
 */
class Put extends AbstractRuleController {

    /**
     * @param string $id
     * @param Request $request
     * @return array
     */
    public function index($id, Request $request){

        // get new rule definition from request body
        $ruleDefinition = $request->input();

        // we cannot allow for an id change
        unset($ruleDefinition['id']);

        try {
            $rule = $this->getRuleRepository()->get($id);
        } catch (NotFoundException $e) {
            return ['status'=>sprintf('Rule with id %s not found', $id)];
        }

        // update fields.
        // @todo this is just a prototype
        foreach($ruleDefinition as $field => $value) {
            $rule->$field = $value;
        }

        try {
            $this->getRuleRepository()->save($rule);
        } catch (\Exception $e) {
            return ['status'=>$e->getMessage()];
        }

        return $rule->jsonSerialize();
    }
}
