$(document).ready(function(){

    $("#error").css("display","block")
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
                $("#loader").css("display","block")
                getOTP()
            }
            else {
                $("#phone").css("border-color","red")
            }
        }
    })

    if (localStorage.getItem("phone") != ""){
        window.location.href = "content.html";
    }
});

$("#phone").keyup(function(event){
    $("#error").text("")
    $("#phone").css("border-color","black")
    $("#error").css("display","none")
})


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
                $("#loader").css("display","none")
                localStorage.setItem("phone",$("#phone").val())
                $("#error").text("")
                $("#error").css("display","none")
                window.location.href = "otp.html";
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