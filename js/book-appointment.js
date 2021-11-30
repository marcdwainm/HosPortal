$(document).ready(function () {
    var interval;
    $('#book-appointment, #see-all-appt').click(function () {
        $('.dim').fadeIn();
        $('.date-time-input').val('');
        $('.description').val('');
        $('#book').prop('disabled', true)
        interval = setInterval(checkInputs, 100);
    });

    $('.exit').click(function () {
        $('.dim').fadeOut();
        $('.date-time-input').val('');
        $('.description').val('');
        clearInterval(interval);
        $('.book-content-doctor input').each(function () {
            $(this).val('')
        })
        $("#appointment-type").val($("#appointment-type option:first").val());
        $('#portal-registered').prop('checked', false)
    })


    $('#book').on('click', function () {
        dt = $("#appointment-date-time").val();
        desc = $(".description").val();

        $.ajax({
            type: "POST",
            url: "php_processes/book-appointment.php",
            data: {
                'appointment-date-time': dt,
                'description': desc,
            },
            success: function (result) {
                $('#book-appointment').prop('disabled', true);
                $('.dim').fadeOut();
                $('.book-content-doctor input').each(function () {
                    $(this).val('')
                })
                $("#appointment-type").val($("#appointment-type option:first").val());
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
    })


    $('#book-doctor').on('click', function () {
        dt = $("#appointment-date-time").val();
        patientName = $('#pname-search').val();
        patientContact = $('#pcontact').val();
        appType = $('#appointment-type').val();
        desc = $('#desc').val();
        selected = $('#reload-tbl').val();
        pid = $(this).val();

        $.ajax({
            type: "POST",
            url: "php_processes/book-appointment-doctor.php",
            data: {
                'appointment-date-time': dt,
                'patient-name': patientName,
                'patient-contact': patientContact,
                'app-type': appType,
                'desc': desc,
                'selected': selected,
                'pid': pid
            },
            success: function (result) {
                clearInterval(interval);
                $('#book-appointment').prop('disabled', true);
                $('.dim').fadeOut();
                $('.book-content-doctor input').each(function () {
                    $(this).val('')
                })
                $("#appointment-type").val($("#appointment-type option:first").val());
                Swal.fire(
                    'Success!',
                    'The appointment is now on queue!',
                    'success'
                )
                $('#doctor-appt-table').html(result);
            }
        })

        $('#page-num').html('1');
        $('#offset').html('0');
    })


    $('.book-content').on('input', function () {
        $('#book').prop('disabled', false)
    })


    flatpickr("#appointment-date-time", {
        enableTime: true,
        defaultHour: 9,
        defaultMinute: 0,
        dateFormat: "Y-m-d H:i:s",
        altInput: true,
        altFormat: "F j, Y l (h:S K)",
        minDate: new Date().fp_incr(1),
        maxDate: new Date().fp_incr(40),
        minTime: "09:00",
        maxTime: "16:00",
        disable: [
            function (date) {
                return (date.getDay() === 0);
            }
        ]
    });


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
            }
        })
    })

    function checkInputs() {
        let empty = false;

        if ($('#appointment-date-time').val() === '') {
            empty = true;
        }
        if ($('pname-search').val() === '') {
            empty = true;
        }
        if ($('#portal-registered').is(':checked') && $('#book-doctor').val() === '0000') {
            empty = true;
        }
        let value = $('#appointment-type').val();
        if (value === null) {
            empty = true;
        }

        if (!empty) {
            $('#book-doctor').prop('disabled', false)
        }
        else {
            $('#book-doctor').prop('disabled', true)
        }
        console.log(empty)
    }
})