function ajaksIndex(page, selected){
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    
    console.log("hitIndex");
    console.log(page);
    
    $.ajax({
        url: '/indexAjax',
        type: 'POST',
        data: {_token: token , message: "bravo", page: page && parseInt(page) && parseInt(page) > 0  ? page : 1, selected: selected ? selected : 0},
        dataType: 'JSON',

        success: (response) => { 
            console.log("success");
            console.log(response);
            let body = "";
            let html = "<div class='container'>";
            let select = "<label class='row' for='sel1' style='width: auto;'>Select specific user's articles:</label><select class='form-control row' style='width: auto;' id='sel1' name='sellist1'><option value='0'>All Users</option>";
            
            if(response){

                if(response.users && response.users.length > 0){
                    
                    for(let i=0;i<response.users.length;i++){
                        let ifSelected = (selected && selected==response.users[i].id) ? "selected" : "";
                        select += "<option value='"+response.users[i].id+"' "+ifSelected+">"+response.users[i].name+"</option>";
                    }
                    select += "</select><br>";
    
                }

                html += select;

                if(response.articles){

                    if(response.articles.data && response.articles.data.length > 0){

                        let len = response.articles.data.length;
                        for(let i=0;i<len;i++){
    
                            html += "<div class='row' style='background-color: whitesmoke;'><div class='col-md-4 col-sm-4'><a href='/list/"+response.articles.data[i].id+"'><img class='postCover postCoverIndex' src='/storage/images/"+response.articles.data[i].image+"'></a></div><div class='col-md-8 col-sm-8'><br>";
                        
                            if(response.articles.data[i].body.length > 400){
                                body = response.articles.data[i].body.substring(0, 400)+"<a href='/list/"+response.articles.data[i].id+"'>...Read more</a>";
                            }
                            else{
                                body = response.articles.data[i].body;
                            }
                            
                            html += "<p>"+body+"</p>";
                            html += "<small class='timestamp'>Written on "+response.articles.data[i].created_at+" by "+response.articles.data[i].user.name+"</small></div></div><hr class='hrStyle'>";
                        }
    
                    }
                    
                    if(response.articles.last_page){
    
                        let pagination = "<div class='container'><ul class='pagination justify-content-center'><li class='page-item'><a class='page-link' href='#'>Previous</a></li>";
                        for(let i=0;i<response.articles.last_page;i++){
                            pagination += "<li class='page-item'><a class='page-link' href='#'>"+(i+1)+"</a></li>";
                        }
                        pagination += "<li class='page-item'><a class='page-link' href='#'>Next</a></li></ul></div>";
        
                    }
    
                    html += "</div>";

                }
                
            }

            let maine = document.getElementById("maine");
            maine.innerHTML = "";
            if(maine){

                maine.innerHTML += html+response.pagination;
            }
        },
        error: (response) => {
            console.log("error");
            console.log(response);
        }
    }); 
    
}

