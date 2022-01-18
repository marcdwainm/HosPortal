
$(document).ready(function () {

    $(document).mouseup(function (e) {
        var container = $(".dropdown");
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    })

    $(document).on('click', "#join-chatroom", function () {
        appnum = $(this).val();

        $.ajax({
            type: 'POST',
            url: 'php_processes/rejoin-meet.php',
            data: {
                appnum: appnum
            },
            success: function (result) {
                window.location.href = 'meeting-room-patient.php?meetlink=' + result;
            }
        })
    })

    $('#reload-tbl').on('click', function () {
        selected = $(this).val();
        patientKeyword = $('.sortation-text-byname').val();
        $.ajax({
            type: "GET",
            url: "php_processes/table-live-update.php",
            data: {
                'selected': selected,
                patientKeyword: patientKeyword
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
        selected = $('.sortation-select').val();
        selectedText = $('.sortation-select').find(':selected').text();
        patientKeyword = $('.sortation-text-byname').val();

        if (selected === 'byname' && patientKeyword == '') {
            $('#patient-error3').show();
            return;
        } else {
            $('#patient-error3').hide();
        }

        $('#reload-tbl').val(selected);

        $('.h2-sortation .title').html(selectedText);
        $.ajax({
            type: "GET",
            url: "php_processes/employee-sorted-table.php",
            data: {
                'selected': selected,
                'patientKeyword': patientKeyword
            },
            success: function (result) {
                $("#doctor-appt-table").html(result);
            },
            error: function (result) {
                alert('error');
            }
        })

        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Appointments Sorted',
            showConfirmButton: false,
            timer: 1000
        })

    })

    $('.sortation-select').on('change', function () {
        sortVal = $(this).val();

        if (sortVal === 'byname') {
            $('.sortation-text-byname').show();
        }
        else {
            $('.sortation-text-byname').hide();
            $('#patient-error3').hide();
            $('.sortation-text-byname').val('');
        }
    })

    $("#doctor-appt-table").on('click', '.e-contents .e-num button', function (e) {
        $('.e-contents-table').children('#doctor-appt-table').children('.e-contents').find('.dropdown').hide()
        index = $(this).parent().parent().index()

        $('.e-contents-table').children('#doctor-appt-table').children('.e-contents').eq(index).find('.dropdown').slideToggle("fast")
    })

    $('#appt-table-all').on('click', '.table-content .e-num button', function () {
        $('.table').children('#appt-table-all').children('.table-content').find('.dropdown').hide()
        index = $(this).parent().parent().index()

        $('.table').children('#appt-table-all').children('.table-content').eq(index).find('.dropdown').slideToggle("fast")
    })

    $('#appt-table').on('click', '.table-content .e-num button', function () {
        $('.table').children('#appt-table').children('.table-content').find('.dropdown').hide()
        index = $(this).parent().parent().index()

        $('.table').children('#appt-table').children('.table-content').eq(index).find('.dropdown').slideToggle("fast")
    })


    $(document).on('click', '.cancel-appointment', function () {
        appointmentNum = $(this).val();
        selected = $("#reload-tbl").val();
        cancelType = 'cancel'

        Swal.fire({
            title: 'Cancel this Appointment?',
            text: "The patient will be notified about the cancellation",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: 'php_processes/appointment-cancel.php',
                    data: {
                        app_num: appointmentNum,
                        selected: selected,
                        cancel_type: cancelType
                    },
                    success: function (result) {
                        getToolTipDetails();
                        calendar.refetchEvents();
                        $("#doctor-appt-table").html(result)
                    }
                })

                $('#page-num').html('1');
                $('#offset').html('0');

                Swal.fire(
                    'Cancelled!',
                    'The appointment has been cancelled.',
                    'success'
                )
            }
        })
        $('#page-num').html('1');
        $('#offset').html('0');
    })


    $(document).on('click', '.missed', function () {
        appointmentNum = $(this).val();
        selected = $("#reload-tbl").val();
        cancelType = 'miss'

        Swal.fire({
            title: 'Mark as missed?',
            text: "Are you sure? you can't revert this process",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: 'php_processes/appointment-cancel.php',
                    data: {
                        app_num: appointmentNum,
                        selected: selected,
                        cancel_type: cancelType
                    },
                    success: function (result) {
                        getToolTipDetails();
                        calendar.refetchEvents();
                        $("#doctor-appt-table").html(result)
                    }
                })

                $('#page-num').html('1');
                $('#offset').html('0');

                Swal.fire(
                    'Marked!',
                    'The patient missed the appointment',
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
            title: 'Are you sure?',
            text: "Accepting means that you have appointed the patient and that the appointment was successful. This process cannot be reverted. ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Finish'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/appointment-done.php',
                    data: {
                        app_num: appointmentNum,
                        selected: selected
                    },
                    success: function (result) {
                        getToolTipDetails();
                        calendar.refetchEvents();
                        $("#doctor-appt-table").html(result)

                        Swal.fire(
                            'Appointed!',
                            'Appointment finished!',
                            'success'
                        )
                    }
                })
            }
        })
    })

    $(document).on('click', '.request-online', function () {
        appnum = $(this).val();
        selected = $("#reload-tbl").val();
        $('#issue-bill').val(appnum)

        $.ajax({
            type: 'POST',
            url: 'php_processes/get-patient-info.php',
            data: {
                appointmentNum: appnum
            },
            success: function (result) {
                $('.issue-details').html(result)
            }
        })
        $('.dim-4').fadeIn()
    })


    $(document).on('click', '.consult-online', function () {
        pid = $(this).val().substr($(this).val().length - 4);
        appnum = $(this).val();
        appstatus = $(this).parent().parent().find('span:nth-child(4)').html()

        if (appstatus == 'Ongoing') {
            // You have already set-up a chat room, click the button below to rejoin the meeting.
            // See if the patient is already in the room.
            $.ajax({
                type: 'POST',
                url: 'php_processes/rejoin-meet.php',
                data: {
                    appnum: appnum
                },
                success: function (result) {
                    window.location.href = 'meeting-room.php?meetlink=' + result + "&appnum=" + appnum;
                }
            })
        }
        else if (appstatus == 'Pending') {
            $.ajax({
                type: 'POST',
                url: 'php_processes/if-online.php',
                data: {
                    pid: pid
                },
                success: function (result) {
                    $('.dim-2, .generate-meeting-container').fadeIn()
                    $('.meeting-message').html(result)
                    $('#generate-meet-link').val(appnum)
                }
            })
        }

    })

    $(document).on('click', '.consult-online-calendar', function () {
        pid = $(this).val().substr($(this).val().length - 4);
        appnum = $(this).val();
        appstatus = $(this)[0].getAttribute("data-value");

        if (appstatus == 'Ongoing') {
            // You have already set-up a chat room, click the button below to rejoin the meeting.
            // See if the patient is already in the room.
            $.ajax({
                type: 'POST',
                url: 'php_processes/rejoin-meet.php',
                data: {
                    appnum: appnum
                },
                success: function (result) {
                    window.location.href = 'meeting-room.php?meetlink=' + result + "&appnum=" + appnum;
                }
            })
        }
        else if (appstatus == 'Pending') {
            $.ajax({
                type: 'POST',
                url: 'php_processes/if-online.php',
                data: {
                    pid: pid
                },
                success: function (result) {
                    $('.dim-2, .generate-meeting-container').fadeIn()
                    $('.meeting-message').html(result)
                    $('#generate-meet-link').val(appnum)
                }
            })
        }

    })

    $(document).on('click', '.disable-dates', function () {
        disabledDates = $(this).val();
        type = 'disable'
        $.ajax({
            type: 'POST',
            url: 'php_processes/disable-enable-dates.php',
            data: {
                disabled_dates: disabledDates,
                type: type
            },
            success: function (result) {
                getToolTipDetails();
                calendar.refetchEvents();
            }
        })
    })

    $(document).on('click', '.enable-dates', function () {
        disabledDates = $(this).val();
        type = 'enable'
        $.ajax({
            type: 'POST',
            url: 'php_processes/disable-enable-dates.php',
            data: {
                disabled_dates: disabledDates,
                type: type
            },
            success: function (result) {
                getToolTipDetails();
                calendar.refetchEvents();
            }
        })
    })

    $(document).on('click', '.view-triage', function () {
        appointmentNum = $(this).val()
        $.ajax({
            type: 'POST',
            url: 'php_processes/view-triage.php',
            data: {
                appointmentNum: appointmentNum
            },
            success: function (result) {
                $('.triage-details').html(result);
                $('.dim-3').fadeIn();
            }
        })
    })

    $(document).on('click', '.view-triage-patient', function () {
        appointmentNum = $(this).val()
        $.ajax({
            type: 'POST',
            url: 'php_processes/view-triage.php',
            data: {
                appointmentNum: appointmentNum
            },
            success: function (result) {
                $('.triage-details').html(result);
                $('.dim-5').fadeIn();
            }
        })
    })

    $(document).on('click', '.view-triage-patient', function () {
        appointmentNum = $(this).val()
        $.ajax({
            type: 'POST',
            url: 'php_processes/view-triage.php',
            data: {
                appointmentNum: appointmentNum
            },
            success: function (result) {
                $('.triage-details').html(result);
                $('.dim-5').fadeIn();
            }
        })
    })

    $(document).on('click', '#exit-triage', function () {
        $('.dim-5').fadeOut();
        $('.dim-3').fadeOut();
    })

    $('#generate-meet-link').on('click', function () {
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var stringLength = 30;
        appnum = $(this).val();

        function pickRandom() {
            return possible[Math.floor(Math.random() * possible.length)];
        }

        var randomString = Array.apply(null, Array(stringLength)).map(pickRandom).join('');

        window.location.href = 'meeting-room.php?meetlink=' + randomString + "&appnum=" + appnum;
        $('.dim-2, .book-container').fadeIn()

        //Mark appointment as ongoing
        appnum = $(this).val();
        selected = $('.sortation-select').val();

        if (selected == null) {
            selected = 'today';
        }

        $.ajax({
            type: 'POST',
            url: 'php_processes/mark-as-ongoing.php',
            data: {
                appnum: appnum,
                selected: selected,
                meetlink: randomString
            },
            success: function (result) {
                $('#doctor-appt-table').html(result);
                $('.dim-2, .generate-meeting-container').fadeOut()

            }
        })
    })

    $(document).on('click', '.cancel-appointment-patient', function () {
        appointmentNum = $(this).val();

        Swal.fire({
            title: 'Cancel this Appointment?',
            text: "This appointment will be removed from the doctor's queue.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Cancel it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/appointment-cancel-patient.php',
                    data: { app_num: appointmentNum },
                    success: function (result) {
                        $("#appt-table-all").html(result)
                    }
                })

                $.ajax({
                    type: "POST",
                    url: 'php_processes/table-live-update-patient-first.php',
                    success: function (result) {
                        $('#appt-table').html(result)
                        console.log(result)
                        if (result == "<span class = 'no-appointments'>You currently have no appointments</span>") {
                            $('#book-appointment').prop('disabled', false);
                        }
                    }
                })

                Swal.fire(
                    'Cancelled!',
                    'Your appointment has been cancelled.',
                    'success'
                )
            }
        })
    })

    $(document).on('click', '.accept-online', function () {
        $('.dim-3').fadeIn();
    })

    $('.cancel-accept').on('click', function () {
        $('.dim-3').fadeOut();
    })

    $(document).on('click', '.decline-online', function () {
        appnum = $(this).val();
        answer = 'decline'

        Swal.fire({
            title: 'Are you sure?',
            text: "By declining, the appointment will continue with the Face-to-Face setup.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'I\'m sure'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/accept-decline-online.php',
                    data: {
                        appnum: appnum,
                        answer: answer
                    },
                    success: function (result) {
                        $("#appt-table").html(result)
                        $('.dim-4').fadeOut();

                        //UPDATE NOTIFS
                        $.ajax({
                            type: 'POST',
                            url: 'extras/patient-notifications.php',
                            success: function (result) {
                                $('.notif-contents').html(result);
                            }
                        })

                        Swal.fire(
                            'Declined!',
                            'The appointment will be conducted face-to-face.',
                            'success'
                        )
                    }
                })

                //DELETE THE BILL
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/delete-bill.php',
                    data: {
                        billnum: appnum
                    }
                })

                //UPDATE THE FIRST TABLE
                $.ajax({
                    type: "POST",
                    url: 'php_processes/table-live-update-patient-first.php',
                    success: function (result) {
                        $('#appt-table').html(result)
                        if (result == "<span class = 'no-appointments'>You currently have no appointments</span>") {
                            $('#book-appointment').prop('disabled', false);
                        }
                    }
                })

                Swal.fire(
                    'Decline!',
                    'The appointment will be conducted face-to-face.',
                    'success'
                )
            }
        })
    })

    $(".cancel-decline").on('click', function () {
        $('.dim-4').fadeOut();
    })

    $('#reload').on('click', function () {
        $.ajax({
            type: "POST",
            url: "php_processes/table-live-update-patient.php",
            success: function (result) {
                $("#appt-table-all").html(result);
            }
        })

        $.ajax({
            type: "POST",
            url: 'php_processes/table-live-update-patient-first.php',
            success: function (result) {
                $('#appt-table').html(result)
            }
        })
    })


    $('#next').on('click', function () {
        sortation = $('.sortation-select').val();
        patientKeyword = $('.sortation-text-byname').val();

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
                offset: offset,
                patientKeyword: patientKeyword
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
        patientKeyword = $('.sortation-text-byname').val();
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
                    offset: offset,
                    patientKeyword: patientKeyword
                },
                success: function (result) {
                    $('#doctor-appt-table').html(result);
                }
            })
        }
    })

    $('#prev5').on('click', function () {
        offset = parseInt($('#offset-app').html());
        pageNum = parseInt($('#page-num-app').html());

        if (pageNum == '1') { //DISABLE IF PAGE NUM IS 1
            $(this).prop('disabled', true)
        } else {
            offset -= 5;
            $('#offset-app').html(offset)
            $('#page-num-app').html(pageNum - 1)

            $.ajax({
                type: 'POST',
                url: 'php_processes/appt-table-patient.php',
                data: {
                    offset: offset
                },
                success: function (result) {
                    $('#appt-table-all').html(result);
                }
            })
        }
    })

    $('#next5').on('click', function () {
        offset = parseInt($('#offset-app').html());
        pageNum = parseInt($('#page-num-app').html());

        if (pageNum >= 1) {
            $('#prev5').prop('disabled', false)
        }

        offset += 5;
        $('#offset-app').html(offset)
        $('#page-num-app').html(pageNum + 1)

        $.ajax({
            type: 'POST',
            url: 'php_processes/appt-table-patient.php',
            data: {
                offset: offset
            },
            success: function (result) {
                if (result === "<span class = 'no-appointments'>You currently have no appointments</span>") {
                    pageNum = parseInt($('#page-num-app').html());
                    offset -= 5;
                    $('#offset-app').html(offset)
                    $('#page-num-app').html(pageNum - 1)
                }
                else {
                    $('#appt-table-all').html(result);
                }
            }
        })
    })

    $('#prev6').on('click', function () {
        offset = parseInt($('#offset-trans').html());
        pageNum = parseInt($('#page-num-trans').html());

        if (pageNum == '1') { //DISABLE IF PAGE NUM IS 1
            $(this).prop('disabled', true)
        } else {
            offset -= 5;
            $('#offset-trans').html(offset)
            $('#page-num-trans').html(pageNum - 1)

            $.ajax({
                type: 'POST',
                url: 'php_processes/patient-transaction-table.php',
                data: {
                    offset: offset
                },
                success: function (result) {
                    $('.transaction-table').html(result);
                }
            })
        }
    })

    $('#next6').on('click', function () {
        offset = parseInt($('#offset-trans').html());
        pageNum = parseInt($('#page-num-trans').html());

        if (pageNum >= 1) {
            $('#prev6').prop('disabled', false)
        }

        offset += 5;
        $('#offset-trans').html(offset)
        $('#page-num-trans').html(pageNum + 1)

        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-transaction-table.php',
            data: {
                offset: offset
            },
            success: function (result) {
                if (result === "<span class = 'no-appointments'>You have no bills</span>") {
                    pageNum = parseInt($('#page-num-trans').html());
                    offset -= 5;
                    $('#offset-trans').html(offset)
                    $('#page-num-trans').html(pageNum - 1)
                }
                else {
                    $('.transaction-table').html(result);
                }
            }
        })
    })
})