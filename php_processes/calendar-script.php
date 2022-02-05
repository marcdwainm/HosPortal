<script>
    tooltipDetails = {};

    window.mobilecheck = function() {
        var check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    };

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
                let toDate = new Date()

                

                $.ajax({
                    type: 'POST',
                    url: 'php_processes/check-disabled-date.php',
                    data: {
                        dateDragged: dateDragged
                    },
                    success: function(result) {
                        if (result == 'disabled') {
                            eventDropInfo.revert();
                        }
                        else if (new Date(start).getTime() <= toDate.getTime()) {
                            Swal.fire(
                                'Invalid!',
                                'Appointments can\'t be rescheduled on past date or time.',
                                'error'
                            )
                            eventDropInfo.revert();
                        }
                        else if (result == 'enabled') {
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