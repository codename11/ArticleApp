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
        let links = elem.getElementsByClassName("pagination").item(0);
        let coll = elem.getElementsByTagName("div").item(0);
        
        coll.appendChild(links);
        //console.log(links);
        //console.log(coll);
        
        //console.log(elem);
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
        $('#maine').html(elem);
        
            //?page=2
      },
      error: (data) => {
        alert("no");
        console.log(data);
      }
    });         
  
}
