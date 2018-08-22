<?php

namespace App\Http\Controllers;

use App\Extractor;
use App\Field;
use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    private $rules = [
        'name' => 'required',
        'fields' => 'required',
        'description' => 'required',
        'image_url' => 'required|url',
        'birth_country' => 'required',
        'gender' => 'required',
        'born' => 'required',
    ];

    /**
     * @param array $input Values from form
     * @return array Values with any multiline or comma delineated variables split into arrays
     */
    private static function processedValues(array $input)
    {
        $values = $input;

        $values['achievements'] = empty($values['achievements']) ? [] : explode("\n", $values['achievements']);
        $values['books'] = array_map(function($line) {
            $parts = explode(',', $line);
            $output = ['url'=>trim($parts[0])];
            if (count($parts) == 2) {
                $output['title'] = trim($parts[1]);
            }
            return $output;
        }, explode("\n", $values['books']));

        $fields = array_filter(preg_split("/\s*,\s*/", $values['fields']));
        $existingFields = Field::pluck('field')->toArray();

        // Create any new fields
        $newFields = array_diff($fields, $existingFields);
        foreach($newFields as $fieldName) {
            $field = new Field;
            $field->field = $fieldName;
            $field->save();
        }

        // Set the fields
        $setFields = Field::whereIn('field', $fields)->pluck('id');
        $values['fields'] = $setFields;

        return $values;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules);

        $person = new Person;
        $values = self::processedValues($request->all());

        $fields = $values['fields'];
        unset($values['fields']);
        $person->fill($values);
        $person->save();
        $person->fields()->sync($fields);
        $person->save();

        return view('stored', ['person'=>$person]);
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
        $request->validate($this->rules);

        $values = self::processedValues($request->all());
        $fields = $values['fields'];
        unset($values['fields']);
        $person->update($values);
        $person->fields()->sync($fields);
        $person->save();

        return view('updated', ['person'=>$person]);
    }

    /**
     * Display the specified resource.
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        return null;
    }

    /**
     * Show the add form
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $person = new Person;

        // attempt to auto-populate
        $name = $request->get('name');
        if (!empty($name)) {
            $wiki = Extractor::wikipedia($name);
            $goodreads = Extractor::goodreads($name);
            $person->fill($wiki);
            $person->books = [['url'=>$goodreads['url'], 'title'=>$goodreads['title']]];
        }

        return view('person_edit', ['person'=>$person]);
    }

    /**
     * Show the edit form
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person) {
        return view('person_edit', ['person'=>$person]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        $name = $person->name;
        $person->delete();
        return response("Deleted $name", 204);
    }

}
