<?php
namespace App\Http\Controllers\Rules;

use App\Models\Rule\NotFoundException;

/**
 * Rule retrieval controller.
 * @package App\Http\Controllers\Rules
 */
class Get extends AbstractRuleController {

    /**
     * Get rule definition
     *
     * @param string $id
     * @return array
     */
    public function index($id){
        try {
            return $this->getRuleRepository()->get($id)->jsonSerialize();

        } catch (NotFoundException $e) {
            return ['status'=>sprintf('Rule with id %s not found', $id)];

        } catch (\Exception $e) {
            return ['status'=>$e->getMessage()];
        }
    }
}
