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
    
}
