$(document).ready(function () {
    disabledDatesArr = [];

    var disabledDates = function () {
        var returnVal = null;
        $.ajax({
            type: 'POST',
            url: 'php_processes/get-disabled-dates.php',
            async: false,
            success: function (result) {
                returnVal = result
            }
        })
        return returnVal.split(", ")
    }

    disabledDatesArr = disabledDates()

    $('#appointment-time').prop('disabled', true);
    var interval;
    if ($('#appt-table .table-content').length >= 1) {
        $('#book-appointment').prop('disabled', true);
    }
    else {
        $('#book-appointment').prop('disabled', false);
    }

    $('#book-appointment').on('click', function(){
        $.ajax({
            type: 'POST',
            url: 'php_processes/check-appt-num.php',
            success: function(result){
                if(result == '1'){
                    Swal.fire(
                        'Exceeded!',
                        'You have exceeded booked 4 times today, which exceeds the limit of bookings a day. Please try again tomorrow.',
                        'error'
                      )
                }
                else{
                    disabledDatesArr = disabledDates()

                    //PROGRAMATICALLY CHECK THE CHECKBOX
                    // $(":checkbox[value=Asthma]").prop("checked","true");
                    
                    // FIRST, GET FROM DATABASE IF USER HAS TRIAGE DATA
                    // IF NO DATA, MAKE ONE
                    
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/get_patient_history.php',
                        success: function(result){
                            if(result != ""){
                                result = JSON.parse(result) 
                                let famHistoryCb = result.fam_history_cb
                                let famHistoryOthers = result.fam_history_others

                                let allergiesCb = result.allergies_cb
                                let allergiesOthers = result.allergies_others

                                let socHistoryCb = result.soc_history_cb
                                let socHistoryOthers = result.soc_history_others

                                let famHistoryArr = famHistoryCb.split(", ");
                                let allergiesArr = allergiesCb.split(", ");
                                let socHistoryArr = socHistoryCb.split(", ");

                                $("#others-fam-history").val(famHistoryOthers)
                                for(let value of famHistoryArr){
                                    if(value == 'None/Unknown'){

                                        $("#none-fam-history").trigger("click")
                                    }
                                    else {
                                        $(`:checkbox[value="${value}"]`).prop("checked", true);
                                    }
                                }

                                $("#others-allergies").val(allergiesOthers)
                                for(let value of allergiesArr){
                                    if(value == 'None/Unknown'){
                                        $("#none-allergies").trigger("click")
                                    } 
                                    else {
                                        $(`:checkbox[value="${value}"]`).prop("checked", true);
                                    }    
                                    
                                }

                                $("#others-social-history").val(socHistoryOthers)
                                for(let value of socHistoryArr){
                                    if (value == 'None/Unknown'){
                                        $("#none-social-history").trigger("click")   
                                    }
                                    else {
                                        $(`:checkbox[value="${value}"]`).prop("checked", true);   
                                    }
                                }
                            }
                        }
                    })


                    $('.dim').fadeIn();
                    $('.date-time-input').val('');
                    $('#book').prop('disabled', true)
                    interval = setInterval(checkInputs, 100);
                }
            }
        })
    })

    $('#see-all-appt').click(function () {
        disabledDatesArr = disabledDates()
        $('.dim').fadeIn();
        $('.date-time-input').val('');
        $('#book').prop('disabled', true)
        interval = setInterval(checkInputs, 100);
    });

    $('.exit').click(function () {
        $('.dim').fadeOut();
        $('.date-time-input').val('');
        $('.book-content input').each(function () {
            if($(this).type == 'text'){
                $(this).val('')
            }
            else{
                $(this).prop('checked', false);
                $(this).prop('disabled', false);
            }
        })
        clearInterval(interval);
        $('.book-content-doctor input[type=text], .book-content-doctor input[type=number]').each(function () {
            $(this).val('')
        })
        $('.book-content-doctor input[type=checkbox]').each(function () {
            $(this).prop('checked', false);
            $(this).prop('disabled', false);
        })
        $('#book-doctor').val('0000')
        // $("#appointment-type").val($("#appointment-type option:first").val());
        $('#portal-registered').prop('checked', false)
        $('.time-autocomplete').parent().hide();
    })

    $('#exit-meet').click(function () {
        $('.dim-2').fadeOut();
    })

    $(document).on('keypress', '#height', function (evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }

        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }

        if ($('#height').val().length == 1) {
            keyword = $('#height').val();
            $('#height').val(keyword + "'")
        }

        if ($('#height').val().length == 3) {
            keyword = $('#height').val()[2];

            if (parseInt(keyword) > 1 || parseInt(keyword) == 0) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    })

    $(document).on('keypress', '#weight, #temperature', function (evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }

        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })

    $(document).on('keypress', '#blood-pressure', function (evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }

        keyword = $('#blood-pressure').val();
        var regex = /[0-9]|\//;

        if (keyword.includes('/')) {
            if (key == '/') {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }

        if (keyword.length == 0 || keyword.length == 1) {
            if (key == '/') {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }

        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }


        if ($('#blood-pressure').val().includes('/')) {
            if (key == '/') {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
        else if ($('#blood-pressure').val().length == 3) {
            if (key == '/') {

            }
            else {
                $('#blood-pressure').val(keyword + "/")
            }
        }
    })


    $('#book').on('click', function () {
        dt = $("#appointment-date-time").val();
        time = $("#appointment-time").val();
        ndt = dt + " " + time;
        chiefComplaint = $('#chief-complaint').val() || "Not Specified";
        height = $('#height').val() || "Not Specified";
        weight = ($('#weight').val() == '') ? "Not Specified" : $('#weight').val() + $("#weight-metric").val();
        bloodPressure = $('#blood-pressure').val() || "Not Specified";
        temperature = ($('#temperature').val() == '') ? "Not Specified" : $('#temperature').val() + "°C";
        pastSurgery = $('#past-surgery').val() || "Not Specified";
        familyHistory = [];
        allergies = [];
        socialHistory = [];
        currentMedications = $("#curr-med").val() || "Not Specified";
        travelHistory = $('#travel-history').val() || "Not Specified";

        if ($('#none-fam-history').is(":checked")) {
            familyHistory.push("None/Unknown");
        } else {
            $.each($('input[name="family-history"]:checked'), function () {
                familyHistory.push($(this).val());
            })
        }

        if ($('#none-allergies').is(":checked")) {
            allergies.push("None/Unknown");
        } else {
            $.each($('input[name="allergies"]:checked'), function () {
                allergies.push($(this).val());
            })
        }

        if ($('#none-social-history').is(":checked")) {
            socialHistory.push("None/Unknown");
        } else {
            $.each($('input[name="socialhistory"]:checked'), function () {
                socialHistory.push($(this).val());
            })
        }

        famHistorySave = [...familyHistory]
        allergiesSave = [...allergies]
        socHistorySave = [...socialHistory]

        famHistoryOthers = $('#others-fam-history').val().charAt(0).toUpperCase() + $('#others-fam-history').val().slice(1);
        allergiesOthers = $('#others-allergies').val().charAt(0).toUpperCase() + $('#others-allergies').val().slice(1);
        socialHistoryOthers = $('#others-social-history').val().charAt(0).toUpperCase() + $('#others-social-history').val().slice(1);

        if (famHistoryOthers !== '')
            familyHistory.push(famHistoryOthers);

        if (allergiesOthers !== '')
            allergies.push(allergiesOthers);

        if (socialHistoryOthers !== '')
            socialHistory.push(socialHistoryOthers);

        familyHistory = familyHistory.join(", ");
        allergies = allergies.join(", ");
        socialHistory = socialHistory.join(", ");

        $.ajax({
            type: "POST",
            url: "php_processes/book-appointment.php",
            data: {
                'appointment-date-time': ndt,
                'chief-complaint': chiefComplaint,
                'height': height,
                'weight': weight,
                'blood-pressure': bloodPressure,
                'temperature': temperature,
                'past-surgery': pastSurgery,
                'family-history': familyHistory,
                'allergies': allergies,
                'social-history': socialHistory,
                'current-medications': currentMedications,
                'travel-history': travelHistory
            },
            success: function (result) {
                $('#book-appointment').prop('disabled', true);
                $('.dim').fadeOut();
                $('.book-content-doctor input').each(function () {
                    $(this).val('')
                })
                $('#family-history-checkboxes div input').each(function () {
                    $(this).prop('checked', false)
                })
                $('.row-4-1 > div div input').each(function () {
                    $(this).prop('checked', false)
                })
                $('.row-4-2 > div div input').each(function () {
                    $(this).prop('checked', false)
                })

                $('.book-content input[type=text]').each(function () {
                    $(this).val('')
                })

                $('.book-content input[type=checkbox]').each(function () {
                    $(this).prop('checked', false);
                    $(this).prop('disabled', false);
                })

                    
                // $("#appointment-type").val($("#appointment-type option:first").val());

                Swal.fire(
                    'Success!',
                    'Your appointment is now on queue!',
                    'success'
                )
                $("#appt-table").html(result);
            },
            error: function (result) {
                alert('error');
            }
        })
        

        //--------------------------HISTORY INPUTS-----------------------//
        $.ajax({
            type: 'POST',
            url: 'php_processes/save-medical-history.php',
            data:{
                familyHistoryCb: famHistorySave.join(", "),
                allergiesCb: allergiesSave.join(", "),
                socHistoryCb: socHistorySave.join(", "),
                familyHistoryOthers: famHistoryOthers,
                allergiesOthers: allergiesOthers,
                socialHistoryOthers: socialHistoryOthers
            },
            success: function(result){
                console.log(result)
            }
        })
    })


    $('#book-doctor').on('click', function () {
        //notes
        // apptype is always f2f
        dt = $("#appointment-date-time").val();
        time = $("#appointment-time").val();
        age = $("#patient-age").val();
        ndt = dt + " " + time;
        patientName = $('#pname-search').val();
        patientContact = $('#pcontact').val() || "Not Specified";
        chiefComplaint = $('#chief-complaint').val() || "Not Specified";
        height = $('#height').val() || "Not Specified";
        weight = ($('#weight').val() == '') ? "Not Specified" : $('#weight').val() + $("#weight-metric").val();
        bloodPressure = $('#blood-pressure').val() || "Not Specified";
        temperature = ($('#temperature').val() == '') ? "Not Specified" : $('#temperature').val() + "°C";
        pastSurgery = $('#past-surgery').val() || "Not Specified";
        familyHistory = [];
        allergies = [];
        socialHistory = [];
        currentMedications = $("#curr-med").val() || "Not Specified";
        travelHistory = $('#travel-history').val() || "Not Specified";
        appType = "f2f";
        selected = $('#reload-tbl').val();
        pid = $(this).val();

        if ($('#none-fam-history').is(":checked")) {
            familyHistory.push("None/Unknown");
        } else {
            $.each($('input[name="family-history"]:checked'), function () {
                familyHistory.push($(this).val());
            })
        }

        if ($('#none-allergies').is(":checked")) {
            allergies.push("None/Unknown");
        } else {
            $.each($('input[name="allergies"]:checked'), function () {
                allergies.push($(this).val());
            })
        }

        if ($('#none-social-history').is(":checked")) {
            socialHistory.push("None/Unknown");
        } else {
            $.each($('input[name="socialhistory"]:checked'), function () {
                socialHistory.push($(this).val());
            })
        }

        famHistoryOthers = $('#others-fam-history').val().charAt(0).toUpperCase() + $('#others-fam-history').val().slice(1);
        allergiesOthers = $('#others-allergies').val().charAt(0).toUpperCase() + $('#others-allergies').val().slice(1);
        socialHistoryOthers = $('#others-social-history').val().charAt(0).toUpperCase() + $('#others-social-history').val().slice(1);

        if (famHistoryOthers !== '')
            familyHistory.push(famHistoryOthers);

        if (allergiesOthers !== '')
            allergies.push(allergiesOthers);

        if (socialHistoryOthers !== '')
            socialHistory.push(socialHistoryOthers);

        familyHistory = familyHistory.join(", ");
        allergies = allergies.join(", ");
        socialHistory = socialHistory.join(", ");

        $.ajax({
            type: "POST",
            url: "php_processes/book-appointment-doctor.php",
            data: {
                'appointment-date-time': ndt,
                'patient-name': patientName,
                'patient-age': age,
                'patient-contact': patientContact,
                'chief-complaint': chiefComplaint,
                'height': height,
                'weight': weight,
                'blood-pressure': bloodPressure,
                'temperature': temperature,
                'past-surgery': pastSurgery,
                'family-history': familyHistory,
                'allergies': allergies,
                'social-history': socialHistory,
                'current-medications': currentMedications,
                'travel-history': travelHistory,
                'app-type': appType,
                'selected': selected,
                'pid': pid
            },
            success: function (result) {
                clearInterval(interval);

                if (result == 'has appointment') {
                    Swal.fire(
                        'Failed!',
                        'The user has a currently booked appointment. To schedule another appointment, you may cancel the current appointment and schedule a new one.',
                        'error'
                    )
                }
                else {
                    calendar.refetchEvents()
                    $('#book-doctor').prop('disabled', true);
                    $('#book-appointment').prop('disabled', true);
                    $('.dim').fadeOut();
                
                    $('#family-history-checkboxes div input').each(function () {
                        $(this).prop('checked', false)
                    })

                    $('.row-4-1 > div div input').each(function () {
                        $(this).prop('checked', false)
                    })

                    $('.row-4-2 > div div input').each(function () {
                        $(this).prop('checked', false)
                    })

                    $('.book-content-doctor input[type=text], .book-content-doctor input[type=number]').each(function () {
                        $(this).val('')
                    })
    
                    $('.book-content-doctor input[type=checkbox]').each(function () {
                        $(this).prop('checked', false);
                        $(this).prop('disabled', false);
                    })

                    $("#book-doctor").val('0000')

                    Swal.fire(
                        'Success!',
                        'The appointment is now on queue!',
                        'success'
                    )
                    $('#doctor-appt-table').html(result);
                }
            }
        })

        $('#page-num').html('1');
        $('#offset').html('0');
    })

    var dateToday = new Date();
    $('#appointment-date-time').datepicker({
        dateFormat: 'yy-mm-dd',
        altFormat: 'yy-mm-dd',
        minDate: 1,
        beforeShowDay: function (date) {
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [disabledDatesArr.indexOf(string) == -1]
        }
    })

    $('#appointment-date-time').on('change', function () {
        $('#appointment-time').val("")
        $('#appointment-time').prop('disabled', false);
        $('.chosen-time').prop('disabled', false)

        var chosenDate = $(this).val()
        // PUT DATES FROM DB TO ARRAY THEN DISABLE BUTTONS WHICH ARE
        // OCCUPIED
        // AND OVERLAPS WITH THE TIME OF BUTTON + 30 MINS
        $.ajax({
            type: 'POST',
            url: 'php_processes/disable-times.php',
            async: true,
            data: {
                chosen_date: chosenDate
            },
            success: function (result) {
                var res = $.parseJSON(result);

                const overlapping = (a, b) => {
                    const getMinutes = s => {
                        const p = s.split(':').map(Number);
                        return p[0] * 60 + p[1];
                    };
                    return getMinutes(a.end) >= getMinutes(b.start) && getMinutes(b.end) >= getMinutes(a.start);
                };

                const isOverlapping = (arr) => {
                    let i, j;
                    for (i = 0; i < arr.length - 1; i++) {
                        for (j = i + 1; j < arr.length; j++) {
                            if (overlapping(arr[i], arr[j])) {
                                return true
                            }
                        };
                    };
                    return false;
                };

                var timeButtons = $('.time-autocomplete button')

                for (let i = 0; i < timeButtons.length; i++) {
                    var buttonStart = timeButtons[i].id
                    buttonStart = buttonStart.slice(-8).replaceAll("-", ":");
                    var minsToAdd = 30;
                    var time = buttonStart;
                    var buttonEnd = new Date(new Date("1970/01/01 " + time).getTime() + minsToAdd * 60000).toLocaleTimeString('en-UK', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });

                    buttonObject = {
                        'start': buttonStart,
                        'end': buttonEnd
                    }

                    res.push(buttonObject)

                    buttonId = "#i" + buttonStart.replaceAll(":", "-");

                    if (isOverlapping(res)) {
                        $(buttonId).prop('disabled', true)
                    }
                    res.pop()
                }
            }
        })
    })

    $('.chosen-time').on('click', function () {
        timeInsert = $(this).attr('id').slice(-8).replaceAll("-", ":");
        $('#appointment-time').val(timeInsert)
        $('.time-autocomplete').parent().hide();
    })

    $('#appointment-time').on('click', function () {
        $('.time-autocomplete').parent().show();
    })

    $("#patient-age").on("keypress", function(e){
        var charCode = (e.which) ? e.which : event.keyCode;
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
          return false;
    })


    $('#pname-search').on('keyup', function () {
        query = $(this).val()

        if ($('#portal-registered').is(':checked')) {
            if (query === '') {
                $('#plist-search').hide();
                $('#patient-error').hide();
            } else {
                $('#plist-search').show();
                $.ajax({
                    url: 'php_processes/search.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function (data) {
                        $('#plist-search').html(data)

                        if (data == '') {
                            $('#patient-error').show()
                            $('#plist-search').hide()
                            $('#plist-search').html('')
                        } else {
                            $('#plist-search').show()
                            $('#patient-error').hide()
                        }

                    }
                })
            }
        } else {

        }
    })


    $('#portal-registered').on('click', function () {
        query = $('#pname-search').val();

        $(".book-content-doctor input[type=text]").each(function(){
            $(this).prop('disabled', false)
        }) //STOPPED HERE CLEAR INPUTS OF TRIAGE
        $(".book-content-doctor input[type=checkbox]").each(function(){
            if($(this).attr('id') == 'portal-registered'){
                return;
            }
            else{
                $(this).prop('disabled', false)
                $(this).prop('checked', false)
            }
        })

        if ($(this).is(':checked')) {
            if (query === '') {
                $('#plist-search').hide();
                $('#patient-error').hide();
            } else {
                $('#plist-search').show();
                $.ajax({
                    url: 'php_processes/search.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function (data) {
                        $('#plist-search').html(data)

                        if (data == '') {
                            $('#patient-error').show()
                            $('#plist-search').hide()
                            $('#plist-search').html('')
                        } else {
                            $('#plist-search').show()
                            $('#patient-error').hide()
                        }

                    }
                })
            }
        } else {
            $('#plist-search').hide()
            $('#book-doctor').val('0000')
            $('#patient-error').hide();
            // $('#appointment-type').val('')
            // $('#appointment-type').hide();
        }
    })


    $(document).on('click', '.search-results', function () {
        userid = $(this).val();

        $('#plist-search').hide()
        $('#plist-search').html('')

        $.ajax({
            url: 'php_processes/field-filler.php',
            method: 'POST',
            data: {
                userid: userid
            },
            dataType: 'json',
            success: function (data) {
                console.log(data.fullname);
                $('#pname-search').val(data.fullname);
                $('#pcontact').val(data.contact);
                $('#book-doctor').val(data.pid)
                $('#patient-age').val(data.age)
                // CHECK FIELDS OF PATIENT

                $.ajax({
                    type: 'POST',
                    url: 'php_processes/get_patient_history.php',
                    data:{
                        pid: data.pid
                    },
                    success: function(result){
                        if(result != ""){
                            result = JSON.parse(result) 
                            let famHistoryCb = result.fam_history_cb
                            let famHistoryOthers = result.fam_history_others

                            let allergiesCb = result.allergies_cb
                            let allergiesOthers = result.allergies_others

                            let socHistoryCb = result.soc_history_cb
                            let socHistoryOthers = result.soc_history_others

                            let famHistoryArr = famHistoryCb.split(", ");
                            let allergiesArr = allergiesCb.split(", ");
                            let socHistoryArr = socHistoryCb.split(", ");

                            $(".book-content-doctor input[type=checkbox]").each(function(){
                                if($(this).attr('id') == 'portal-registered'){
                                    return;
                                }
                                else{
                                    $(this).prop("checked", false);
                                }
                            })

                            $("#others-fam-history").val(famHistoryOthers)
                            for(let value of famHistoryArr){
                                if(value == 'None/Unknown'){
                                    $("#none-fam-history").trigger("click")
                                }
                                else {
                                    $(`:checkbox[value="${value}"]`).prop("checked", true);
                                }
                            }

                            $("#others-allergies").val(allergiesOthers)
                            for(let value of allergiesArr){
                                if(value == 'None/Unknown'){
                                    $("#none-allergies").trigger("click")
                                }
                                else {
                                    $(`:checkbox[value="${value}"]`).prop("checked", true);   
                                }
                            }

                            $("#others-social-history").val(socHistoryOthers)
                            for(let value of socHistoryArr){
                                if (value == 'None/Unknown'){
                                    $("#none-social-history").trigger("click")
                                } 
                                else {
                                    $(`:checkbox[value="${value}"]`).prop("checked", true);   
                                }
                            }
                        }
                    }
                })
            }
        })
    })

    $(document).on('click', '#none-fam-history', function () {
        if ($(this).is(':checked')) {
            $('#others-fam-history').val('');
            $('#others-fam-history').prop('disabled', true);
            $('[name="family-history"]').prop('checked', false);
            $('[name="family-history"]').prop('disabled', true);
            $(this).prop('checked', true);
            $(this).prop('disabled', false);
        }
        else {
            $('#others-fam-history').prop('disabled', false);
            $('[name="family-history"]').prop('disabled', false);
        }
    })

    $(document).on('click', '#none-allergies', function () {
        if ($(this).is(':checked')) {
            $('#others-allergies').val('');
            $('#others-allergies').prop('disabled', true);
            $('[name="allergies"]').prop('checked', false);
            $('[name="allergies"]').prop('disabled', true);
            $(this).prop('checked', true);
            $(this).prop('disabled', false);
        }
        else {
            $('#others-allergies').prop('disabled', false);
            $('[name="allergies"]').prop('disabled', false);
        }
    })

    $(document).on('click', '#none-social-history', function () {
        if ($(this).is(':checked')) {
            $('#others-social-history').val('');
            $('#others-social-history').prop('disabled', true);
            $('[name="socialhistory"]').prop('checked', false);
            $('[name="socialhistory"]').prop('disabled', true);
            $(this).prop('checked', true);
            $(this).prop('disabled', false);
        }
        else {
            $('#others-social-history').prop('disabled', false);
            $('[name="socialhistory"]').prop('disabled', false);
        }
    })

    function checkInputs() {
        let empty = false;

        //DOCTOR BOOKING

        if ($('#pname-search').val() === '') {
            empty = true;
        }
        if ($('#portal-registered').is(':checked') && $('#book-doctor').val() === '0000') {
            empty = true;
        }
        if (!$('#portal-registered').is(':checked') && $('#appointment-date-time').val() !== '' && $('#pname-search').val() !== '') {
            empty = false;
        }

        //PATIENT BOOKING

        if ($('#appointment-time').val() == '' || $('.appointment-date-time').val() == '' || $('#chief-complaint').val() == '') {
            empty = true;
        }
        if (!$('#none-fam-history').is(':checked') && (!$('[name="family-history"]').is(':checked') && $('#others-fam-history').val() == '')) {
            empty = true;
        }
        if (!$('#none-allergies').is(':checked') && (!$('[name="allergies"]').is(':checked') && $('#others-allergies').val() == '')) {
            empty = true;
        }
        if (!$('#none-social-history').is(':checked') && (!$('[name="socialhistory"]').is(':checked') && $('#others-social-history').val() == '')) {
            empty = true;
        }


        if (!empty) {
            $('#book-doctor').prop('disabled', false)
            $('#book').prop('disabled', false)
        }
        else {
            $('#book-doctor').prop('disabled', true)
            $('#book').prop('disabled', true)
        }
    }

    $('#pcontact').on('keypress', function (evt) {

        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
})