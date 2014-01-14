$("#latError").hide();
$("#longError").hide();
$("#dateError").hide();
$("#timeError1").hide();
$("#timeError2").hide();
$("#emptyError").hide();
$("#fatalError").hide();

$("#saveLocationButtonId").click(function() {

    var url = "php/CreateCustomSettingsTable.php";

    $.ajax({
        type: "POST",
        url: url,
        data: $("#saveLocationFormId").serialize(),
        success: function(data)
        {
           var obj = JSON && JSON.parse(data) || $.parseJSON(data);
            if(obj.errorFlag == 'no_errors'){
                $('#setObserverSettings').css('display','none');
                $('#smallObserverSettings').html('(Custom Settings)');
                $('#detailedObserverSettings').html('<p>Location: '+obj.location +'</p><p>Time interval: ' + obj.datetime1+' <> ' + obj.datetime2+' </p><p><small>(*Note: Objects visible between this interval.) </small></p><p>Timezone: '+obj.timezone+'</p>');
                $('#observerLocation').modal('show');

                hideErrorHighlightedZones();

            }
            else{
                hideErrorHighlightedZones();

                if(obj.errorLat != 'no_errors'){
                    $('#form-group-lat').addClass('has-error');
                    $("#latError").html('<p>'+obj.errorLat +'</p>');
                    $("#latError").show();
                }
                if(obj.errorLong != 'no_errors'){
                    $('#form-group-long').addClass('has-error');
                    $("#longError").html('<p> '+obj.errorLong +'</p>');
                    $("#longError").show();
                }
                if(obj.errorDate != 'no_errors'){
                    $('#form-group-date').addClass('has-error');
                    $("#dateError").html('<p> '+obj.errorDate +'</p>');
                    $("#dateError").show();
                }
                if(obj.errorTime1 != 'no_errors'){
                    $('#form-group-time1').addClass('has-error');
                    $("#timeError1").html('<p>'+obj.errorTime1 +'</p>');
                    $("#timeError1").show();
                }
                if(obj.errorTime2 != 'no_errors'){
                    $('#form-group-time2').addClass('has-error');
                    $("#timeError2").html('<p>'+obj.errorTime2 +'</p>');
                    $("#timeError2").show();
                }
                if(obj.errorEmpty != 'no_errors'){
                    $("#emptyError").html('<p>'+obj.errorEmpty +'</p>');
                    $("#emptyError").show();
                }
                if(obj.errorFatal != 'no_errors'){
                    $("#fatalError").html('<p>'+obj.errorFatal +'</p>');
                    $("#fatalError").show();
                }
            }
        }
    });

    function hideErrorHighlightedZones(){
        //hide divs with detailed errors:
        $("#latError").hide();
        $("#longError").hide();
        $("#dateError").hide();
        $("#timeError1").hide();
        $("#timeError2").hide();
        $("#emptyError").hide();
        $("#fatalError").hide();

        //remove error highlights from form-groups as well:

        var clsName = $("#form-group-lat").attr('class');
        var number = clsName.indexOf("has-error");
        if(number !== -1){
            $('#form-group-lat').removeClass('has-error');
        }

        var clsName = $("#form-group-long").attr('class');
        var number = clsName.indexOf("has-error");
        if(number !== -1){
            $('#form-group-long').removeClass('has-error');
        }

        var clsName = $("#form-group-date").attr('class');
        var number = clsName.indexOf("has-error");
        if(number !== -1){
            $('#form-group-date').removeClass('has-error');
        }

        var clsName = $("#form-group-time1").attr('class');
        var number = clsName.indexOf("has-error");
        if(number !== -1){
            $('#form-group-time1').removeClass('has-error');
        }

        var clsName = $("#form-group-time2").attr('class');
        var number = clsName.indexOf("has-error");
        if(number !== -1){
            $('#form-group-time2').removeClass('has-error');
        }
    }

    return false; // avoid to execute the actual submit of the form.
});


$(function() {
    $( "#slider-range" ).slider({
        range: true,
        min: 0,
        max: 17,
        values: [ 5, 10 ],
        slide: function( event, ui ) {
            $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        }
    });
    $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
        " - " + $( "#slider-range" ).slider( "values", 1 ) );
});

