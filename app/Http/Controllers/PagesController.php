<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Article;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ["except" => ["index"]]);
    }
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.index');
    }

    public function ListArticles()
    {
        return view('articles.List Articles');
    }

    public function ShowArticle($id)
    {
        //dd($id);
        return view('articles.Single Article');
    }

    public function DeleteArticle($id)
    {
        dd($id);
        return view('articles.List Articles');
    }
}
