<?php

namespace App\Http\Controllers\API;

use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{
    const PAGE_SIZE = 20;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Person::query();

        if($fields = $request->get('fields')){
            $fields = explode(',', $fields);
            $query->whereHas('fields', function($q) use($fields) {
                $q->whereIn('id', $fields);
            });
        }

        if($countries = $request->get('countries')){
            $countries = explode(',', $countries);
            $query->whereIn('birth_country', $countries);
        }

        $gender = $request->get('gender');
        if($gender && $gender != "either"){
            $query->where('gender', $gender[0]);
        }

        if($bornBefore = $request->get('born_before')){
            $query->whereDate('born', '<=', Carbon::createFromDate($bornBefore));
        }

        if($bornAfter = $request->get('born_after')){
            $query->whereDate('born', '>=', Carbon::createFromDate($bornAfter));
        }

        return $query->with('fields')->orderBy('id','asc')->paginate(self::PAGE_SIZE);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param Person $person
     * @return Person
     */
    public function show(Person $person)
    {
        return $person;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        $person->update($request->all());
        return response()->json($person, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json(null, 204);
    }
}
