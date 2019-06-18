function ajax(me){

    let var1 = "gg";
    let var2 = "bruh";
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    let url = "/articles";
    
    $.ajax({
      type: "POST",
      url: url,
      headers:{
              "X-CSRF-TOKEN": token
          },
      data: {
          var1: var1,
          var2: var2,
          elem: {
              id: me.id ? me.id : null,
              class: me.className ? me.className : null,
              value: me.value ? me.value : null,
              innerHTML: me.innerHTML ? me.innerHTML : null,
          }
      },
      success: (data) => {
          console.log(data);
          console.log("yes");
      },
      error: (data) => {
        console.log(data);
        console.log("no");
      }
    });         
  
}