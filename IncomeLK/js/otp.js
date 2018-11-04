$(document).ready(function(){

    $("#subscibe").click(function () {  
        console.log("Hello");
        var phoneNumber = $("#otp").val()
        if (phoneNumber == ""){
            $("#otp").css("border-color","red")
        }
        else {
            $("#otp").css("border-color","black")
            $("#loader").css("display","block")
            verfyOTP()
        }
    })

    if(localStorage.getItem("phone") == ""){
        window.location.href = "phone.html";
    }
});

$("#otp").keyup(function(event){
    $("#error").text("")
    $("#error").css("display","none")
})

$("#get_code").click(function(){
    getOTP()
})


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
                $("#loader").css("display","none")
                $("#error").text("")
                $("#error").css("display","none")
                window.location.href = "content.html";
            }else {
                $("#loader").css("display","none")
                console.log("No content availablle")
                $("#error").text(result["details"])
                $("#error").css("display","block")
            }
        },
        error: function() { 
        },
    });
}

function getOTP(){
        
    var ajaxurl = baseURL+"/request_otp.php",
    data =  {'phone': localStorage.getItem("phone")};
    $.ajax({

        url: ajaxurl,
        data: data,
        type: 'POST',
        success: function(response) { 
            console.log(response);
            var result = jQuery.parseJSON(response)
            console.log(result)
            if (result["message"] == "success"){
                
            }else {
            }
        },
        error: function() { 
        },
    });
}