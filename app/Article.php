<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Article extends Model
{
    protected $fillable = [
        "title", "body"
    ];

    public function user(){
        return $this->belongsTo("App\User","user_id");
    }
    
    public function prev($article)
    {
        if($article->orderBy('id', 'ASC')->where('id', '>', $article->id)->first()){
            $prev = $article->orderBy('id', 'ASC')->where('id', '>', $article->id)->first()->id;
        }
        else{
            $prev = $article->min('id');
        }
        
        return $prev;
    } 

    public function next($article)
    {
        if($article->orderBy('id', 'DESC')->where('id', '<', $article->id)->first()){
            $next = $article->orderBy('id', 'DESC')->where('id', '<', $article->id)->first()->id;
        }
        else{
            $next = $article->max('id');
        }
        
        return $next;
    } 

}
