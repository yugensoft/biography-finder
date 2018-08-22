<?php

use Illuminate\Database\Seeder;

class PeopleFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Field::class, 5)->create();
        factory(\App\Person::class, 50)->create();

        $people = \App\Person::all();
        $fields = \App\Field::all();

        $people->each(function(\App\Person $person) use($fields) {
            $person->fields()->attach(
                $fields->random(rand(1,5))->pluck('id')->toArray()
            );
        });
    }
}
