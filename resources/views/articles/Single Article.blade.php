@extends("layouts.app")

@section("content")

        <a href="{{ url()->previous() }}" class="btn btn-outline-info btn-sm">Go Back</a>
        <div class="nextPrev">
            <a href="/articles/{{$prev}}" class="btn btn-outline-success"><i class="fas fa-arrow-left"></i></a>
            <a href="/articles/{{$next}}" class="btn btn-outline-info"><i class="fas fa-arrow-right"></i></a>
        </div><br><br>

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

        <div class="container" style="margin-bottom: 80px;">
            @if(!Auth::guest())
                @if(Auth::check() && Auth::user()->id==$article->user->id)
                <hr>
                    <a href="/articles/{{$article->id}}/edit" class="btn btn-outline-primary btn-sm">Edit</a>

                    <form action="/articles/{{$article->id}}" method="post" class="float-right">
                        @csrf
                        {{method_field("DELETE")}}
                        
                        <!-- The Modal -->
                        <div class="modal" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                            
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                    <h4 class="modal-title">Are you sure you want to delete this post?</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                            
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                            
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                            
                                </div>
                            </div>
                        </div>

                    </form>
                
                <button class="btn btn-outline-danger btn-sm float-right" data-toggle="modal" data-target="#myModal">Delete</button>
            @endif
        @endif
    </div>
@endsection