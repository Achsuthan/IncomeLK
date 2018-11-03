$(document).ready(function(){
    $("#request_code").click(function () {  
        console.log("Hello");
        var phoneNumber = $("#phone").val()
        if (phoneNumber == ""){
            $("#phone").css("border-color","red")
        }
        else {
            $("#phone").css("border-color","black")
            if (phoneNumber.length == 10 && $.isNumeric(phoneNumber) && (phoneNumber.substring(0,3) == "077" || phoneNumber.substring(0,3) == "076")){
                $("#phone").css("border-color","black")
                getOTP()
            }
            else {
                $("#phone").css("border-color","red")
            }
        }
    })
});


function getOTP(){
        
    var ajaxurl = baseURL+"/request_otp.php",
    data =  {'phone': $("#phone").val()};
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            console.log(response);
            var result = jQuery.parseJSON(response)
            console.log(result)
            if (result["message"] == "success"){
                localStorage.setItem("phone",$("#phone").val())
                window.location.href = "otp.html";
            }else {
                console.log("No content availablle")
            }
        },
        error: function() { 
        },
    });
}