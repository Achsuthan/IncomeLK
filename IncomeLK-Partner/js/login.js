$(document).ready(function(){

    var emailValid = false
    var email = ""

    
    $('#email').keyup(function(e){
        $("#email-group").removeClass()
        $("#email-group").addClass("form-group")
    });

    $('#otp').keyup(function(e){
        $("#email-otp").removeClass()
        $("#email-otp").addClass("form-group")
    });
    

    $("#login").click(function(){
        if (!emailValid) {
            if (isEmail($("#email").val())) {

                $("#container").css("display","none")
                $("#loader").css("display","block")


                $("#email-group").removeClass()
                $("#email-group").addClass("form-group")

                getCode()
            }
            else {
                $("#email-goup").removeClass()
                $("#email-group").addClass("form-group has-error")
            }
        }
        else {
            var otp = $("#otp").val()
            if (otp.length > 3 && email == $("#email").val()){

                //$("#container").css("display","none")
                //$("#loader").css("display","block")
                
                $("#otp-group").removeClass()
                $("#otp-group").addClass("form-group")

                verifyCode()
            }
            else if (email != $("#email").val()) {
                emailValid = false
                $("#otp-group").css("display","none")
                $("#email-goup").removeClass()
                $("#email-group").addClass("form-group has-error")
            }
            else {
                $("#otp-goup").removeClass()
                $("#otp-group").addClass("form-group has-error")
            }
        }
        
    });

    function getCode(){
        var ajaxurl = baseURL+"/request_code.php",
        data =  {'email': $("#email").val()};
        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function(response) { 
                console.log(response)
                var result = jQuery.parseJSON(response)
                if (result["message"] == "success"){
                    $("#container").css("display","block")
                    $("#loader").css("display","none")
                    $("#otp-group").css("display","block")
                    emailValid = true
                    email = $("#email").val()
                    $("#email-group").removeClass()
                    $("#email-group").addClass("form-group")
                }else {
                    $("#container").css("display","block")
                    $("#loader").css("display","none")
                    emailValid = false
                    email = ""
                    $("#otp-group").css("display","none")
                    $("#email-goup").removeClass()
                    $("#email-group").addClass("form-group has-error")
                }
            },
            error: function() { 
            },
        });
    }
    function verifyCode(){
        
        var ajaxurl = baseURL+"/login_admin.php",
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
                        $("#container").css("display","block")
                        $("#loader").css("display","none")
                        window.location.href = "dashboard.html";
                    }
                    else {
                        $("#container").css("display","block")
                        $("#loader").css("display","none")
                        $("#otp-goup").removeClass()
                        $("#otp-group").addClass("form-group has-error")
                    }
                }else {
                    $("#otp-goup").removeClass()
                    $("#otp-group").addClass("form-group has-error")
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