function ajaksShow(){
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    console.log("hitShow");
    
    let urlId = window.location.href;
    let getaArticleId = urlId.lastIndexOf("/");
    let articleId = urlId.substring(getaArticleId+1, urlId.length);
    console.log(articleId);
    
    $.ajax({
        url: '/showAjax',
        type: 'POST',
        data: {_token: token , message: "bravo", articleId: articleId},
        dataType: 'JSON',
        success: (response) => { 
            console.log("success");
            console.log(response);
            let body = "";
            let imageStyle = ";height: 435px; background-position: center top; background-attachment: fixed; background-repeat: no-repeat;background-size:cover;";

            let img = response.article.image;
            let find = " ";
            let rep = new RegExp(find, 'g');
            img = img.replace(rep, "%20");
            let mymodalDelete = "<div class='modal' id='myModalDelete'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><h4 class='modal-title'>Do you really want to delete this article?</h4><button type='button' class='close' data-dismiss='modal'>&times;</button></div><div class='modal-body'>deleting ...</div><div class='modal-footer'><button class='btn btn-outline-danger' style='position: absolute;left:0px; margin-left: 1rem;' onclick='ajaksDelete(this)'>Delete</button><button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button></div></div></div></div>";
            
            let updateForm = "<form method='POST' id='updateForm' enctype='multipart/form-data'><input type='hidden' name='_method' value='PUT'><input type='hidden' name='_token' value='"+token+"'><input id='' type='hidden' name='article_id' value='"+response.article.id+"' /><div class='form-group'><label class='label' for='title'>Title</label><input type='text' class='form-control' name='title' placeholder='Title' value='"+response.article.title+"' required></div><div class='form-group'><label for='body'>Body</label><textarea class='form-control' id='ckeditor' name='body' placeholder='Body' required>"+response.article.body+"</textarea></div><div class='form-group'><input type='file' name='image' id='imagex'></div></form>";
            let mymodalUpdate = "<div class='modal' id='myModalUpdate'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><h4 class='modal-title'>Do you really want to update this article?</h4><button type='button' class='close' data-dismiss='modal'>&times;</button></div><div class='modal-body'>"+updateForm+"</div><div class='modal-footer'><button class='btn btn-outline-success' style='position: absolute;left:0px; margin-left: 1rem;' onclick='ajaksUpdate()'>Update</button><button type='button' class='btn btn-info' data-dismiss='modal'>Close</button></div></div></div></div>";
            
            let imageUrl = "/storage/images/"+img;
            let html = "<a href='/list' class='btn btn-outline-info btn-sm'>Go Back</a><div class='nextPrev'><a href='/list/"+response.prev+"' class='btn btn-outline-success'><i class='fas fa-arrow-left'></i></a><a href='/list/"+response.next+"' class='btn btn-outline-info'><i class='fas fa-arrow-right'></i></a></div><br><br><div id='single-kv' style='background-image: url("+imageUrl+")"+imageStyle+";background-color: red !important;'></div><div id='single-intro'><div id='single-intro-wrap'><h1> "+response.article.title+"</h1>";
            
                if(response && response.article && response.article.body && response.article.body.length > 400){
                    body = response.article.body.substring(0, 400)+"<a id='readMore' href='/list/"+response.article.id+"'>...Read more</a>";
                }
                else if(response && response.article && response.article.body && response.article.body.length <= 400){
                    body = response.article.body;
                }
           
                html += body;
                
                let updateAndDelete = response.currentUser.id==response.user.id ? "<button id='update' class='btn btn-outline-info btn-sm float-left' data-toggle='modal' data-target='#myModalUpdate' onclick='getCkEditor()'>Update</button><button class='btn btn-outline-danger btn-sm float-right' data-toggle='modal' data-target='#myModalDelete'>Delete</button>" : "";

                html += "<div class='comment-time excerpt-details' style='margin-bottom: 20px; font-size: 14px;'><a href='#gotoprofil'> "+response.user.name+" </a> - "+response.article.created_at+"</div>"+updateAndDelete+"</div></div><br><hr style='color:whitesmoke; width: 50%;'><div id='single-body'><div id='single-content'>"+response.article.body+"</div></div>"+mymodalDelete+mymodalUpdate;
                    
            if(document.getElementById("maine")){
                    
                document.getElementById("maine").innerHTML = html;

            }
            
        },
        error: (response) => {
            console.log("error");
            console.log(response);
        }
    }); 
    
}

function ajaksDelete(){
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    console.log("hitDel");
    console.log(token);

    let urlId = window.location.href;
    let getaArticleId = urlId.lastIndexOf("/");
    let articleId = urlId.substring(getaArticleId+1, urlId.length);
    console.log(articleId);
    $.ajax({
        url: '/deleteAjax/'+articleId,
        type: 'POST',
        data: {_token: token , message: "bravo", articleId: articleId},
        dataType: 'JSON',
        success: (response) => { 
            console.log("success");
            console.log(response);
            window.location.href = "/list";
        },
        error: (response) => {
            console.log("error");
            console.log(response);
        }
    }); 
    
}

