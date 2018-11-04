$(document).ready(function(){

    

    $("#send_message").click(function () {  
        console.log("Hello");

        if ($("#email").val() != "" && $("#message").val() != "" && $("#name").val() != "" && isEmail($("#email").val()) ){
            
            $("#loader").css("display","block")
            $("#email").css("border-color","#ced4da")
            $("#message").css("border-color","#ced4da")
            $("#name").css("border-color","#ced4da")
            sendMessage()
        }
        else {
            if ($("#email").val() == ""){
                $("#email").css("border-color","red")
            }
            else {
                if (!isEmail($("#email").val())){
                    $("#email").css("border-color","red")
                }
            }
            if ($("#message").val() == ""){
                $("#message").css("border-color","red")
            }
            if ($("#name").val() == ""){
                $("#name").css("border-color","red")
            }
            
        }
        
    })


    $("#message").keyup(function(event){
        $("#message").css("border-color","#ced4da")
    })

    $("#name").keyup(function(event){
        $("#name").css("border-color","#ced4da")
    })

    $("#email").keyup(function(event){
        $("#email").css("border-color","#ced4da")
    })
});


function isEmail (email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}




function sendMessage(){
        
    var ajaxurl = baseURL+"/send_message.php",
    data =  {'phone': $("#phone").val(),'email': $("#email").val(), 'name':$("#name").val(),'message':$("#message").val()};
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
                $("#name").text("")
                $("#message").text("")
                $("#phone").text("")
                $("#email").text("")
                $("#error").css("display","none")
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