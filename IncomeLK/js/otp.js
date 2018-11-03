$(document).ready(function(){
    $("#subscibe").click(function () {  
        console.log("Hello");
        var phoneNumber = $("#otp").val()
        if (phoneNumber == ""){
            $("#otp").css("border-color","red")
        }
        else {
            $("#otp").css("border-color","black")
            verfyOTP()
        }
    })
});


function verfyOTP(){
        
    var ajaxurl = baseURL+"/verify_otp.php",
    data =  {'otp': $("#otp").val(),'phone':localStorage.getItem("phone")};
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            console.log(response);
            var result = jQuery.parseJSON(response)
            console.log(result)
            if (result["message"] == "success"){
                console.log("hello")
                window.location.href = "content.html";
            }else {
                console.log("No content availablle")
            }
        },
        error: function() { 
        },
    });
}