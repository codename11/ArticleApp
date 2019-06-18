@extends("layouts.app")

@section("content")
<div class="containerBird">
    <!--Credits for animation goes to: https://codepen.io/matchboxhero/pen/RLebOY?editors=1100 -->
    <!--My part are just links and class for them. -->
    <a href="/" class="centerName"><h1>{{ config('app.name', 'ArtApp') }}</h1></a>
    
    @if(Auth::check())
    
        <div class="bird-container bird-container--one" title="List Articles">
            <a href="/articles" class="flyingLink">List Articles</a>
        </div>
        
        <div class="bird-container bird-container--two" title="Create Article">
            <a href="/articles/create" class="flyingLink">Create Article</a>
        </div>

        <div class="bird-container bird-container--three" title="List Articles">
                <a href="/articles" class="flyingLink">List Articles</a>
        </div>
        
        <div class="bird-container bird-container--four" title="Create Article">
            <a href="/articles/create" class="flyingLink">Create Article</a>
        </div>
    
    @else

        <div class="bird-container bird-container--one" title="Login">
            <a href="{{ route('login') }}" class="flyingLink">Login</a>
        </div>
        
        <div class="bird-container bird-container--two" title="Register">
            <a href="{{ route('register') }}" class="flyingLink">Register</a>
        </div>

        <div class="bird-container bird-container--three" title="Login">
            <a href="{{ route('login') }}" class="flyingLink">Login</a>
        </div>
        
        <div class="bird-container bird-container--four" title="Register">
            <a href="{{ route('register') }}" class="flyingLink">Register</a>
        </div>

    @endif

</div>
<!--end credits-->
@endsection