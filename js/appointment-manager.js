$(document).ready(function () {
    $('#reload-tbl').on('click', function () {
        $.ajax({
            type: "POST",
            url: "php_processes/table-live-update.php",
            success: function (result) {
                $("#doctor-appt-table").html(result);
            },
            error: function (result) {
                alert('error');
            }
        })
    })

    $('.sortation').on('input', function () {
        $('#sort-btn').prop('disabled', false);
    })

    $('#sort-btn').on('click', function () {
        selected = $('.sortation-select').find(':selected').val();
        selectedText = $('.sortation-select').find(':selected').text();

        $('.h2-sortation span').html(selectedText);
        $.ajax({
            type: "GET",
            url: "php_processes/employee-sorted-table.php",
            data: {
                'selected': selected
            },
            success: function (result) {
                $("#doctor-appt-table").html(result);
            },
            error: function (result) {
                alert('error');
            }
        })
    })

    $('#see-all-appt').on('click', function () {
        window.location = 'employee-all-appointments.php';
    })

    $("#doctor-appt-table").on('click', '.e-contents .e-num button', function () {
        $('.e-contents-table').children('#doctor-appt-table').children('.e-contents').find('.dropdown').hide()
        index = $(this).parent().parent().index()

        $('.e-contents-table').children('#doctor-appt-table').children('.e-contents').eq(index).find('.dropdown').slideToggle("fast")
    })

    $('#appt-table').on('click', '.table-content .e-num button', function () {
        $('.table').children('#appt-table').children('.table-content').find('.dropdown').hide()
        index = $(this).parent().parent().index()

        $('.table').children('#appt-table').children('.table-content').eq(index).find('.dropdown').slideToggle("fast")
    })


    $(document).on('click', '.cancel-appointment', function () {
        appointmentNum = $(this).val();

        Swal.fire({
            title: 'Cancel this Appointment?',
            text: "The patient will be notified about the cancellation",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/appointment-cancel.php',
                    data: { app_num: appointmentNum },
                    success: function (result) {
                        $("#doctor-appt-table").html(result)
                    }
                })

                Swal.fire(
                    'Deleted!',
                    'The appointment has been cancelled.',
                    'success'
                )
            }
        })
    })



    $(document).on('click', '.cancel-appointment-patient', function () {
        appointmentNum = $(this).val();

        Swal.fire({
            title: 'Cancel this Appointment?',
            text: "This appointment will be removed from the queue",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/appointment-cancel-patient.php',
                    data: { app_num: appointmentNum },
                    success: function (result) {
                        $("#appt-table").html(result)
                    }
                })

                Swal.fire(
                    'Deleted!',
                    'Your appointment has been cancelled.',
                    'success'
                )

                $('#book-appointment').prop('disabled', false);
            }
        })
    })

    $('#reload').on('click', function () {
        $.ajax({
            type: "POST",
            url: "php_processes/table-live-update-patient.php",
            success: function (result) {
                $("#appt-table").html(result);
            },
            error: function (result) {
                alert('error');
            }
        })
    })

})