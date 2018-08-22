<?php

namespace Tests\Unit;

use \App\Extractor;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExtractorTest extends TestCase
{
    public function testGoodreads()
    {
        $book['titan'] = ['id'=>16121, 'title'=>'Titan: The Life of John D. Rockefeller, Sr.', 'author'=>'Ron Chernow', 'url'=>'https://www.goodreads.com/book/show/16121'];
        $book['brief'] = [
            'id' => 21228386,
            'title' => 'A Brief Biography of John D. Rockefeller (Annotated)',
            'author' => 'Edwin Wildman',
            'url' => 'https://www.goodreads.com/book/show/21228386',
        ];
        $this->assertEquals(
            $book['brief'],
            Extractor::goodreads('john rockefeller')
        );
    }

    public function testWikipedia()
    {
        $this->assertEquals([
            'name'=>'Albert Einstein',
            'description'=>'Albert Einstein was a German-born theoretical physicist who developed the theory of relativity, one of the two pillars of modern physics. His work is also known for its influence on the philosophy of science.',
            'image_url'=>'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Einstein_1921_by_F_Schmutzer_-_restoration.jpg/330px-Einstein_1921_by_F_Schmutzer_-_restoration.jpg',
            'wiki'=>'https://en.wikipedia.org/wiki/Albert_Einstein',
            'birth_country'=>'de',
//            'gender'=>'m',
            'born'=>Carbon::createFromFormat('!Y-m-d', '1879-3-14'),
        ], Extractor::wikipedia('albert einstein'));

        $this->assertEquals([
            'name'=>'Howard Hughes',
            'description'=>'Howard Robard Hughes Jr. was an American business magnate, investor, record-setting pilot, engineer, film director, and philanthropist, known during his lifetime as one of the most financially successful individuals in the world.',
            'image_url'=>'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Howard_Hughes_1938.jpg/330px-Howard_Hughes_1938.jpg',
            'wiki'=>'https://en.wikipedia.org/wiki/Howard_Hughes',
            'birth_country'=>'us',
//            'gender'=>'m',
            'born'=>Carbon::createFromFormat('!Y-m-d', '1905-12-24'),
        ], Extractor::wikipedia('howard hughes'));
    }
}
