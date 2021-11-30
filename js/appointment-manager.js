$(document).ready(function () {
    $('#reload-tbl').on('click', function () {
        selected = $(this).val();

        $.ajax({
            type: "GET",
            url: "php_processes/table-live-update.php",
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

        $('#page-num').html('1');
        $('#offset').html('0');
    })

    $('.sortation').on('input', function () {
        $('#sort-btn').prop('disabled', false);
    })

    $('#sort-btn').on('click', function () {
        selected = $('.sortation-select').find(':selected').val();
        selectedText = $('.sortation-select').find(':selected').text();
        $('#reload-tbl').val(selected);

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

        Swal.fire({
            position: 'bottom-right',
            icon: 'success',
            title: 'Appointments Sorted',
            backdrop: 'none',
            showConfirmButton: false,
            timer: 1000
        })

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
        selected = $("#reload-tbl").val();

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
                    type: 'GET',
                    url: 'php_processes/appointment-cancel.php',
                    data: {
                        app_num: appointmentNum,
                        selected: selected
                    },
                    success: function (result) {
                        $("#doctor-appt-table").html(result)
                    }
                })

                $('#page-num').html('1');
                $('#offset').html('0');

                Swal.fire(
                    'Deleted!',
                    'The appointment has been cancelled.',
                    'success'
                )
            }
        })
        $('#page-num').html('1');
        $('#offset').html('0');
    })


    $(document).on('click', '#finish-appointment', function () {
        appointmentNum = $(this).val();
        selected = $('#reload-tbl').val();

        Swal.fire({
            icon: 'question',
            title: 'Finish Appointment?',
            text: "You may issue the bill now or later",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Online Bill Issue',
            denyButtonText: 'Don\'t Issue',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Bill Issued!', 'The patient has an outstanding balance', 'success')
            } else if (result.isDenied) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "The patient won't be issued with a bill",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //MARK AS DONE APPOINTMENT
                        $.ajax({
                            type: 'POST',
                            url: 'php_processes/appointment-done.php',
                            data: {
                                app_num: appointmentNum,
                                selected: selected
                            },
                            success: function (result) {
                                $("#doctor-appt-table").html(result)
                            }
                        })

                        $('#page-num').html('1');
                        $('#offset').html('0');

                        Swal.fire(
                            'Appointment Done!',
                            'Patient won\'t be issued',
                            'success'
                        )
                    }
                })
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


    $('#next').on('click', function () {
        sortation = $('.sortation-select').val();
        if (sortation === null) {
            sortation = 'today';
        }

        offset = parseInt($('#offset').html());
        pageNum = parseInt($('#page-num').html());

        if (pageNum >= 1) {
            $('#prev').prop('disabled', false)
        }

        offset += 5;
        $('#offset').html(offset)
        $('#page-num').html(pageNum + 1)

        $.ajax({
            type: 'GET',
            url: 'php_processes/employee-sorted-table.php',
            data: {
                selected: sortation,
                offset: offset
            },
            success: function (result) {
                if (result === "<span class = 'no-appointments'>No Appointments Found</span>") {
                    pageNum = parseInt($('#page-num').html());
                    offset -= 5;
                    $('#offset').html(offset)
                    $('#page-num').html(pageNum - 1)
                }
                else {
                    $('#doctor-appt-table').html(result);
                }
            }
        })
    })

    $('#prev').on('click', function () {
        sortation = $('.sortation-select').val();
        if (sortation === null) {
            sortation = 'today';
        }
        offset = parseInt($('#offset').html());
        pageNum = parseInt($('#page-num').html());

        if (pageNum == '1') { //DISABLE IF PAGE NUM IS 1
            $(this).prop('disabled', true)
        } else {
            offset -= 5;
            $('#offset').html(offset)
            $('#page-num').html(pageNum - 1)

            $.ajax({
                type: 'GET',
                url: 'php_processes/employee-sorted-table.php',
                data: {
                    selected: sortation,
                    offset: offset
                },
                success: function (result) {
                    $('#doctor-appt-table').html(result);
                }
            })
        }

    })
})