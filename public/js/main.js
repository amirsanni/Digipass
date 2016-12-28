'use strict';
var ar = setAppRoot("digipass", "");
var global = {appRoot:ar};

$(document).ready(function(){
    
    $("#toggleWebCam").click(function(e){
        e.preventDefault();
        
        var actionToTake = $(this).hasClass("enablewebcam") ? "enable" : "disable";
        var newText = actionToTake === "enable" ? "Disable Webcam" : "Enable Webcam";
        
        if(actionToTake === "enable"){
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            //call webcamjs attach function
            Webcam.attach('#visitorImage');
        }
        
        else{
            Webcam.reset();
            
            //remove the style attached to div the image is been shown
            $("#visitorImage").removeAttr('style', '');
        }
        
        //change the text to "Disable webcam", add class "disablewebcam" and remove class "enablewebcam"
        $(this).toggleClass('enablewebcam', 'disablewebcam').toggleClass('btn-danger', 'btn-success').html(newText);
    });
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
   
    //TO GENERATE PASS
    $("#genPass").click(function(e){
        e.preventDefault();
        
        var name = $('#vName').val();
        var email = $('#vEmail').val();
        var phone = $('#vPhone').val();
        var from = $('#vFrom').val();
        var toSee = $('#vToSee').val();
        
        if(name && phone && from && toSee){
            takeSnapshotAndSendAllToServer(name, email, phone, from, toSee);
        }
        
        else{
            !name ? $("#vName").css('borderColor', 'red') : $("#vName").css('borderColor', '');
            !phone ? $("#vPhone").css('borderColor', 'red') : $("#vPhone").css('borderColor', '');
            !from ? $("#vFrom").css('borderColor', 'red') : $("#vFrom").css('borderColor', '');
            !toSee ? $("#vToSee").css('borderColor', 'red') : $("#vToSee").css('borderColor', '');
        }
    });
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
   
    //TO PRINT PASS
    $("#vPassPrint").click(function(){
        window.print();
        
        $("#visitorPassModal").modal("hide");
    });
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    //TO VIEW A PASS FROM THE LIST OF VISITORS' TABLE
    $("#vTableDiv").on('click', '.viewVPassClk', function(){
        //paste details on modal, then launch modal
        $("#vPassImg").attr('src', $(this).siblings(".vVPassImg").val());
        $("#vPassId").html($(this).parent('td').siblings('.vVPassId').html());
        $("#vPassName").html($(this).parents('td').siblings(".vVPassName").html());
        $("#vPassPhone").html($(this).parents('td').siblings(".vVPassPhone").html());
        $("#vPassEmail").html($(this).parents('td').siblings(".vVPassEmail").html() || "---");
        $("#vPassFrom").html($(this).parents('td').siblings(".vVPassFrom").html());
        $("#vPassSee").html($(this).parents('td').siblings(".vVPassToSee").html());
        $("#vPassTimeIn").html($(this).parents('td').siblings(".vVPassCheckIn").html());
        $("#vPassTimeOut").html($(this).parents('td').siblings(".vVPassCheckOut").html());

        //show the print button
        $("#vPassPrint").removeClass("hidden");
        $("#visitorPassModal").modal("show");
    });
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    //CANCEL THE GENERATION OF VISITOR PASS
    $("#cancelGenPass").click(function(){
        //reset the form
        document.getElementById("vPassForm").reset();
    });
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    //TO CHECK OUT A VISITOR
    $("#vCheckOut").click(function(e){
        e.preventDefault();
        
        var passId = $("#vVCheckOutId").val();
        
        if(passId){
            //make server req to change status
            $("#vVCheckOutFMsg").html("Checking out...!");
            
            $.ajax({
                url: global.appRoot+"visitors.php",
                method: "POST",
                data: {pi:passId, action:'checkout'},
                dataType: "JSON"
            }).done(function(rd){
                if(rd.status === 1){
                    //display success msg
                    $("#vVCheckOutFMsg").css('color', 'green').html("Checked out");
                    
                    //clear the pass id
                    $("#vVCheckOutId").val("");
                    
                    //remove the msg after a while
                    setTimeout(function(){$("#vVCheckOutFMsg").html("");}, 2000);
                    
                    //update the status and checkout time of visitor
                    updateStatusAndCheckOutTime(passId, rd.cot);
                }
                
                else{
                    $("#vVCheckOutFMsg").css('color', 'red').html("Failed! Visitor might have been checked out");
                }
            }).fail(function(){
                
            });
        }
    });
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    //WHEN PAGINATION BUTTONS ARE CLICKED
    $(".pgBtnPrev, .pgBtnNxt").click(function(e){
        e.preventDefault();
        
        //get current page's number
        var currPageNumber = parseInt($("#vListCurPage").val());
        
        //determine the page number to fetch based on the button clicked
        var pageToFetch = $(this).hasClass("pgBtnPrev") ? currPageNumber - 1 : currPageNumber + 1;
        
        //only make req if page to fetch is one or greater
        if(pageToFetch >= 1){
            loadVList(pageToFetch);
        }
    });
    
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
   
    //WHEN SORT BY AND SHOW PER PAGE ARE CHANGED
    $("#vSort, #vShow").change(function(e){
        e.preventDefault();
        
        loadVList(1);
    });
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
   
    $("#vSearch").keyup(function(e){
        e.preventDefault();
        
        var value = $(this).val();
        
        if(value){
            //get search result
            $("#vTableDiv").load(global.appRoot+"index.php #vListTable", {sv:value});
        }
        
        else{
            loadVList(1);
        }
    });
});


