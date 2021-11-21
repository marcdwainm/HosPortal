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

        $.ajax({
            type: "POST",
            url: "php_processes/book-appointment-doctor.php",
            data: {
                'appointment-date-time': dt,
                'patient-name': patientName,
                'patient-contact': patientContact,
                'app-type': appType,
                'desc': desc,
                'selected': selected
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

        if (query != '') {
            $.ajax({
                url: 'php_processes/search.php',
                method: 'POST',
                data: {
                    query: query
                },
                success: function (data) {
                    if (data == '') {
                        $('#plist-search').hide()
                        $('#plist-search').html('')
                    } else {
                        $('#plist-search').show()
                        $('#plist-search').html(data)
                    }

                }
            })
        }
        else if (query == '') {
            $('#plist-search').hide()
            $('#plist-search').html('')
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
            }
        })
    })

    function checkInputs() {
        let empty1 = false;
        let empty2 = false;
        $('.book-content-doctor input').each(function () {
            if ($(this).val() === '') {
                empty1 = true;
            }
        })

        let value = $('#appointment-type').val();
        if (value === null) {
            empty2 = true;
        }

        if (!empty1 && !empty2) {
            $('#book-doctor').prop('disabled', false)
        }
        else {
            $('#book-doctor').prop('disabled', true)
        }
        console.log(empty1, empty2)
    }
})