$("#saveFilters").click(function() {
    //uncheck the radio button from predefined filters:
    $("input[name=predefinedFilters]:checked").prop('checked', false);

    var url = "php/RetrieveObjects.php";
    var saveData = {};
    saveData.selectedObjectTypesAndConst = $("#saveFiltersFormId").serialize();
    saveData.selectedObjectMagnitude = $( "#slider-range" ).slider( "values" );

    $.ajax({
        type: "POST",
        url: url,
        data: saveData,
        success: function(data)
        {
            $("#results-visibility").css('display','inline');
            var elem =  document.getElementById('displayResults');
            elem.innerHTML = "";
            elem.innerHTML = data; 
        }
    });

    return false; // avoid to execute the actual submit of the form.
});

function changePagination(pageId,liId){
    $(".flash").show();
    $(".flash").fadeIn(400).html('Loading <img src="dist/css/images/ajax-loading.gif" />');
    var result = $("input[name=predefinedFilters]:checked").val();
    if(result == undefined){
        result = 'not_predefined';
        var url = "php/RetrieveObjects.php";
    }else{
        var url = "php/RetrieveObjectsPredefined.php";
    }
    var saveData = {};
    saveData.selectedObjectTypesAndConst = $("#saveFiltersFormId").serialize();
    saveData.selectedObjectMagnitude = $( "#slider-range" ).slider( "values" );
    saveData.pageId = pageId;
    saveData.predefinedFilters = result;

    $.ajax({
        type: "POST",
        url: url,
        data: saveData,
        cache: false,
        success: function(result){
            $(".flash").hide();
            var elem =  document.getElementById('displayResults');
            elem.innerHTML = "";
            elem.innerHTML = result;
            $("."+pageId+"_no").addClass('active');
        }
    });
}

$("input[name='predefinedFilters']").change(function() {
    var url = "php/RetrieveObjectsPredefined.php";
    //var result = $("input[name=predefinedFilters]:checked").val();
    //alert(result);
    $.ajax({
        type: "POST",
        url: url,
        data: $("#savePredefinedFiltersFormId").serialize(),
        success: function(data)
        {   
           $("#results-visibility").css('display','inline'); 
           var elem =  document.getElementById('displayResults');
           elem.innerHTML = "";
           elem.innerHTML = data; 
           /*
           var rez = JSON.parse(data);
           var i = rez.length;
           for(var i = 0; i < rez.length; i++){ // parsing depth = 1
                alert(rez[i]['Name2']);
           }
           */
        }
    });

    return false; // avoid to execute the actual submit of the form.
});

$(function(){
        $('#displayResults').click(function(event){
          //if you did not clicked on the image, don't do anything:
          if (event.target.tagName != 'IMG') { return false;}
          event = event || window.event;
          var target = event.target || event.srcElement,
            link = target.src ? target.parentNode : target,
            options = {index: link, event: event},
            links = this.getElementsByTagName('a');
          blueimp.Gallery(links, options);
        });
});


$(function(){
    $('#displayGrid').click(function(event){
        event.preventDefault();
        $('#displayList').removeClass('active');
        $(this).addClass('active');
        $('#displayResults ul').removeClass('list-inline');
        $('#displayResults ul').addClass('list-block');
        $('.media').removeClass('col-lg-12');
        $('.media').addClass('col-lg-4');
        $('.media').css('text-align','center');
        $('.media img').css('margin','auto');
        $('.media-body').css('clear', 'both');
    });
})

/*
$("#changeSettings").click(function() {
  $("#setObserverSettings").css('display','inline');
});
*/

$( "#changeSettings" ).click(function() {
    $( "#setObserverSettings" ).toggle();
});

$(function() {
    $("#user_date").datepicker({
        autoSize: false,
        dateFormat: "yy-mm-dd",
        showAnim: "clip"
    });
});

$(function() {
    $( "[title]" ).tooltip({ position: { my: "center bottom-20", at: "center top" } });
});


