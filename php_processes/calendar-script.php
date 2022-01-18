<script>
    tooltipDetails = {};

    function getToolTipDetails() {
        $.ajax({
            type: "POST",
            url: "php_processes/loadEvents.php",
            success: function(result) {
                resultParsed = JSON.parse(result)
                for (let i = 0; i < resultParsed.length; i++) {
                    object = resultParsed[i].extendedProps
                    tooltipDetails[object.id] = object
                }
            }
        })
    }

    $(document).ready(function() {
        //NOTE
        //TODO
        //PAG SINELECT NG USER YUNG DISABLED DATES DAPTAT MAY LALABAS NA ENABLE DATE BUTTON
        //DAPAT BAWAL MAKAPAGAPPOINT YUNG PATIENT SA OCCUPIED HOURS NI DOC


        //Tooltip Details object (to be shown when mouse hovered over event)

        getToolTipDetails();


        calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            headerToolbar: {
                start: 'prev,next today',
                center: 'title',
                end: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            aspectRatio: 1.5,
            events: 'php_processes/loadEvents.php',
            selectable: true,
            slotDuration: '00:05:00',
            scrollTime: '07:00:00',
            eventOverlap: function(stillEvent, movingEvent) {
                if (movingEvent.extendedProps.status == ('pending' || 'ongoing' || 'onlinereq') && stillEvent.extendedProps.status == ('cancelled' || 'missed')) {
                    return true;
                } else {
                    return false;
                }
            },
            // IF USER SELECTS A DATE
            select: function(selectionInfo) {
                id = "";
                let x = selectionInfo.jsEvent.pageX + "px";
                let y = selectionInfo.jsEvent.pageY + "px";
                let dropdown = $('.appointment-calendar-dropdown');

                $('.appointment-calendar-dropdown').hide();
                if (!dropdown.is(":visible")) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/calendar-available-btns.php',
                        data: {
                            appointment_num: id,
                            startDate: selectionInfo.startStr,
                            endDate: selectionInfo.endStr
                        },
                        success: function(result) {
                            dropdown.html(result)
                            dropdown.css({
                                'top': y,
                                'left': x
                            }).slideDown("fast");
                        }
                    })
                }
            },
            eventClick: function(eventClickInfo) {
                let start = dateFormatter(eventClickInfo.event.start);
                let end = dateFormatter(eventClickInfo.event.end);
                let title = eventClickInfo.event.title;
                let id = eventClickInfo.event.id;

                let x = eventClickInfo.jsEvent.pageX + "px";
                let y = eventClickInfo.jsEvent.pageY + "px";
                let dropdown = $('.appointment-calendar-dropdown');

                $('.appointment-calendar-dropdown').hide();
                if (!dropdown.is(":visible")) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/calendar-available-btns.php',
                        data: {
                            appointment_num: id
                        },
                        success: function(result) {
                            if ($('.event-tooltip').is(":visible")) {
                                $('.event-tooltip').hide();
                                dropdown.html(result)
                                dropdown.css({
                                    'top': y,
                                    'left': x
                                }).slideDown("fast");

                            }
                        }
                    })
                }
            },
            eventResize: function(eventResizeInfo) {
                $('.event-tooltip').hide();
                //TO DO, ABILITY TO RESIZE 5 MINUTE INTERVALS
                let start = dateFormatter(eventResizeInfo.event.start);
                let end = dateFormatter(eventResizeInfo.event.end);
                let title = eventResizeInfo.event.title;
                let id = eventResizeInfo.event.id;

                $.ajax({
                    url: "php_processes/calendar_php/update.php",
                    type: "POST",
                    data: {
                        title: title,
                        start: start,
                        end: end,
                        id: id
                    },
                    success: function() {
                        calendar.refetchEvents();
                    }
                })
            },
            eventDrop: function(eventDropInfo) {
                $('.event-tooltip').hide();

                let start = dateFormatter(eventDropInfo.event.start);
                let end = dateFormatter(eventDropInfo.event.end);
                let title = eventDropInfo.event.title;
                let id = eventDropInfo.event.id;
                let dateDragged = start.substring(0, 10);

                $.ajax({
                    type: 'POST',
                    url: 'php_processes/check-disabled-date.php',
                    data: {
                        dateDragged: dateDragged
                    },
                    success: function(result) {
                        if (result == 'disabled') {
                            eventDropInfo.revert();
                        } else if (result == 'enabled') {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "The patient will be notified with this rescheduling (if patient is portal-registered), but it is recommended to inform the patient through SMS/Call to discuss more about the rescheduling.",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Im sure'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "php_processes/calendar_php/update.php",
                                        type: "POST",
                                        data: {
                                            title: title,
                                            start: start,
                                            end: end,
                                            id: id
                                        },
                                        success: function() {
                                            calendar.refetchEvents();
                                        }
                                    });

                                    Swal.fire(
                                        'Rescheduled!',
                                        'The appointment has been rescheduled',
                                        'success'
                                    )
                                } else {
                                    calendar.refetchEvents();
                                }
                            })
                        }
                    }
                })
            },
            eventMouseEnter: function(mouseEnterInfo) {
                getToolTipDetails();
                let id = mouseEnterInfo.event.id;
                let x = mouseEnterInfo.jsEvent.pageX + "px";
                let y = mouseEnterInfo.jsEvent.pageY + "px";

                let tooltip = $('.event-tooltip');

                $('.event-tooltip').hide();

                tooltipObj = tooltipDetails[id]
                if (tooltipObj.title == 'disabled') return
                timeStartFormatted = timeFormat(tooltipObj.timeStart, "ampm");
                timeEndFormatted = timeFormat(tooltipObj.timeEnd, "ampm");
                tooltipStatus = tooltipObj.status == 'onlinereq' ? "Online requested" : tooltipObj.status.charAt(0).toUpperCase() + tooltipObj.status.slice(1);

                detailsAsString = `
                    <b>Patient:</b> ${tooltipObj.patientName} <br/>
                    <b>Time:</b> ${timeStartFormatted} - ${timeEndFormatted} <br/>
                    <b>Contact:</b> ${tooltipObj.contact} <br/>
                    <b>Appointment Type:</b> ${tooltipObj.appType == 'f2f' ? "F2F" : "Online"} <br/>
                    <b>Status:</b> ${tooltipStatus} <br/>
                `;

                $('.event-tooltip').html(detailsAsString)

                if (!tooltip.is(":visible")) {
                    if ($('.appointment-calendar-dropdown').is(':visible')) {
                        tooltip.hide()
                    } else {
                        tooltip.css({
                            'top': y,
                            'left': x
                        }).fadeIn(200);
                    }
                }

            },
            eventMouseLeave: function(mouseLeaveInfo) {
                $('.event-tooltip').hide();
            }
        });
        calendar.render();
    });

    $(document).on('click', function(e) {
        if (e.target.className != "fc-event-time" && !$(e.target).hasClass("fc-event-title") && e.target.className != "fc-event-title-container") {
            $('.appointment-calendar-dropdown').hide();
        }
    })

    function dateFormatter(date) {
        var date = new Date(date);
        var isoDateTime = new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().slice(0, 19).replace('T', ' ');
        return isoDateTime;
    }

    function timeFormat(time, format) {
        var parts = time.split(':');
        var hour = parseInt(parts[0]);
        var suffix = '';
        if (format === 'ampm') {
            suffix = hour >= 12 ? ' PM' : ' AM';
            hour = (hour + 11) % 12 + 1;
        }
        return ('0' + hour).substr(-2) + ':' + parts[1] + suffix;
    }
</script>