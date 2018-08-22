<?php

namespace App;
use Carbon\Carbon;
use Exception;
use SimpleXMLElement;

/**
 * Class Extractor
 *
 * Functions for searching for famous people and their biographies on places like Wikipedia
 * and GoodReads, and extracting data on them.
 */
class Extractor {
    const BRIEF_SENTENCE_COUNT = 2; // How many sentences to extract from first paragraph as the description

    /**
     * Attempt to find and extract person information from Wikipedia
     * description - First two sentences
     * image_url - From infobar
     * wiki - Wikipedia url
     * birth_country
     * gender -> X not currently, don't know how to get
     * born
     *
     * @param string $name Person's name
     * @throws Exception
     * @return array
     */
    public static function wikipedia($name){
        $nameEncoded = urlencode($name);

        // get the page ID
        $searchEndpoint = "https://en.wikipedia.org/w/api.php?format=json&action=query&list=search&srsearch=$nameEncoded&utf8=";
        $searchJson = file_get_contents($searchEndpoint);
        if($searchJson === false){
            throw new Exception("Search failed");
        }
        $search = json_decode($searchJson, true);
        if(!isset($search['query']['search'][0]['pageid'])){
            throw new Exception("Search failed");
        }
        $pageId = $search['query']['search'][0]['pageid'];
        $confirmedName = $search['query']['search'][0]['title'];

        // Get an extract of the document
        $extractEndpoint = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&pageids=$pageId";
        $extractJson = file_get_contents($extractEndpoint);
        if($extractJson === false) {
            throw new Exception("Extract failed");
        }
        $extract = json_decode($extractJson, true);
        if (!isset($extract['query']['pages'][$pageId]['extract'])) {
            throw new Exception("Extract failed");
        }
        $extractHtml = $extract['query']['pages'][$pageId]['extract'];
        $extractXml = simplexml_load_string("<html>$extractHtml</html>");
        if($extractXml === false) {
            throw new Exception("Extract failed (post API call)");
        }
        $paragraphs = $extractXml->xpath('//p[not(@class)]');
        if (!isset($paragraphs)) {
            throw new Exception("Extract failed (no first paragraph)");
        }
        $firstParagraphHtml = $paragraphs[0]->asXML();
        $firstParagraph = strip_tags($firstParagraphHtml);
        $description = self::stripDownParagraphToBriefDescription($firstParagraph);

        // Get the normal wikipedia url
        $urlEndpoint = "https://en.wikipedia.org/w/api.php?format=json&action=query&prop=info&pageids=$pageId&inprop=url";
        $urlJson = file_get_contents($urlEndpoint);
        if($urlJson === false) {
            throw new Exception("URL get failed");
        }
        $urlData = json_decode($urlJson, true);
        if (!isset($urlData['query']['pages'][$pageId]['fullurl'])) {
            throw new Exception("URL get failed");
        }
        $url = $urlData['query']['pages'][$pageId]['fullurl'];

        $pageEndpoint = "https://en.wikipedia.org/?curid=$pageId";
        $pageHtml = file_get_contents($pageEndpoint);
        if($pageHtml === false){
            throw new Exception("Page load failed");
        }
        $pageXml = simplexml_load_string($pageHtml);
        $imageUrl = self::getWikiImage($pageXml);
        $born = self::getWikiBirthDate($pageXml);
        $country = self::getWikiBirthPlace($pageXml);

        return [
            'name'=>$confirmedName,
            'description'=>$description,
            'image_url'=>$imageUrl,
            'wiki'=>$url,
            'birth_country'=>$country,
            'born'=>$born,
        ];
    }

    private static function getWikiImage(SimpleXMLElement $xml) {
        $infobox_img_xpath = $xml->xpath( '//table[contains(@class,"infobox")]//img/@srcset' );
        if(empty($infobox_img_xpath)) {
            $infobox_img_xpath = $xml->xpath( '//table[contains(@class,"infobox")]//img/@src' );
        }

        if(isset($infobox_img_xpath[0][0])) {
            $imagePath =  preg_replace( '/ .*$/', '', (string) $infobox_img_xpath[0][0] );
            return preg_replace('/^\/\//', 'https://', $imagePath);
        }

        return null;
    }


    private static function getWikiBirthDate(SimpleXMLElement $xml)
    {
        $bday = $xml->xpath("//span[@class='bday']");
        if (empty($bday)) {
            return null;
        }
        $date = (string)$bday[0];
        return $date;
    }

    private static function getWikiBirthPlace(SimpleXMLElement $xml)
    {
        $birthplace = $xml->xpath("//span[@class='birthplace']");
        if (empty($birthplace)) {
            return null;
        }
        $birthplaceString = strip_tags($birthplace[0]->asXML());
        $lastPartOfBirthplaceString = strtolower(trim(preg_replace('/.*,/', '', $birthplaceString)));

        $equivalents = [
            'u.s.' => 'United States of America (USA)',
            'us' => 'United States of America (USA)',
        ];
        if (isset($equivalents[$lastPartOfBirthplaceString])) {
            $lastPartOfBirthplaceString = $equivalents[$lastPartOfBirthplaceString];
        }

        $levDistances = [];
        foreach (Countries::ALL as $code => $country) {
            $levDistances[$code] = levenshtein($lastPartOfBirthplaceString, strtolower($country));
        }

        asort($levDistances);
        $closestCountryCode = key($levDistances);

        return strtolower($closestCountryCode);
    }

    /**
     * Suggest a Goodreads URL, likely a biography
     * @param string $name Person's name
     * @return array [id title author url]
     */
    public static function goodreads($name){
        $endpoint = "https://www.goodreads.com/search/index.xml";
        $query = http_build_query([
            'key'=>env('GOODREADS_API_KEY'),
            'q'=>$name
        ]);

        $data = file_get_contents($endpoint . "?" . $query);
        $xml = simplexml_load_string($data);

        if(!isset($xml->search->results->work)){
            return null;
        }

        $works = $xml->search->results->work;
        if(!isset($works[0]->best_book->id)){
            return null;
        }

        $book = $works[0]->best_book;
        return [
            'id'=> (int)$book->id,
            'title'=> (string)$book->title,
            'author'=> (string)$book->author->name,
            'url'=>"https://www.goodreads.com/book/show/{$book->id}"
        ];
    }

    private static function stripDownParagraphToBriefDescription($text)
    {
        $parenCounter = 0;
        $periodCounter = 0;
        $charCounter = 0;
        $output = "";
        foreach (str_split($text) as $char) {
            $charCounter++;
            if(in_array($char, ['[','('])){
                $parenCounter++;
            }
            if ($parenCounter == 0) {
                $output .= $char;
            }
            if (in_array($char, [']', ')'])) {
                $parenCounter--;
            }
            if($char == '.' && $periodCounter == 0 && $charCounter > 30){
                $periodCounter++;
                if ($periodCounter == self::BRIEF_SENTENCE_COUNT) {
                    break;
                }
            }
        }

        $noDoubleSpaces = preg_replace('/ +/', ' ', $output);
        $noSpaceThenPeriod = preg_replace('/ \./', '.', $noDoubleSpaces);
        $result = preg_replace('/ \,/', '.', $noSpaceThenPeriod);

        return $result;
    }

}
