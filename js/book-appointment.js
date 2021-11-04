$(document).ready(function () {
    $('#book-appointment').click(function () {
        $('.dim').fadeIn();
    });
    $('.exit').click(function () {
        $('.dim').fadeOut();
    })

    flatpickr("#appointment-date-time", {
        enableTime: true,
        defaultHour: 9,
        defaultMinute: 0,
        dateFormat: "Y-m-d H:i",
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
})