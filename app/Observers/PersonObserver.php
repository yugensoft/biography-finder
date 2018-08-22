<?php

namespace App\Observers;

use App\Person;

class PersonObserver
{
    /**
     * Handle the person "created" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function created(Person $person)
    {
        $this->clearCache();
    }

    /**
     * Handle the person "updated" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function updated(Person $person)
    {
        $this->clearCache();
    }

    /**
     * Handle the person "deleted" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function deleted(Person $person)
    {
        $this->clearCache();
    }

    /**
     * Handle the person "restored" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function restored(Person $person)
    {
        $this->clearCache();
    }

    /**
     * Handle the person "force deleted" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function forceDeleted(Person $person)
    {
        $this->clearCache();
    }

    public function clearCache(){
        cache()->forget('countries');
    }
}