function ajaksUpdate(){
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    console.log("hitUpdate");

    let updateForm = document.getElementById("updateForm");
    
    let urlId = window.location.href;
    let getaArticleId = urlId.lastIndexOf("/");
    let articleId = urlId.substring(getaArticleId+1, urlId.length);

    let updateFormElements = {};
    updateFormElements.title = updateForm.elements[3].value;
    updateFormElements.body = CKEDITOR.instances.ckeditor.getData();//Ovo trece po redu je id polja sa ckeditorom.
    updateFormElements.image = updateForm.elements[5].files[0];
    
    let imginp = document.getElementById("imagex").files;

    var myformData = new FormData();        
    myformData.append('title', updateFormElements.title);
    myformData.append('body', updateFormElements.body);
    myformData.append('image', updateFormElements.image);
    myformData.append('_token', token);
    myformData.append('articleId', articleId);

    $.ajax({

        url: '/updateAjax/'+articleId,
        enctype: 'multipart/form-data',
        type: 'POST',
        data: myformData,
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success: (response) => { 
            console.log("success");
            console.log("+++++");
            console.log(response);
            console.log("+++++");
            let body = "";
            let imageStyle = ";height: 435px; background-position: center top; background-attachment: fixed; background-repeat: no-repeat;background-size:cover;";

            let img = response.article.image;
            let find = " ";
            let rep = new RegExp(find, 'g');
            img = img.replace(rep, "%20");
            let mymodalDelete = "<div class='modal' id='myModalDelete'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><h4 class='modal-title'>Do you really want to delete this article?</h4><button type='button' class='close' data-dismiss='modal'>&times;</button></div><div class='modal-body'>deleting ...</div><div class='modal-footer'><button class='btn btn-outline-danger' style='position: absolute;left:0px; margin-left: 1rem;' onclick='ajaksDelete(this)'>Delete</button><button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button></div></div></div></div>";
            
            let updateForm = "<form method='POST' id='updateForm' enctype='multipart/form-data'><input type='hidden' name='_method' value='PUT'><input type='hidden' name='_token' value='"+token+"'><input id='' type='hidden' name='article_id' value='"+response.article.id+"' /><div class='form-group'><label class='label' for='title'>Title</label><input type='text' class='form-control' name='title' placeholder='Title' value='"+response.article.title+"' required></div><div class='form-group'><label for='body'>Body</label><textarea class='form-control' id='ckeditor' name='body' placeholder='Body' required>"+response.article.body+"</textarea></div><div class='form-group'><input type='file' name='image' id='imagex'></div></form>";
            let mymodalUpdate = "<div class='modal' id='myModalUpdate'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><h4 class='modal-title'>Do you really want to update this article?</h4><button type='button' class='close' data-dismiss='modal'>&times;</button></div><div class='modal-body'>"+updateForm+"</div><div class='modal-footer'><button class='btn btn-outline-success' style='position: absolute;left:0px; margin-left: 1rem;' onclick='ajaksUpdate()' data-dismiss='modal'>Update</button><button type='button' class='btn btn-info' data-dismiss='modal'>Close</button></div></div></div></div>";
            
            let imageUrl = "/storage/images/"+img;
            let html = "<a href='/list' class='btn btn-outline-info btn-sm'>Go Back</a><div class='nextPrev'><a href='/list/"+response.prev+"' class='btn btn-outline-success'><i class='fas fa-arrow-left'></i></a><a href='/list/"+response.next+"' class='btn btn-outline-info'><i class='fas fa-arrow-right'></i></a></div><br><br><div id='single-kv' style='background-image: url("+imageUrl+")"+imageStyle+";background-color: red !important;'></div><div id='single-intro'><div id='single-intro-wrap'><h1> "+response.article.title+"</h1>";
            
                if(response && response.article && response.article.body && response.article.body.length > 400){
                    body = response.article.body.substring(0, 400)+"<a id='readMore' href='/list/"+response.article.id+"'>...Read more</a>";
                }
                else if(response && response.article && response.article.body && response.article.body.length <= 400){
                    body = response.article.body;
                }
           
                html += body;

                html += "<div class='comment-time excerpt-details' style='margin-bottom: 20px; font-size: 14px;'><a href='#gotoprofil'> "+response.user.name+" </a> - "+response.article.created_at+"</div><button id='update' class='btn btn-outline-info btn-sm float-left' data-toggle='modal' data-target='#myModalUpdate' onclick='getCkEditor()'>Update</button><button class='btn btn-outline-danger btn-sm float-right' data-toggle='modal' data-target='#myModalDelete'>Delete</button></div></div><br><hr style='color:whitesmoke; width: 50%;'><div id='single-body'><div id='single-content'>"+response.article.body+"</div></div>"+mymodalDelete+mymodalUpdate;
                    
            if(document.getElementById("maine")){
                    
                document.getElementById("maine").innerHTML = html;
                $('myModalUpdate').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }

        },
        error: (response) => {
            console.log("error");
            console.log(response);
        }
    }); 
    
}

function getCkEditor(){
    console.log("got ckeditor");
    CKEDITOR.replace("ckeditor")

}

$(document).ready(function(){
    $("#subCreate").click(function(event){
      event.preventDefault();
    });
});

function ajaksCreate(){
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    console.log("hitCreate");
    
    let createForm = document.getElementById("createArticle");
    
    let createFormElements = {};
    createFormElements.title = createForm.elements[1].value;
    createFormElements.body = CKEDITOR.instances.ckeditor.getData();//Ovo trece po redu je id polja sa ckeditorom.
    createFormElements.image = createForm.elements[3].files[0] ? createForm.elements[3].files[0] : "";

    var myformData = new FormData();        
    myformData.append('title', createFormElements.title);
    myformData.append('body', createFormElements.body);
    myformData.append('image', createFormElements.image);
    myformData.append('_token', token);

    $.ajax({

        url: '/createAjax',
        enctype: 'multipart/form-data',
        type: 'POST',
        data: myformData,
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success: (response) => { 
            console.log("success");
            console.log(response);
            
            if(response.errors){
                let messages = document.getElementById("messages");

                if(messages.innerHTML.length > 0){
                    messages.innerHTML = "";
                }

                for(let error in response.errors){

                    let newItem = document.createElement("div");
                    newItem.className += "alert alert-danger";
                    let textnode = document.createTextNode(response.errors[error]);
                    newItem.appendChild(textnode);
    
                    messages.insertBefore(newItem, messages.childNodes[0]);

                }

            }
            else{
                window.location.href = "/list/"+response.article.id;
            }
          
        },
        error: (response) => {
            console.log("error");
            console.log(response);
        }
    }); 
    
}
