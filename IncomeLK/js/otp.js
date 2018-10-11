$(document).ready(function(){
    $("#subscibe").click(function () {  
        console.log("Hello");
        var phoneNumber = $("#otp").val()
        if (phoneNumber == ""){
            $("#otp").css("border-color","red")
        }
        else {
            $("#otp").css("border-color","black")
            window.location.href = "content.html";
            // if (phoneNumber.length == 10 && $.isNumeric(phoneNumber) && (phoneNumber.substring(0,3) == "077" || phoneNumber.substring(0,3) == "076")){
            //     $("#otp").css("border-color","black")
            //     window.location.href = "otp.html";
            // }
            // else {
            //     $("#otp").css("border-color","red")
            // }
        }
    })
});


function getOTP(){
        
    var ajaxurl = baseURL+"/request_otp.php",
    data =  {'otp': $("#otp").val()};
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            console.log(response);
            var result = jQuery.parseJSON(response)
            console.log(result)
            if (result["message"] == "success"){
                if (result["content"].length > 0){
                    console.log("hello")
                   
                }
                else {

                }
            }else {
                console.log("No content availablle")
            }
        },
        error: function() { 
        },
    });
}