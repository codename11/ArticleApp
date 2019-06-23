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

        $articles = Article::paginate(2);

        return view("articles.List Articles")->with(compact("articles"));
    }

    public function ajaxIndex(Request $request){
        
        if($request->ajax()){
            $articles = Article::with('user')->paginate(1);
            /*Response koji se šalje sa servera ukoliko je uspešan response.*/

            $response = array(
                'status' => 'success',
                'msg' => "Hello!",
                "articles" => $articles,
                "request" => $request->all(),  
                'pagination'=>(string) $articles->links(),
            );
            
            return response()->json($response);
            
        }
         
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
        $prev = $article->prev($article);
        $next = $article->next($article);
        
        return view("articles.Single Article")->with(compact("article", "prev", "next"));
    }

    public function ajaxShow(Request $request)
    {
        if($request->ajax()){

            $article = Article::find($request->articleId);  
            $allArticleIds = Article::pluck('id');
            $user = User::find($article->user_id);
            $prev = $article->prev($article);
            $next = $article->next($article);
            
            /*Response koji se šalje sa servera ukoliko je uspešan response.*/
            
            $response = array(
                "status" => "success",
                "msg" => "Hello!",
                "article" => [
                    "id" => $article->id,
                    "title" => $article->title,
                    "body" => $article->body,
                    "image" => $article->image,
                    "created_at" => $article->created_at->format('d. M, Y'),
                    "updated_at" => $article->updated_at,
                    "user_id" => $article->user_id,
                ],  
                "prev" => $prev,
                "next" => $next,
                "request" => $request->all(),
                "allArticleIds" => $allArticleIds,
                "user" => $user,
            );
            
            return response()->json($response);
            
        }

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

}
