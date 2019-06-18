<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Article;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth', ["except" => ["pages.index"]]);
    }

    public function index()
    {
        $articles = Article::orderBy("created_at","desc")->paginate(5);
        //dd($articles);
        return view("articles.List Articles")->with(compact("articles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("articles.Create Article");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required|min:4|max:12",
            "body" => "required",
            "image" => "image|nullable"
        ]);
        //dd($request["title"],$request["body"],$request["image"]);
        if($request->hasFile("image")){
            $filenameWithExt = $request->file("image")->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("image")->getClientOriginalExtension();
            $fileNameToStore = $filename."_".time().".".$extension;
            $path = $request->file("image")->storeAs("public/images", $fileNameToStore);
        }
        else{
            $fileNameToStore = "noimage.jpg";
        }
        
        $article = new Article;
        $article->title = $request->input("title");
        $article->body = $request->input("body");
        $article->user_id = auth()->user()->id;
        $article->image = $fileNameToStore;
        $article->save();
        
        return redirect("/articles")->with("success", "Post Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);  
        //dd($article);
        //return view("posts.show")->with(compact("jason"));
        $prev = $article->prev($article);
        $next = $article->next($article);
        
        return view("articles.Single Article")->with(compact("article", "prev", "next"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
        
        return view("articles.Update Article")->with(compact("article"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        $this->validate($request, [
            "title" => "required",
            "body" => "required",
            "cover_image" => "image|nullable"
        ]);

        if($request->hasFile("image")){
            $filenameWithExt = $request->file("image")->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("image")->getClientOriginalExtension();
            $fileNameToStore = $filename."_".time().".".$extension;
            $path = $request->file("image")->storeAs("public/images", $fileNameToStore);
        }

        //$post = new Post;

        $article->title = $request->input("title");
        $article->body = $request->input("body");

        if($request->hasFile("image")){
            $article->image = $fileNameToStore;
        }

        $article->save();

        return redirect("/articles")->with("success", "Article Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        
        if($article->image!="noimage.jpg"){
            Storage::delete("public/images/".$article->image);
        }

        $article->delete();
        return redirect("/articles")->with("success", "Article removed");
    }

    public function ajax(Request $request)
    { 
        $var1 = $request->var1;
        $var2 = $request->var2;
        $elem = $request->elem;

        $articles = Article::orderBy("created_at","desc")->paginate(5);
        //return $request;
        return response()->json(array("articles"=> $articles,"var1"=> $var1, "var2"=> $var2, "elem"=> $elem), 200);
        
    }

}
