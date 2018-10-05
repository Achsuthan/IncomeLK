$(document).ready(function(){

    getContents()
    var dataSet = []
    var columns = { title : "Content ID",
                    title : "Content Heading",
                    title : "Type",
                    title : "Image",
                    title : "Edit",
                    title : "Delete",
                    title : "Move to Top"
                  }

    function getContents(){
        var ajaxurl = baseURL+"/get_all_content.php",
        data =  {'otp': $("#otp").val(), 'email': $("#email").val()};
        $.ajax({

            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function(response) { 
                console.log(response);
                var result = jQuery.parseJSON(response)
                console.log(result)
                if (result["message"] == "success"){
                    var result = jQuery.parseJSON(response)
                    if (result["message"] == "success"){
                        if (result.length > 0){
                            for (var i =0; i< result.length; i ++){
                                tmpResult = result[i]
                                var tmp =  []
                                tmp.push(tmpResult["content_id"])
                                tmp.push(tmpResult["heading"])
                                tmp.push(tmpResult["type"])
                                tmp.push(tmpResult["image_url"])
                                tmp.push("")
                                tmp.push("")
                                tmp.push("")
                                dataSet.push(tmp)
                            }
                            
                            if (dataSet.length > 0) {
                                $('#dataTables-content').DataTable({
                                    responsive: true,
                                    data: dataSet,
                                    columns: [ columns
                                    ]
                                });
                            }
                        }
                    }
                    else {
                    }
                }else {
                }
            },
            error: function() { 
            },
        });
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
      }

});