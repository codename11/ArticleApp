<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ArtApp') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/skripta.js') }}" defer></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="DisplayNone"></div>

    <?php
        $conflu = "container-fluid";
    ?>
    @if(Route::getFacadeRoot()->current()->uri()=="articles/create" || Route::getFacadeRoot()->current()->uri()=="articles/{article}/edit" || Route::getFacadeRoot()->current()->uri()=="ajaksCreate")
        <?php
            $conflu = "container";
        ?>
    @endif

    @include("inc.navbar")
    <div class="{{$conflu}} mainContainer" style="margin-top: 70px;">
        @include("inc.messages")
        <div  id="messages"></div>
        
        @yield('content')

        <div  id="maine">ggg</div>

        <?php
            $mytime = Carbon\Carbon::now();
        ?>
        
        <div class="footer navbar-dark bg-dark" style="z-index: 999;">
            <p class="mt-1" class="footCompany">© 2017-{{$mytime->format('Y')}} Company Name</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a class="footLinks" href="#">Privacy</a></li>
                <li class="list-inline-item"><a class="footLinks" href="#">Terms</a></li>
                <li class="list-inline-item"><a class="footLinks" href="#">Support</a></li>
            </ul>
        </div>
    </div>

<?php
if(Route::getFacadeRoot()->current()->uri()=="list"){
?>
<script>
    $(document).ready(function(){
        
        $(document).on("change", "#sel1", function(event){
            
            let page = 0;
            if($(this).attr("href")){
                page = $(this).attr("href").split("page=")[1];
            }
            else{
                page = 1;
            }

            let selected = event.target.value;
            if(selected){
                ajaksIndex(page, selected);
            }
            else{
                ajaksIndex(page);
            }
            
        });

        $(document).on("click", ".pagination a", function(event){
            let page = $(this).attr("href").split("page=")[1];
            //let page = $(this).html();
            event.preventDefault();

            let selected = document.getElementById("sel1").value;
            if(selected){
                ajaksIndex(page, selected);
            }
            else{
                ajaksIndex(page);
            }

        });

        ajaksIndex();

    });
  
</script>

<?php
}
?>
<?php
if(Route::getFacadeRoot()->current()->uri()=="list/{id}"){
?>
    <script>
        
        $(document).ready(function(){
    
            ajaksShow();
        });

    </script>
    
<?php
    }
?>

<script src="/ckeditor/ckeditor.js"></script>
<script>
    if(document.getElementById("ckeditor")){
        CKEDITOR.replace("ckeditor");
    }
</script>

</body>
</html>
