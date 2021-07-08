<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

trait ArchiveableTrait
{
	use SoftDeletes;

    /* Archive Item */
    public function archive() {
        if (!$this->trashed() && $this->isArchiveable()) {
            $this->delete();
            $this->onArchive();
        } else {
            throw ValidationException::withMessages([
                'deleted_at' => $this->archiveErrorMessage(),
            ]);
        }

        return true;
    }

    /* Restore all items in array */
    public static function batchArchive($ids) {
        $result = 0;

        if (count($ids) > 1) {
            $result = static::whereIn('id', $ids)->delete();
            static::onBatchArchive($ids);

            activity()
                ->causedBy(auth()->user())
                ->performedOn(new static)
                ->withProperties(['ids' => $ids])
                ->log("{$result} items has been archived");
        } else {
            $result = 1;
            $item = static::withTrashed()->findOrFail($ids[0]);
            $item->archive();
        }

        return $result;
    }

    /* Restore Item */
    public function unarchive() {
        if ($this->trashed() && $this->isRestorable()) {
            $this->restore();
            $this->onRestore();
        } else {
            throw ValidationException::withMessages([
                'deleted_at' => $this->restoreErrorMessage(),
            ]);
        }

        return true;
    }

    /* Restore all items in array */
    public static function batchRestore($ids) {
        $result = 0;

        if (count($ids) > 1) {
            $result = static::onlyTrashed()->whereIn('id', $ids)->restore();
            static::onBatchRestore($ids);

            activity()
                ->causedBy(auth()->user())
                ->performedOn(new static)
                ->withProperties(['ids' => $ids])
                ->log("{$result} items has been restored");
        } else {
            $result = 1;
            $item = static::withTrashed()->findOrFail($ids[0]);
            $item->unarchive();
        }

        return $result;
    }

    /* Fire function on archive */
    public function onArchive() {
        
    }

    /* Fire function on restore */
    public function onRestore() {

    }

    /* Fire function on  batch archive */
    public static function onBatchArchive(array $ids) {
        
    }

    /* Fire function on batch resotre */
    public static function onBatchRestore(array $ids) {

    }

    /* Determine if item is archiveable */
    public function isArchiveable() {
        return true;
    }

    /* Determine if item is restorable */
    public function isRestorable() {
        return true;
    }

    /* Archive error message */
    public function archiveErrorMessage() {
        $result = 'Item';

        if ($this->isArchiveable()) {
            $result .= ' has already been archived.';
        } else {
            $result .= ' cannot be archived.';
        }

        return $result;
    }

    /* Restore error message */
    public function restoreErrorMessage() {
        $result = 'Item';

        if ($this->isRestorable()) {
            $result .= ' has already been restored.';
        } else {
            $result .= ' cannot be restored.';
        }

        return $result;
    }

    /* Archive URL */
    public function renderArchiveUrl() {}

    /* Restore URL */
    public function renderRestoreUrl() {}
}