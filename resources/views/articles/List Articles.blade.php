@extends("layouts.app")

@section("content")

    @if(count($articles) > 0)
        <div class="container">
            @foreach ($articles as $article)
                <div class="row" style="background-color: whitesmoke;">
                    <div class="col-md-4 col-sm-4">
                        <img class="postCover postCoverIndex" src="/storage/images/{{$article->image}}">
                    </div>
                    <div class="col-md-8 col-sm-8">

                        <br>
                        <?php 
                        if(strlen($article->body) > 400){
                            $bod = substr($article->body,0,400);
                        
                        ?>
                        <p>{!!$bod!!}<a href='/articles/{{$article->id}}'>...Read more</a></p><br>
                        <?php
                        }
                        else{
                        ?>
                            <p>{{$article->body}}</p>
                        <?php
                            
                        }
                        ?>

                        <small class="timestamp">Written on {{$article->created_at->format('d. M, Y')}} by {{$article->user->name}}</small>
                    </div>
                </div>
                <hr class="hrStyle">
            @endforeach
        </div>
    @endif

@endsection