<?php

namespace App\Traits;

use App\Helpers\StringHelper;

trait DateTrait 
{
    /* Render formatted date */
    public function renderDate($column = 'created_at', $format = 'M d, Y (H:i:s)') {
        $result = null;

        switch ($format) {
        	case 'diffForHumans':
        			if (isset($this[$column]) && $this[$column]) {
			            $result = $this[$column]->diffForHumans();
			        }
        		break;
        	
        	default:
        			if (isset($this[$column]) && $this[$column]) {
			            $result = $this[$column]->format($format);
			        }
        		break;
        }

        return $result;
    }
}