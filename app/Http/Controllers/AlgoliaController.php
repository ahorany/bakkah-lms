<?php

namespace App\Http\Controllers;

use App\Models\Training\Course;
use Illuminate\Http\Request;

class AlgoliaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function export(){

        require __DIR__ . '\vendor\algolia\algoliasearch-client-php\autoload.php';

        // if you are not using composer
        // require_once 'path/to/algoliasearch.php';

        // $client = Algolia\AlgoliaSearch\SearchClient::create('344AAZSWHX', 'a1f1a9364fd5a07440f3070a8c2a7f76');
        // $index = $client->initIndex('products_index');
        $courses = Course::get();

        $objects = [];

        foreach ($courses->browseObjects() as $hit) {
            $objects[] = $hit;
        }

        file_put_contents('products_index', json_encode($objects));
    }
}
