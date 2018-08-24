@extends('base')

@section('title', 'Add/Edit Person')

@section('content')
    <div class="container">
        <a href="{{ URL::to('/') }}"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>
            @if(isset($person->id))
                Edit Person: <i>{{ $person->name }}</i>
            @else
                New Person
            @endif
        </h1>

        <form action="{{ isset($person->id) ? action('PersonController@update', ['id'=>$person->id]) : action('PersonController@store') }}" method="post">
            @if(isset($person->id))
                @method('put')
            @endif

            {{ csrf_field() }}

            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $person->name) }}" />
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>

            <div class="form-group">
                <label for="fields">Fields</label>
                <input class="form-control" type="text" name="fields" id="fields" value="{{ old('fields', array_reduce($person->fields->toArray(), function($a,$b){ return $a === null ? $b['field'] : $a . ', ' . $b['field']; }, null)) }}" />
                <small class="text-danger">{{ $errors->first('fields') }}</small>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ old('description', $person->description) }}</textarea>
                <small class="text-danger">{{ $errors->first('description') }}</small>
            </div>

            <div class="form-group">
                <label for="image_url">Image URL</label>
                <input class="form-control" type="text" name="image_url" id="image_url" value="{{ old('image_url', $person->image_url) }}" />
                <small class="text-danger">{{ $errors->first('image_url') }}</small>
            </div>

            <div class="form-group">
                <label for="achievements">Achievements</label>
                <textarea class="form-control" name="achievements" id="achievements" cols="30" rows="5">{{ old('achievements', join("\n", $person->achievements ?? [])) }}</textarea>
            </div>

            <div class="form-group">
                <label for="books">Book links</label>
                <textarea class="form-control" name="books" id="books" cols="30" rows="5">{{ old('books', $person->booksTextarea) }}</textarea>
                <small>New book on each line. Format: [url], [title].</small>
            </div>

            <div class="form-group">
                <label for="wiki">Wikipedia link</label>
                <input class="form-control" type="text" name="wiki" id="wiki" value="{{ old('wiki', $person->wiki) }}" />
            </div>

            <div class="form-group">
                <label for="birth_country">Birth country</label>
                <select class="form-control" name="birth_country" id="birth_country">
                    <option></option>
                    @foreach(\App\Countries::ALL as $code=>$country)
                        <option value="{{ strtolower($code) }}" {{ old('birth_country', strtolower($person->birth_country)) == strtolower($code) ? 'selected' : '' }} >{{ $country }}</option>
                    @endforeach
                </select>
                <small class="text-danger">{{ $errors->first('birth_country') }}</small>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <div id="gender">
                    <label class="radio-inline"><input type="radio" name="gender" id="male" value="m" {{ old('gender', $person->gender) == 'm' ? 'checked' : '' }} > Male</label>
                    <label class="radio-inline"><input type="radio" name="gender" id="female" value="f" {{ old('gender', $person->gender) == 'f' ? 'checked' : '' }} > Female</label>
                </div>
                <small class="text-danger">{{ $errors->first('gender') }}</small>
            </div>

            <div class="form-group">
                <label for="born">Born</label>
                <input class="form-control" type="date" name="born" id="born" value="{{ old('born', $person->born) }}" />
                <small class="text-danger">{{ $errors->first('born') }}</small>
            </div>

            <div class="form-group text-center">
                <button class="btn btn-primary btn-lg" type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection