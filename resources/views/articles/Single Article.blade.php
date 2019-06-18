@extends("layouts.app")

@section("content")

        <div id="single-kv" style="background-image: url('/storage/images/{{$article->image}}'); height: 435px; background-position: center top; background-attachment: fixed; background-repeat: no-repeat;background-size:cover;"></div>
        <div id="single-intro">
            <div id="single-intro-wrap"><h1> {{$article->title}}</h1>
                <?php 
                if(strlen($article->body) > 400){
                    $bod = substr($article->body,0,400);
                
                ?>
                    <div>{!!$bod!!}</div><br>
                <?php
                }
                else{
                ?>
                    <div>{{$article->body}}</div>
                <?php
                    
                }
                ?>
                
                <div class="comment-time excerpt-details" style="margin-bottom: 20px; font-size: 14px;"> 
                    <a href="#gotoprofil"> {{$article->user->name}} </a> -
                {{$article->created_at->format('d. M, Y')}}</div>
            </div>
        </div>
        <hr style="color:whitesmoke; width: 50%;">
        <div id="single-body">

            <div id="single-content">
                {!!$article->body!!}
            </div>

        </div>
        
@endsection