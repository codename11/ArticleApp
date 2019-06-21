function ajaxIndex(page){

    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    let url = "articles"+"/ajaxIndex?page="+page;

    /*if(formId){

        let form = $("#"+formId).serialize();

        console.log(form);

    }*/
    
    $.ajax({
      type: "GET",
      url: url,
      headers:{
              "X-CSRF-TOKEN": token
          },
      success: (data) => {
        
        /*console.log(page);
        console.log("******************");
        console.log(data);*/

        var elem = document.createElement( 'div' );
        elem.innerHTML = data;
        //let links = elem.getElementsByClassName("pagination").item(0);
        let coll1 = elem.getElementsByTagName("div");
        let coll2 = elem.getElementsByTagName("div")[0];
        let coll3 = elem.getElementsByTagName("div")[1];
        let coll4 = elem.childNodes;
        let linkParent = elem.querySelector("#linkParent");

        //coll.appendChild(links);
        //console.log(links);
        //console.log(linkParent);
        //console.log(elem.childNodes.length);
        let arr = [];
        let elm;
        for(let i=0;i<elem.childNodes.length;i++){
            
            if(elem.childNodes[i].innerHTML !== undefined){
                //console.log(i);
                //console.log(elem.childNodes[i]);
                arr.push(elem.childNodes[i]);
                
            }
            
        }

        $('#maine').html(arr);

        /*let currPage = 0;
        let html = "<div class='container'>";
        let articleBody = "";
        
            if(data.articles.data.length > 0){
                let len = data.articlesAll.length / data.articles.total;
                for(let i=0;i<len;i++){
                    currPage = i;
                    let created_at = data.articles.data[i].created_at.replace(/-/g,"/").split(" ")[0];

                    html += "<div class='row' style='background-color: whitesmoke;'><div class='col-md-4 col-sm-4'><a href='/articles/"+data.articles.data[i].id+"'><img class='postCover postCoverIndex' src='/storage/images/"+data.articles.data[i].image+"'></a></div><div class='col-md-8 col-sm-8'><br>";
                    
                    if(data.articles.data[i].body.length > 400){
                        articleBody = data.articles.data[i].body.substring(0, 400);
                       html += "<p>"+articleBody+"<a href='/articles/"+data.articles.data[i].id+"'>...Read more</a></p>";
                    }
                    else{
                        html += "<p>"+data.articles.data[i].body+"</p>";
                    }
                    
                    html += "<small class='timestamp'>Written on "+created_at+" by "+data.articles.data[i].user.name+"</small></div></div><hr class='hrStyle'></hr>";
                    history.pushState(null, null, "/articles?page="+(i+1));
                }

                
            }
        html+="<div class='d-flex' style='margin: 10px 0px;padding-top: 20px;'><div class='mx-auto' style='line-height: 10px;'>"+links+"</div></div></div>";*/
        
        
            //?page=2
      },
      error: (data) => {
        alert("no");
        console.log(data);
      }
    });         
  
}

function ajaks(page){
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    console.log("hit");
    console.log(page);

    $.ajax({
        url: '/postajax',
        type: 'POST',
        data: {_token: token , message: "bravo", page: page && parseInt(page) && parseInt(page) > 0  ? page : 1},
        dataType: 'JSON',
        success: (response) => { 
            console.log("success");
            console.log(response);
            let body = "";
            let html = "<div class='container'>";
            
            let len = response.articles.data.length;
            for(let i=0;i<len;i++){

                html += "<div class='row' style='background-color: whitesmoke;'><div class='col-md-4 col-sm-4'><a href='/articles/"+response.articles.data[i].id+"'><img class='postCover postCoverIndex' src='/storage/images/"+response.articles.data[i].image+"'></a></div><div class='col-md-8 col-sm-8'><br>";
            
                if(response.articles.data[i].body.length > 400){
                    body = response.articles.data[i].body.substring(0, 400)+"<a href='/articles/"+response.articles.data[i].id+"'>...Read more</a>";
                }
                else{
                    body = response.articles.data[i].body;
                }
                
                html += "<p>"+body+"</p>";
                html += "<small class='timestamp'>Written on "+response.articles.data[i].created_at+" by "+response.articles.data[i].user.name+"</small></div></div><hr class='hrStyle'>";
            }

            let pagination = "<div class='container'><ul class='pagination justify-content-center'><li class='page-item'><a class='page-link' href='#'>Previous</a></li>";
            for(let i=0;i<response.articles.last_page;i++){
                pagination += "<li class='page-item'><a class='page-link' href='#'>"+(i+1)+"</a></li>";
            }
            pagination += "<li class='page-item'><a class='page-link' href='#'>Next</a></li></ul></div>";
            
            
            //console.log(pagination);
            html += "</div>";
            
            if(document.getElementById("maine")){
                    
                    document.getElementById("maine").innerHTML = html+response.pagination;
            }
        },
        error: (response) => {
            console.log("error");
            console.log(response);
        }
    }); 
    
}
