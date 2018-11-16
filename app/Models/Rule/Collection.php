<?php

namespace App\Models\Rule;


class Collection {
    /**
     * @var string
     */
    private $pathPrefix;

    /**
     * Collection constructor.
     * @param string $pathPrefix
     */
    public function __construct() {
        $this->pathPrefix = '../storage/rules/';
    }

    public function save(Entity $entity) : void {
        file_put_contents($this->pathPrefix .$entity->id, serialize($entity));
    }

    public function get($id) : Entity {
        if (!file_exists($this->pathPrefix.$id)){
            throw new NotFound('Rule not found');
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

    public function getAll(){

        $return = [];
        $files = glob($this->pathPrefix.'*');

        foreach($files as $file) {
            // exclude deleted files
            if (strpos($file, '_deleted_') !== false) {
                continue;
            }
            $id = basename($file);
            $return[] = $this->get($id);
        }

        usort($return, function ($a, $b) {
            if ($a->ord === $b->ord) {
                return 0;
            }
            return ($a->ord < $b->ord) ? -1 : 1;
        });

        return $return;
    }

    public function delete($id) : bool {
        if (!file_exists($this->pathPrefix.$id)){
            throw new NotFound('Rule not found');
        }
        return rename(
            $this->pathPrefix.$id,
            $this->pathPrefix.$id.'_deleted_'.microtime(true)
        );
    }
}
