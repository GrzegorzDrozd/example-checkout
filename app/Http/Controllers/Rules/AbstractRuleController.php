<?php

namespace App\Http\Controllers\Rules;


use App\Models\Rule\Collection;

abstract class AbstractRuleController extends \App\Http\Controllers\Controller {
    /**
     * @var Collection
     */
    protected $ruleRepository;

    /**
     * Get constructor.
     * @param Collection $ruleRepository
     */
    public function __construct(Collection $ruleRepository) {
        $this->ruleRepository = $ruleRepository;
    }
}
