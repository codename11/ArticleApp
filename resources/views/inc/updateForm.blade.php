<form method="POST" action="">
    {{method_field("PUT")}}
    @csrf
    <input id="" type="hidden" name="article_id" value="" />
    <div class="form-group">
        <label class="label" for="title">Title</label>
        <input type="text" class="form-control" name="title" placeholder="Title" value="" required>
    </div>

    <div class="form-group">
        <label for="body">Body</label>
        <textarea class="form-control" id="ckeditor" name="body" placeholder="Body" required></textarea>
    </div>
      
</form>