/*
********************************************************************************************************************************
********************************************************************************************************************************
********************************************************************************************************************************
********************************************************************************************************************************
********************************************************************************************************************************
*/


/**
 * 
 * @param {type} name
 * @param {type} email
 * @param {type} phone
 * @param {type} from
 * @param {type} toSee
 * @returns {undefined}
 */
function takeSnapshotAndSendAllToServer(name, email, phone, from, toSee) {
    
    var image = '';
    var print_image = '';
    
    Webcam.snap(function(data_uri) {
		image = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
        print_image = data_uri;
    });
    
    if(!image){
        $("#vPassFMsg").css('color', 'red').html("Image Required");
        return;
    }
    
    
    else{
        $("#vPassFMsg").css('color', '').html("");//remove error msg that might be there
        
        $.ajax({
            method: 'POST',
            url: global.appRoot+"visitors.php",
            data: {i: image, n:name, e:email, p:phone, f:from, t:toSee, action:'insert'},
            dataType: 'json'
        })
        .done(function(returnedData){
            if (returnedData.status === 1) {
                //paste details on modal, then launch modal
                $("#vPassImg").attr('src', print_image);
                $("#vPassId").html(returnedData.id);
                $("#vPassName").html(name);
                $("#vPassPhone").html(phone);
                $("#vPassEmail").html(email);
                $("#vPassFrom").html(from);
                $("#vPassSee").html(toSee);
                $("#vPassTimeIn").html(returnedData.cit);

                $("#vPassPrint").removeClass("hidden");//remove hidden class from button
                $("#visitorPassModal").modal("show");

                //reset the form
                document.getElementById("vPassForm").reset();
                
                
                //call function to prepend newly added visitor info to table
                prependVisitorInfo(name, returnedData.id, email, phone, from, toSee, returnedData.cit, print_image)
            }

            else{
                $("#vPassFMsg").css('color', 'red').html(returnedData.msg);
            }
        })
        .fail(function(){

        });
    }
}


/*
********************************************************************************************************************************
********************************************************************************************************************************
********************************************************************************************************************************
********************************************************************************************************************************
********************************************************************************************************************************
*/

/**
 * 
 * @returns {undefined}
 */
function resetVisitorsListSN(){
    $(".vVPassSn").each(function(i){
        $(this).html(i + 1);
    });
}



/**
 * 
 * @param {type} name
 * @param {type} passId
 * @param {type} email
 * @param {type} phone
 * @param {type} from
 * @param {type} toSee
 * @param {type} checkInTime
 * @param {type} print_image
 * @returns {undefined}
 */
function prependVisitorInfo(name, passId, email, phone, from, toSee, checkInTime, print_image){
    var newRow = '<tr id="lr-'+passId+'">\
        <td class="vVPassSn"></td>\
        <td class="vVPassName">'+name+'</td>\
        <td class="vVPassId">'+passId+'</td>\
        <td class="vVPassEmail">'+email+'</td>\
        <td class="vVPassPhone">'+phone+'</td>\
        <td class="vVPassFrom">'+from+'</td>\
        <td class="vVPassToSee">'+toSee+'</td>\
        <td class="vVPassCheckIn">'+checkInTime+'</td>\
        <td class="vVPassCheckOut">---</td>\
        <td class="vVPassStatus">IN</td>\
        <td>\
            <button class="btn btn-primary btn-xs viewVPassClk">View Pass</button>\
            <input type="hidden" class="vVPassImg" value="'+print_image+'">\
        </td>\
    </tr>';
    
    //prepend and reset SN
    $("#vVPassTBody").prepend(newRow);
    
    resetVisitorsListSN();
}




/**
 * 
 * @param {type} passId
 * @param {type} checkOutTime
 * @returns {String}
 */
function updateStatusAndCheckOutTime(passId, checkOutTime){
    //look for the row of the passId
    var vElem = $("#vVPassTBody").find("#lr-"+passId);
    
    if(vElem){
        //now update the status and the checkout time
        $(vElem).find(".vVPassCheckOut").html(checkOutTime);
        $(vElem).find(".vVPassStatus").html("OUT");
    }
    
    return "";
}



function loadVList(pageNum){
    var limit = $("#vShow").val();
    var orderBy = $("#vSort").val().split("-")[0];
    var orderFormat = $("#vSort").val().split("-")[1];
    var pageToLoad = pageNum || 1;
    
    var obj = {vListPage:pageToLoad, l:limit, vsb:orderBy, vsbo:orderFormat};
    
    //show loading
    $("#vTableDiv").html("Loading...");

    //load next page
    $("#vTableDiv").load(global.appRoot+"index.php #vListTable", obj, function(){
        //update current page
        $("#vListCurPage").val(pageToLoad);
    });
}



function setAppRoot(devFolderName, prodFolderName){
    var hostname = window.location.hostname;

    /*
     * set the appRoot
     * This will work for both http, https with or without www
     * @type String
     */
    
    //attach trailing slash to both foldernames
    var devFolder = devFolderName ? devFolderName+"/" : "";
    var prodFolder = prodFolderName ? prodFolderName+"/" : "";
    
    var baseURL = hostname === "localhost" ? window.location.origin+"/"+devFolder : window.location.origin+"/"+prodFolder;
    
    return baseURL;
}