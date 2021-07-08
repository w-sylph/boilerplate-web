<?php

namespace App\Traits;

use App\Helpers\StringHelper;

trait RenderTrait
{
    public function renderName() {
        return;
    }

    public function renderShowUrl() {
        return;
    }

    /**
     * @Helpers
     */
    public static function renderConstants($array, $value, $column = 'label', $compare_column = 'value') {

        /* Loop through the array */
        foreach ($array as $obj) {
            
            if($obj[$compare_column] == $value) {

                /* Fetch columm if specified */
                if($column && isset($obj[$column]))
                    return $obj[$column];

                return $obj;
            }
        }
    }

    public function renderBooleanText($column) {
        $result = 'No';

        if ($this[$column]) {
            $result = 'Yes';
        }

        return $result;
    }

    public function renderBooleanInt($column) {
        $result = 0;

        if ($this[$column]) {
            $result = $this[$column] == true ? 1 : 0;
        }

        return $result;
    }

    public function renderClassName() {
        return StringHelper::getShortClassName($this);
    }

    public function renderLogName() {
        return "{$this->renderClassName()} #{$this->id}";
    }
}