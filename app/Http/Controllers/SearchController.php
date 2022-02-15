<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $results = null;

        $users = User::get();

        if($query = $request->get('query')){
            $results= Article::search($query, function($meilisearch, $query, $options) use ($request) {
                if($userId = $request->get('user_id')){
                    $options['filter'] = "user_id=$userId";
                }
                // $options['filter'] = "user_id=2";
                return $meilisearch->search($query, $options);
            })->paginate(5)->withQueryString();
            // $results= Article::search($query)->paginate(5);
            // $results= Article::search($query)->get();
            // $results= Article::search($query)->where('published', 1)->get();
            // dd($results);
        }

        return view('search', ['results' => $results, 'users' => $users]);
    }
}



