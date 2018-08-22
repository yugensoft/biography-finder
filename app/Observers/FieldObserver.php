<?php

namespace App\Observers;

use App\Field;

class FieldObserver
{
    /**
     * Handle the field "created" event.
     *
     * @param  \App\Field  $field
     * @return void
     */
    public function created(Field $field)
    {
        $this->clearCache();
    }

    /**
     * Handle the field "updated" event.
     *
     * @param  \App\Field  $field
     * @return void
     */
    public function updated(Field $field)
    {
        $this->clearCache();
    }

    /**
     * Handle the field "deleted" event.
     *
     * @param  \App\Field  $field
     * @return void
     */
    public function deleted(Field $field)
    {
        $this->clearCache();
    }

    /**
     * Handle the field "restored" event.
     *
     * @param  \App\Field  $field
     * @return void
     */
    public function restored(Field $field)
    {
        $this->clearCache();
    }

    /**
     * Handle the field "force deleted" event.
     *
     * @param  \App\Field  $field
     * @return void
     */
    public function forceDeleted(Field $field)
    {
        $this->clearCache();
    }

    public function clearCache(){
        cache()->forget('fields');
    }
}
