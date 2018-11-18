<?php
namespace App\Http\Controllers\Rules;

/**
 * Controller for getting list of rules
 *
 * @package App\Http\Controllers\Rules
 */
class Index extends AbstractRuleController {

    /**
     * List of rules
     *
     * @return array
     */
    public function index(){
        try {
            return $this->getRuleRepository()->getAll(null);

        } catch (\Exception $e) {
            return ['status'=>$e->getMessage()];
            
        }
    }
}
