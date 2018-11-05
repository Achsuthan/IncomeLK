$(document).ready(function(){

    getAllContent()

    

});

function conentClicked(id){
    //console.log(id)
    localStorage.setItem("content_id",id)
    if (localStorage.getItem("phone") != ""){
        window.location.href = "content.html";
    }
    else {
        window.location.href = "phone.html";
    }
}

function getAllContent(){
        
    var ajaxurl = baseURL+"/get_all_content.php",
    data =  "";
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            //console.log(response);
            var result = jQuery.parseJSON(response)
            //console.log(result)
            if (result["message"] == "success"){
                if (result["content"].length > 0){
                    //console.log("hello")
                    for (var i=0; i<result["content"].length; i++){
                        //console.log("hello")
                        var heading = result["content"][i]["heading"]
                        var type = result["content"][i]["type"]
                        var image = result["content"][i]["image_url"]
                        var contentId = result["content"][i]["content_id"]
                        var url = baseURL+image
                        //console.log(url);
                        
                        var content = "<div class='col-md-6 col-lg-4'>\
                        <a onclick = conentClicked('"+contentId+"')  class='a-block d-flex align-items-center height-md' style='background-image: url("+url+"); '>\
                          <div class='text'>\
                            <div class='post-meta'>\
                              <span class='category'>"+type+"</span>\
                            </div>\
                            <h3>"+heading+"</h3>\
                          </div>\
                        </a>\
                      </div>"

                      $("#content").prepend(content)
                    }
                }
                else {

                }
            }else {
                //console.log("No content availablle")
            }
        },
        error: function() { 
        },
    });
}