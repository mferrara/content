<?php

namespace App\Http\Controllers;

use App\Author;
use App\Basedomain;
use App\Searchquery;
use App\Subreddit;
use Illuminate\Support\Facades\File;


class AdminController extends Controller
{

    public function index()
    {
        return view('admin.index');
    }

    public function searchqueries()
    {
        $searches = Searchquery::paginate(25);

        return view('admin.searchquery.index')
                ->with('searches', $searches);
    }

    public function authors()
    {
        $authors = Author::paginate(25);

        return view('admin.author.index')
                ->with('authors', $authors);
    }

    public function domains()
    {
        $domains = Basedomain::paginate(25);

        return view('admin.basedomain.index')
                ->with('domains', $domains);
    }

    public function subreddits()
    {
        $subreddits = Subreddit::paginate(25);

        return view('admin.subreddit.index')
                ->with('subreddits', $subreddits);
    }

    public function showErrors()
    {
        $file = File::get(app_path().'/storage/logs/laravel.log');

        $file = explode("[] []", $file);
        rsort($file);
        $file = array_slice($file, 1, 200);

        $errors = [];
        $id = 0;
        foreach ($file as $line) {
            if (stristr($line, 'Route:')) {
                $line = str_replace("Route:", "Route:<strong>", $line);
                $line = $line."</strong>";

                $errors[$id]['error'] = $line;
                $errors[$id]['stack_trace'] = '';
                $id++;
            } else {
                $line = explode("#0", $line);

                if (count($line) > 1) {
                    $stack_trace = str_replace("#", "<br />", $line[1]);
                    $error = str_replace("Stack trace:", "", $line[0]);
                    $error = str_replace(" in ", " in <strong>", $error)."</strong>";

                    $errors[$id]['error'] = $error;
                    $errors[$id]['stack_trace'] = $stack_trace;
                    $id++;
                }
            }
        }

        return view('admin.errors')
            ->with('errors', $errors);
    }
}
