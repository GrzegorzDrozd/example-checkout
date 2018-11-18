<?php
namespace App\Http\Controllers\Rules;

use App\Models\Rule\Collection;

/**
 * Base class for Rule operations.
 *
 * @package App\Http\Controllers\Rules
 */
abstract class AbstractRuleController extends \App\Http\Controllers\Controller {

    /**
     * @var Collection
     */
    protected $ruleRepository;

    /**
     * Set rule repository.
     * 
     * @param Collection $ruleRepository
     */
    public function __construct(Collection $ruleRepository) {
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * @return Collection
     */
    public function getRuleRepository(): Collection {
        return $this->ruleRepository;
    }

    /**
     * @param Collection $ruleRepository
     */
    public function setRuleRepository(Collection $ruleRepository): void {
        $this->ruleRepository = $ruleRepository;
    }
}
