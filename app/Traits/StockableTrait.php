<?php

namespace App\Traits;

use App\Models\Products\Inventory;

trait StockableTrait
{
    /**
     * @Relationships
     */

	public function inventories() {
		return $this->morphMany(Inventory::class, 'parent');
	}

    /**
     * @Setters
     */

    /**
     * Adjust inventory
     */
    public function updateInventory($type, $value, $user = null) {
        $result = null;

        switch ($type) {
            case Inventory::TYPE_ADD:
                $result = $this->addInventory($value, $user);
                break;

            case Inventory::TYPE_SET:
                $result = $this->setInventory($value, $user);
                break;
        }

        return $result;
    }

    /**
     * Adjust inventory by Batch
     */
    public static function batchUpdateInventory($ids, $type, $value, $user = null) {
        $result = 0;

        switch ($type) {
            case Inventory::TYPE_ADD:
                $result = static::batchAddInventory($ids, $value, $user);
                break;

            case Inventory::TYPE_SET:
                $result = static::batchSetInventory($ids, $value, $user);
                break;
        }

        return $result;
    }

	/**
	 * Add quantity to inventory
	 */
	public function addInventory($value, $user = null, $logActivity = true) {
        $inventory = null;

        if ($value < 0 || $value > 0) {

            $vars['value'] = $value;

            if ($user) {
                $vars['user_id'] = $user->id;
                $vars['user_type'] = get_class($user);
            }

            $inventory = $this->inventories()->create($vars);

            if ($value > 0) {
                $message = "{$value} has been added to the inventory";
            } else {
                $value = abs($value);
                $message = "{$value} has been removed from the inventory";
            }

            if ($logActivity) {
                activity()
                    ->causedBy($user)
                    ->performedOn($this)
                    ->log($message);
            }

        }

        return $inventory;
    }


    /**
     * Set quantity of inventory
     */
    public function setInventory($value, $user = null) {
    	$currentStocks = $this->renderStocks();

        if ($currentStocks != $value) {
        	/* Reset quantity */
        	if ($currentStocks < 0) {
    	    	$resetCount = abs($currentStocks);
        	} else {
        		$resetCount = -1 * $currentStocks;
        	}

            $quantity = $resetCount + $value;
            $inventory = $this->addInventory($quantity, $user, false);

            if ($inventory) {
                $message = "Inventory has been set to {$value}";

                activity()
                    ->causedBy($user)
                    ->performedOn($this)
                    ->log($message);
            }
        }

    	return $inventory;
    }

    public function reduceInventory($value, $user = null, $logActivity = true) {
        $inventory = null;

        if ($this->shouldTrackInventory()) {
            $value = abs($value) * -1;

            if ($value < 0) {

                $vars['value'] = $value;

                if ($user) {
                    $vars['user_id'] = $user->id;
                    $vars['user_type'] = get_class($user);
                }

                $inventory = $this->inventories()->create($vars);
                $message = "{$value} has been removed from inventory";

                if ($logActivity) {
                    activity()
                        ->causedBy($user)
                        ->performedOn($this)
                        ->log($message);
                }
            }
        }

        return $inventory;
    }

    public static function batchAddInventory(array $ids, $value, $user = null) {
    	$count = 0;
    	$items = static::getStockableItems($ids);

    	foreach ($items as $item) {
    		$result = $item->addInventory($value, $user);

    		if ($result) {
    			$count++;
    		}
    	}

    	return $count;
    }

    public static function batchSetInventory(array $ids, $value, $user = null) {
    	$count = 0;
    	$items = static::getStockableItems($ids);

    	foreach ($items as $item) {
    		$result = $item->setInventory($value, $user);

    		if ($result) {
    			$count++;
    		}
    	}

    	return $count;
    }

    /**
     * @Getters
     */

    /**
     * Get stockable items for batch updating of inventory
     * @param  array $ids array of ids
     * @return model      stockable model
     */
    public static function getStockableItems($ids) {
        return static::withTrashed()->whereIn('id', $ids)->get();
    }

    /**
     * @Checkers
     */

    /**
     * Checking if item has stocks
     *
     * @return boolean
     */
	public function hasStocks() {
		return $this->renderStocks() > 0;
	}

    /**
     * Checking if item allows overselling
     *
     * @return boolean
     */
    public function canOversell() {
        return $this->allow_overselling;
    }

    /**
     * Check if has stock or can oversell
     * @return boolean
     */
    public function canPurchase() {
        return ! $this->shouldTrackInventory() || $this->hasStocks() || $this->canOversell();
    }

    /**
     * Check if inventory should be tracked
     * @return boolean
     */
    public function shouldTrackInventory() {
        return $this->track_quantity;
    }

    /**
     * Render item stocks
     *
     */
    public function renderStocks() {
        return $this->inventories()->sum('value');
    }
}