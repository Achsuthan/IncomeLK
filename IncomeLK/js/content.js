$(document).ready(function(){    
    checkUser()
});

function checkUser(){
    console.log(localStorage.getItem("phone"));
    
    var ajaxurl = baseURL+"/check_user.php",
    data =  {"phone": localStorage.getItem("phone")};
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            //console.log(response);
            var result = jQuery.parseJSON(response)
            console.log(result)
            if (result["message"] == "success"){
                getAllContentById()
            }else {
                localStorage.setItem("phone","")
                window.location.href = "phone.html";
            }
        },
        error: function() { 
        },
    });
}

function conentClicked(id){
    //console.log(id)
    localStorage.setItem("content_id",id)
    if (localStorage.getItem("userStatus") == true){

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

function getAllContentById(){
        
    var ajaxurl = baseURL+"/get_content_by_id.php",
    data =  {"content_id":localStorage.getItem("content_id")};
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            //console.log(response);
            var result = jQuery.parseJSON(response)
            //console.log(result)
            if (result["message"] == "success"){
                    //console.log("hello specific content")
                    var heading = result["content"]["heading"]
                    var type = result["content"]["type"]
                    var image = result["content"]["image_url"]
                    var contentId = result["content"]["content_id"]
                    var url = baseURL+image
                    var english = result["content"]["english"]
                    var sinhala = result["content"]["sinhala"]

                    //console.log(heading+"heading ");
                    

                    $("#heading").text(heading)
                    $("#type").text(type)
                    $("#english").text(english)
                    $("#sinhala").text(sinhala)
                    $("#image").attr("src", url);

                    getAllContent()
            }else {
                //console.log("No content availablle")
            }
        },
        error: function() { 
        },
    });
}