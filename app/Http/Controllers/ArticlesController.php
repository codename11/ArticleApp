<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Article;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
        //return $request->selected;
        if($request->ajax()){
            
            $articles = intval($request->selected) ? Article::with('user')->where("user_id",intval($request->selected))->paginate(1) : Article::with('user')->paginate(1);
            /*Response koji se šalje sa servera ukoliko je uspešan response.*/
            $users = User::all();
            $response = array(
                'status' => 'success',
                'msg' => "Hello!",
                "articles" => $articles,
                "request" => $request->all(),  
                'pagination'=>(string) $articles->links(),
                "users" => $users,
                "selected" => intval($request->selected),
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
            "image" => "image|nullable"
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

    public function ajaxDestroy(Request $request)
    {
        $article = Article::find($request->id);
        
        if($article->image!="noimage.jpg"){
            Storage::delete("public/images/".$article->image);
        }

        $article->delete();
        if($request->ajax()){
            //dd($request);
            $response = array(
                'status' => 'success',
                'msg' => "Hello!",
                "request" => $request->all(),
                "rqId" => $request->id,
            );
            return response()->json($response);
            
        }
        //return redirect("/list")->with("success", "Article removed");
        
    }

    public function ajaxUpdate(Request $request)
    {

        if($request->ajax()){
            
            $article = Article::find($request->articleId);  
            $allArticleIds = Article::pluck('id');
            $user = User::find($article->user_id);
            $prev = $article->prev($article);
            $next = $article->next($article);

            $validator = \Validator::make($request->all(), [
                "title" => "required",
                "body" => "required",
                'image' => 'image|nullable|max:1999'
            ]);
           
            if ($validator->passes()){
                $hasImage = false;
                //Handle file upload
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
                
                $article->title = $request->input("title");
                $article->body = $request->input("body");
                //$article->user_id = auth()->user()->id;
                $article->image = $fileNameToStore;
                $article->save();

                $response = array(
                    'status' => 'success',
                    'msg' => "Hello!",
                    "request" => $request->all(),
                    "passesValidation" => true,
                    "article" => [
                        "id" => $article->id,
                        "title" => $article->title,
                        "body" => $article->body,
                        "image" => $article->image,
                        "created_at" => $article->created_at->format('d. M, Y'),
                        "updated_at" => $article->updated_at,
                        "user_id" => $article->user_id,
                    ],
                    "hasImage" => $hasImage,
                    "prev" => $prev,
                    "next" => $next,
                    "allArticleIds" => $allArticleIds,
                    "user" => $user,
                );

                return response()->json($response);

            }
            else{

                $response = array(
                    'status' => 'success',
                    'msg' => "Hello!",
                    "request" => $request->all(),
                    "passesValidation" => false,
                    'errors' => $validator->getMessageBag()->toArray(),
                );
                return response()->json($response);

            }
            
        }
        
    }

    public function ajaxCreate(Request $request)
    {
        //return $request->all();
        if($request->ajax()){
            
            $article = new Article;
            $article->title = $request->input("title");
            $article->body = $request->input("body");
            $article->user_id = auth()->user()->id;

            $validator = \Validator::make($request->all(), [
                "title" => "required",
                "body" => "required",
                'image' => 'image|nullable|max:1999'
            ]);
           
            if ($validator->passes()){
                $hasImage = false;
                //Handle file upload
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

                $article->image = $fileNameToStore;
                $article->save();
                //return $article;
                $allArticleIds = Article::pluck('id');
                $user = auth()->user();
                $prev = $article->prev($article);
                $next = $article->next($article);

                $response = array(
                    'status' => 'success',
                    'msg' => "Hello!",
                    "request" => $request->all(),
                    "passesValidation" => true,
                    "article" => [
                        "id" => $article->id,
                        "title" => $article->title,
                        "body" => $article->body,
                        "image" => $article->image,
                        "created_at" => $article->created_at->format('d. M, Y'),
                        "updated_at" => $article->updated_at,
                        "user_id" => $article->user_id,
                    ],
                    "hasImage" => $hasImage,
                    "prev" => $prev,
                    "next" => $next,
                    "allArticleIds" => $allArticleIds,
                    "user" => $user,
                );

                return response()->json($response);

            }
            else{

                $response = array(
                    'status' => 'success',
                    'msg' => "Hello!",
                    "request" => $request->all(),
                    "passesValidation" => false,
                    'errors' => $validator->getMessageBag()->toArray(),
                );
                return response()->json($response);

            }
            
        }
        
    }

}
