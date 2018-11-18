<?php
namespace App\Http\Controllers\Rules;

use App\Models\Rule\NotFoundException;

/**
 * Delete operation.
 *
 * @package App\Http\Controllers\Rules
 */
class Delete extends AbstractRuleController {

    /**
     * Delete rule
     *
     * @param string $id rule id
     * @return array
     */
    public function index($id){
        try {
            $this->getRuleRepository()->delete($id);
            return ['status'=>'ok'];

        } catch (NotFoundException $e) {
            return ['status'=>sprintf('Rule with id %s not found', $id)];

        } catch (\Exception $e) {
            return ['status'=>$e->getMessage()];
        }
    }
}
