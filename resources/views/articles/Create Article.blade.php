@extends("layouts.app")

@section("content")
    <h1>Create Article</h1> 
    <?php /*Pregleda zasebno za title i description 
    da li ima gresaka. Ukoliko ih ima, dodeljuje im klasu(crveni border).*/
        $errTitle = $errors->has('title') ? 'border-danger' : '';
        $errBody = $errors->has('body') ? 'border-danger' : '';
    ?>
    <form method="POST" id="createArticle" enctype="multipart/form-data">
        @csrf
        <!-- https://laravel.com/docs/5.7/validation#available-validation-rules -->
        <div class="form-group">
            <label for="title">Title:</label> <!--Ovo u 'value' atributu da prilikom neuspesne validacije zapamti staru vrednost iz polja.-->
            <input class="form-control {{$errTitle}}" type="text" name="title" placeholder="Post title" required value="{{old('title')}}">
        </div>
        <div class="form-group">
            <label for="body">Body:</label>
            <textarea id="ckeditor" class="form-control {{$errBody}}" name="body" placeholder="Post body" required value="{{old('body')}}"></textarea>
        </div>

        <div class="form-group">
            <input type="file" name="image" id="image">
        </div>

        <div>
            <button type="submit" id="subCreate" class="btn btn-primary" onclick="ajaksCreate()">Create Article</button>
        </div>
    </form>
    
@endsection