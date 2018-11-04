
    var dataSetContent = []
    var columnsContent = [{ title : "ID", orderable : false},
                  { title : "Content_ID", orderable : false},
                  { title : "Image"},
                  {  title : "Content Heading"},
                  {  title : "Type"},
                  {  title : "Edit",  orderable : false},
                  {  title : "Delete",  orderable : false},
                  {  title : "Move to Top", orderable : false} ]

    var dataSetAdmin = []
    var columnsAdmin = [{title: "Email"},{title: "Delete", orderable : false}]

    var AdminTable = $('#dataTables-admin').DataTable({
        data: dataSetAdmin,
        columns: columnsAdmin,
        responsive: true,
    });

    var contentTable = $('#dataTables-content').DataTable({
        
        data: dataSetContent,
        columns: columnsContent,
        responsive: true,
    });

    var create = true
    var editId = ""
    var createdContentId = ""


    getContents()
    getAdmins()
    getDashboardDetails()

    

    function getContents(){
        dataSetContent = []
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
                        if (result["content"].length > 0){
                            console.log("hello")
                            for (var i =0; i< result["content"].length; i++){
                                tmpResult = result["content"][i]
                                var tmp =  []
                                tmp.push(i+1)
                                tmp.push(tmpResult["content_id"])
                                tmp.push("<img src = "+baseURL+"/"+tmpResult['image_url']+" responsive style='max-height:100px; max-width: 100px'> </img>")
                                tmp.push(tmpResult["heading"])
                                tmp.push(tmpResult["type"])
                                var editBtn = "<button type='button' class='btn btn-success' style='padding: 10px' onclick =editContent('"+tmpResult["content_id"]+"') >Edit</button>"
                                tmp.push(editBtn)
                                var deleteBtn = "<button type='button' class='btn btn-danger' style='padding: 10px' onclick =deleteContent('"+tmpResult["content_id"]+"') >Delete</button>"
                                tmp.push(deleteBtn)
                                var moveBtn = "<button type='button' class='btn btn-warning' style='padding: 10px' onclick =moveTop('"+tmpResult["content_id"]+"') >Move</button>"
                                tmp.push(moveBtn)
                                
                               
                                dataSetContent.push(tmp)
                            }
                            if (dataSetContent.length > 0) {
                                contentTable.clear().draw();
                                contentTable.rows.add(dataSetContent);
                                contentTable.columns.adjust().draw();
                            }
                        }
                    }
                    else {
                        contentTable.clear().draw();
                        contentTable.rows.add(dataSetContent);
                        contentTable.columns.adjust().draw();
                    }
                }else {
                }
            },
            error: function() { 
            },
        });
    }

    function getAdmins(){
        dataSetAdmin = []
        var ajaxurl = baseURL+"/get_all_admin.php",
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
                        if (result["content"].length > 0){
                            console.log("hello")
                            for (var i =0; i< result["content"].length; i++){
                                tmpResult = result["content"][i]
                                var tmp =  []
                                tmp.push(tmpResult["email"])
                                var deleteBtn = "<button type='button' class='btn btn-danger' style='padding: 10px' onclick =deleteAdmin('"+tmpResult["email"]+"') >Delete</button>"
                                tmp.push(deleteBtn)
                                dataSetAdmin.push(tmp)

                            }
                            if (dataSetAdmin.length > 0) {
                                AdminTable.clear().draw();
                                AdminTable.rows.add(dataSetAdmin);
                                AdminTable.columns.adjust().draw();
                            }
                        }
                    }
                    else {
                        contentTable.clear().draw();
                        contentTable.rows.add(dataSetContent);
                        contentTable.columns.adjust().draw();
                    }
                }else {
                }
            },
            error: function() { 
            },
        });
    }

    function getDashboardDetails(){
        dataSetContent = []
        var ajaxurl = baseURL+"/get_dashboard.php"
        $.ajax({

            url: ajaxurl,
            type: 'GET',
            success: function(response) { 
                console.log(response);
                var result = jQuery.parseJSON(response)
                console.log(result)
                if (result["message"] == "success"){
                    var result = jQuery.parseJSON(response)
                    if (result["message"] == "success"){
                        $("#today").text(result["today_visit"])
                        $("#today_subscribe").text(result["today_subscribe"])
                        $("#subscribe").text(result["subscribe"])
                        $("#unsubscribe").text(result["unsubscribe"])
                    }
                    else {
                        contentTable.clear().draw();
                        contentTable.rows.add(dataSetContent);
                        contentTable.columns.adjust().draw();
                    }
                }else {
                }
            },
            error: function() { 
            },
        });
    }


    function registerAdmin(){
        $("#email-group").removeClass()
        $("#email-group").addClass("form-group")

        var ajaxurl = baseURL+"/register_admin.php",
        data =  {'email': $("#email").val()};
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
                    $("#email-group").removeClass()
                    $("#email-group").addClass("form-group")
                    $("#email").val("")
                    alert("Register Admin Successfully");
                    getAdmins()
                }else {
                    console.log("Error")
                    $("#email-goup").removeClass()
                    $("#email-group").addClass("form-group has-error")
                }
            },
            error: function() { 
            },
        });
    }

    function deleteAdminEamil(email){
        var ajaxurl = baseURL+"/delete_admin.php",
        data = {"email": email};
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
                    alert("Admin Deleted Successfully");
                    getAdmins()
                }else {
                    console.log("Error")
                }
            },
            error: function() { 
            },
        });
    }

    function moveToTop(id){
        var ajaxurl = baseURL+"/update_date_content.php",
        data = {"content_id": id};
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
                    alert("Content Moved Successfully");
                    getContents()
                }else {
                    console.log("Error")
                }
            },
            error: function() { 
            },
        });
    }

    function createContent(){
        var ajaxurl = baseURL+"/create_content.php",
        data = {"english": $("#english").val(), "sinhala":$("#sinhala").val(), "heading":$("#heading").val(), "type":$("#type").val()};
        $.ajax({
    
            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function(response) { 
                console.log(response);
                var result = jQuery.parseJSON(response)
                console.log(result)
                if (result["message"] == "success"){
                    createdContentId = result["content_id"]
                    $("#heading").val("")
                    $("#type").val("")
                    $("#english").val("")
                    $("#sinhala").val("")
            
                    $("#heading-group").removeClass()
                    $("#heading-group").addClass("form-group")
            
                    $("#type-group").removeClass()
                    $("#type-group").addClass("form-group")
            
                    $("#english-group").removeClass()
                    $("#english-group").addClass("form-group")
            
                    $("#sinhala-group").removeClass()
                    $("#sinhala-group").addClass("form-group")

                    imageUpload()
                }else {
                    console.log("Error")
                }
            },
            error: function() { 
            },
        });
    }

    function imageUpload(){
        if (createdContentId != ""){

            const formData = new FormData(this);
            formData.append("content_id", createdContentId)
            console.log(formData)
            var value = document.getElementById("image").value;

            if (value != "") {
                $("#image-group").removeClass()
                $("#image-group").addClass("form-group")

                var data = new FormData();
                data.append("content_id", createdContentId);
                data.append("fileToUpload", document.querySelector("#image").files[0]);
            
                console.log(document.querySelector("#image").files[0]);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        var response = this.responseText
                        alert("Content Added Successfully");
                        
                        getContents()

                    }
                };
                xmlhttp.open("POST", baseURL+"/content_image_upload.php");
                xmlhttp.send(data);
            }
            else {
                $("#image-group").css("color","red")
            }
        }
        else {
            console.log("id not avilable");
        }
    }
    function updateContent(){
        if (editId != ""){
            var ajaxurl = baseURL+"/update_content.php",
            data = {"content_id": editId,"english": $("#english").val(), "sinhala":$("#sinhala").val(), "heading":$("#heading").val(), "type":$("#type").val()};
            $.ajax({
        
                url: ajaxurl,
                data: data,
                type: 'POST',
                success: function(response) { 
                    console.log(response);
                    var result = jQuery.parseJSON(response)
                    console.log(result)
                    if (result["message"] == "success"){
                        $("#heading").val("")
                        $("#type").val("")
                        $("#english").val("")
                        $("#sinhala").val("")
                
                        $("#heading-group").removeClass()
                        $("#heading-group").addClass("form-group")
                
                        $("#type-group").removeClass()
                        $("#type-group").addClass("form-group")
                
                        $("#english-group").removeClass()
                        $("#english-group").addClass("form-group")
                
                        $("#sinhala-group").removeClass()
                        $("#sinhala-group").addClass("form-group")
    
                        editId = ""
                        create = true

                        alert("Content updated Successfully");
                        $("html, body").delay(500).animate({
                            scrollTop: $('#dataTables-content').offset().top 
                        }, 2000);
                        getContents()
                    }else {
                        console.log("Error")
                    }
                },
                error: function() { 
                },
            });
        }
    }

    function getContentByID(id){
        var ajaxurl = baseURL+"/get_content_by_id.php",
            data = {"content_id": id};
            $.ajax({
        
                url: ajaxurl,
                data: data,
                type: 'POST',
                success: function(response) { 
                    console.log(response);
                    var result = jQuery.parseJSON(response)
                    console.log(result)
                    if (result["message"] == "success"){
                        create = false
                        $("#english").val(result["content"]["english"])
                        $("#sinhala").val(result["content"]["sinhala"])
                        $("#type").val(result["content"]["type"])
                        editId = id
                        $("#heading").val(result["content"]["heading"])
                    }else {
                        console.log("Error")
                    }
                },
                error: function() { 
                },
            });
    }

    function deleteContentById(id){
        var ajaxurl = baseURL+"/delete_content.php",
            data = {"content_id": id};
            $.ajax({
        
                url: ajaxurl,
                data: data,
                type: 'POST',
                success: function(response) { 
                    console.log(response);
                    var result = jQuery.parseJSON(response)
                    console.log(result)
                    if (result["message"] == "success"){
                        alert("Content Deleted Successfully");
                        getContents()
                    }else {
                        console.log("Error")
                    }
                },
                error: function() { 
                },
            });
    }

    function getSearchResult(){
        console.log($('#startDate').val())
        var ajaxurl = baseURL+"/get_filtered_dashboard.php",
            data = {"start": $('#startDate').val(),"end":$('#endDate').val()};
            $.ajax({
        
                url: ajaxurl,
                data: data,
                type: 'POST',
                success: function(response) { 
                    console.log(response);
                    var result = jQuery.parseJSON(response)
                    console.log(result)
                    if (result["message"] == "success"){
                        $("#subscribe").text(result["subscribe"])
                        $("#unsubscribe").text(result["unsubscribe"])
                    }else {
                        console.log("Error")
                    }
                },
                error: function() { 
                },
            });
    }



    

    $("#email").keyup(function () {
        $("#email-group").removeClass()
            $("#email-group").addClass("form-group")
    })

    $("#createAdmin").click(function () {
        if (isEmail($("#email").val())) {
            $("#email-group").removeClass()
            $("#email-group").addClass("form-group")
            registerAdmin()
        }
        else {
            $("#email-goup").removeClass()
            $("#email-group").addClass("form-group has-error")
        }
    })


    $("#reset").click(function () {
        $("#email").val("") 
        create = false
        editId = ""
        $("#email-group").removeClass()
        $("#email-group").addClass("form-group")
    })

    $("#cancel").click(function () {
        var now = new Date();
 
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
    
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    
    
       $('#startDate').val(today);
       $('#endDate').val(today);
        getDashboardDetails()
    })

    $("#getResult").click(function () {
        getSearchResult()
        
    })

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    $("#createContent").click(function () {  
        console.log("hello")
        $("#heading-group").removeClass()
        $("#heading-group").addClass("form-group")

        $("#type-group").removeClass()
        $("#type-group").addClass("form-group")

        $("#english-group").removeClass()
        $("#english-group").addClass("form-group")

        $("#sinhala-group").removeClass()
        $("#sinhala-group").addClass("form-group")

        $("#image-group").css("color","black")

        var value = document.getElementById("image").value;

        if (create){
            console.log("create");
            if ($("#heading").val() != "" && $("#type").val() != "" && $("#english").val() != "" && $("#sinhala").val() != "" && value != ""){
                createContent()
            }
            else {
                if ($("#heading").val() == ""){
                    $("#heading-goup").removeClass()
                    $("#heading-group").addClass("form-group has-error")
                }
                if($("#type").val() == "" ){
                    $("#type-goup").removeClass()
                    $("#type-group").addClass("form-group has-error")
                }
                if($("#english").val() == ""){
                    $("#english-goup").removeClass()
                    $("#english-group").addClass("form-group has-error")
                }
                if ($("#sinhala").val() == ""){
                    $("#sinhala-goup").removeClass()
                    $("#sinhala-group").addClass("form-group has-error")
                }
                if (value == "") {
                    $("#image-group").css("color","red")
                }
            }
                
        }
        else {
            console.log("edit")
            if ($("#heading").val() != "" && $("#type").val() != "" && $("#english").val() != "" && $("#sinhala").val() != ""){
                updateContent()
            }
            else {
                if ($("#heading").val() == ""){
                    $("#heading-goup").removeClass()
                    $("#heading-group").addClass("form-group has-error")
                }
                if($("#type").val() == "" ){
                    $("#type-goup").removeClass()
                    $("#type-group").addClass("form-group has-error")
                }
                if($("#english").val() == ""){
                    $("#english-goup").removeClass()
                    $("#english-group").addClass("form-group has-error")
                }
                if ($("#sinhala").val() == ""){
                    $("#sinhala-goup").removeClass()
                    $("#sinhala-group").addClass("form-group has-error")
                }
            }
        }
    })


    $("#resetContent").click(function () { 
        $("#heading").val("")
        $("#type").val("")
        $("#english").val("")
        $("#sinhala").val("")

        $("#heading-group").removeClass()
        $("#heading-group").addClass("form-group")

        $("#type-group").removeClass()
        $("#type-group").addClass("form-group")

        $("#english-group").removeClass()
        $("#english-group").addClass("form-group")

        $("#sinhala-group").removeClass()
        $("#sinhala-group").addClass("form-group")
     })

     $("#heading").keyup(function () {
        $("#heading-group").removeClass()
        $("#heading-group").addClass("form-group")
    })

    $("#type").keyup(function () {
        $("#type-group").removeClass()
        $("#type-group").addClass("form-group")
    })

    $("#english").keyup(function () {
        $("#english-group").removeClass()
        $("#english-group").addClass("form-group")
    })

    $("#sinhala").keyup(function () {
        $("#sinhala-group").removeClass()
        $("#sinhala-group").addClass("form-group")
    })


function deleteAdmin(email){
    deleteAdminEamil(email)
}

function deleteContent(id){
    console.log(id)
    deleteContentById(id)
}

function moveTop(id){
    console.log(id)
    moveToTop(id)
}

function editContent(content_id){
    console.log(content_id)
    $("html, body").delay(500).animate({
        scrollTop: $('#artical').offset().top 
    }, 2000);
    
    getContentByID(content_id)

    
}

