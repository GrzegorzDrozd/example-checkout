<?php
namespace App\Models\Rule;

use mysql_xdevapi\Exception;

/**
 * Rule collection
 * 
 * @package App\Models\Rule
 */
class Collection {

    /**
     * @var string
     */
    private $pathPrefix;

    /**
     * Set storage path prefix.
     */
    public function __construct() {
        $this->pathPrefix = '../storage/rules/';
    }

    /**
     * Save entity into permanent storage
     *
     * @param Entity $entity
     */
    public function save(Entity $entity) : void {
        $current = error_reporting();
        error_reporting(E_ALL);
        $i = file_put_contents($this->pathPrefix .$entity->id, serialize($entity));
        if ($i === 0 OR $i === false) {
            throw new \RuntimeException('Unable to save rule in storage',null, new \Exception(error_get_last()['message']));
        }
        error_reporting($current);
    }

    /**
     * Get rule from rule storage
     *
     * @param string $id
     * @return Entity
     */
    public function get($id) : Entity {

        if (!file_exists($this->pathPrefix.$id)){
            throw new NotFoundException('Rule not found');
        }
        $data = file_get_contents($this->pathPrefix.$id);
        $rule = unserialize($data, [
            'allowed_classes'   => [
                Entity::class,
                \App\Models\Product\Entity::class
            ],
        ]);

        return $rule;
    }

    /**
     * Get all rules from the storage with optional filter.
     *
     * @param bool $basket filter rules: null=> get all, true => get rules for basket, false => get rules for items
     * @return array
     */
    public function getAll($basket = true) : array {

        $return = [];
        $files = glob($this->pathPrefix.'*');

        foreach($files as $file) {
            // exclude deleted files
            if (strpos($file, '_deleted_') !== false) {
                continue;
            }
            $id = basename($file);
            $rule = $this->get($id);

            if ($basket === null OR $rule->basket === $basket) {
                $return[] = $rule;
            }
        }

        // sort by order. This is important for rules that have stop flag set to true.
        usort($return, function ($a, $b) {
            if ($a->ord === $b->ord) {
                return 0;
            }
            return ($a->ord < $b->ord) ? -1 : 1;
        });

        return $return;
    }

    /**
     * Delete rule by id
     *
     * @param string $id
     * @return bool
     */
    public function delete($id) : bool {
        if (!file_exists($this->pathPrefix.$id)){
            throw new NotFoundException('Rule not found');
        }
        return rename(
            $this->pathPrefix.$id,
            $this->pathPrefix.$id.'_deleted_'.microtime(true)
        );
    }
}
