var container = document.querySelector('#meeting-window');
var api = null;

let searchParams = new URLSearchParams(window.location.search);
randomString = searchParams.get('meetlink');

var domain = "meet.jit.si";
var options = {
    "roomName": randomString,
    "parentNode": container,
    userInfo: {
        displayName: 'Doctor'
    }
};

api = new JitsiMeetExternalAPI(domain, options);
api.executeCommand('toggleRaiseHand')

$(document).ready(function () {
    $('.icd-10-codes').hide();
    let searchParams = new URLSearchParams(window.location.search);
    appnum = searchParams.get('appnum')
    openedSoapNote = appnum;

    $.ajax({
        type: 'POST',
        url: 'php_processes/view-triage.php',
        data: {
            appointmentNum: appnum
        },
        success: function (result) {
            $('.triage-details').html(result)
        }
    })

    $.ajax({
        type: 'POST',
        url: 'php_processes/open-soap-note-file.php',
        data: {
            appnum: openedSoapNote
        },
        success: function (result) {
            var arrOfSoap = result.split(" ### ");

            $('#subjective-textarea').val(arrOfSoap[0]);
            $('#objective-textarea').val(arrOfSoap[1]);
            $('#diagnosis-textarea').val(arrOfSoap[2]);
            $('#plan-textarea').val(arrOfSoap[3]);
            $('.open-soap-note').hide();
        }
    })

    $.ajax({
        type: 'POST',
        url: 'php_processes/get-created-soap.php',
        data: {
            appnum: appnum
        },
        async: false,
        success: function (result) {
            openedSoapNote = result;
        }
    })



    active = false;

    $(document).on('click', '#view-triage', function () {
        if (active == false) {
            $(".triage-dropdown").animate({
                top: '60px',
                opacity: "toggle",
            })
            active = true;
        }
        else {
            $(".triage-dropdown").animate({
                top: '-60px',
                opacity: "toggle",
            })
            active = false;
        }
    })

    $(document).on('click', '#conclude-appointment', function () {

        Swal.fire({
            title: 'Conclude appointment?',
            text: "By clicking yes, you agree that the appointment is finished. This will disconnect you from the meeting room. To re-consult the patient, another booking must be made.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                let searchParams = new URLSearchParams(window.location.search);
                appnum = searchParams.get('appnum');

                $.ajax({
                    type: 'POST',
                    url: 'php_processes/conclude-appointment.php',
                    data: {
                        appnum: appnum
                    },
                    success: function (result) {

                        let timerInterval
                        Swal.fire({
                            title: 'Finishing appointment',
                            html: 'Redirecting you back to homepage in <b></b> milliseconds.',
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                                const b = Swal.getHtmlContainer().querySelector('b')
                                timerInterval = setInterval(() => {
                                    b.textContent = Swal.getTimerLeft()
                                }, 100)
                                setTimeout(function () {
                                    self.close();
                                }, 2000);
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href = 'employee-homepage.php';
                            }
                        })

                    }
                })

            }
        })
    })

    $('.soap-button').on('click', function () {
        $(".soap-note-container").animate({
            height: "toggle",
            opacity: "toggle"
        }, 300)
    })

    $('#add-bullet-subjective, #add-bullet-objective, #add-bullet-assessment, #add-bullet-plan').on('click', function () {
        currentText = $(this).parent().parent().parent().find('textarea').val()
        if (currentText == "") {
            $(this).parent().parent().parent().find('textarea').val(currentText + "• ")
            $(this).parent().parent().parent().find('textarea').focus()
        }
        else {
            $(this).parent().parent().parent().find('textarea').val(currentText + "\n• ")
            $(this).parent().parent().parent().find('textarea').focus()
        }
    })

    $('.soap-subjective textarea, .soap-objective textarea, .soap-assessment textarea, .soap-plan textarea').on('click', function () {
        $(".icd-10-codes").hide();
    })

    $('#insert-icd-10').on('click', function () {
        $(".icd-10-codes").animate({
            height: "toggle",
            opacity: "toggle"
        }, 300)
    })

    $('#icd-10-code-doctor-keyword').on('keyup', function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $('#icd-10-search').trigger('click')
        }
    })

    $('#icd-10-search').on('click', function () {
        var keyword = $('#icd-10-code-doctor-keyword').val();
        var expression = new RegExp(keyword, "i")

        $('.icd-10-code-buttons').html('');

        if (keyword == '') {
            $('.icd-10-code-buttons').append("<span class='no-results'>No Results</span>");
        }
        else {
            $.getJSON('icd10_codes.json', function (data) {
                $.each(data, function (key, value) {
                    if (value.code.search(expression) != -1 || value.desc.search(expression) != -1) {
                        val = value.code + " - " + value.desc;
                        $('.icd-10-code-buttons').append(`<button class = 'icd-button' value = '${val}'><span>${value.code}</span><span>${value.desc}</span></button>`)
                    }
                })

                if ($('.icd-10-code-buttons').html() == "") {
                    $('.icd-10-code-buttons').append("<span class='no-results'>No Results</span>");
                }
            })
        }
    })

    $(document).on('click', '.icd-button', function () {
        currentText = $('#diagnosis-textarea').val()
        icdValue = $(this).val()
        $('.icd-10-codes').hide();

        if (currentText == "") {
            $('#diagnosis-textarea').val(currentText + icdValue)
            $('#diagnosis-textarea').focus()
        }
        else {
            $('#diagnosis-textarea').val(currentText + `\n${icdValue}`)
            $('#diagnosis-textarea').focus()
        }

        saveSoap();
    })

    var saveTimeout;

    $('#subjective-textarea, #objective-textarea, #diagnosis-textarea, #plan-textarea').on('keyup', function () {
        saveSoap();
    })

    $('.add-bullet').on('click', function () {
        saveSoap();
    })

    $('#open-soap-note-btn').on('click', function () {
        let searchParams = new URLSearchParams(window.location.search);
        currentAppt = searchParams.get('appnum');

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-soap-notes.php',
            data: {
                currentAppt: currentAppt
            },
            success: function (result) {
                $('.open-soap-note-files').html(result);
            }
        })

        $(".open-soap-note").animate({
            height: "toggle",
            opacity: "toggle"
        }, 300)
    })

    $(document).on('click', '.open-soap-note-file', function () {
        var soapid = $(this).val()
        openedSoapNote = soapid;

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-soap-note-file.php',
            data: {
                soap_id: openedSoapNote
            },
            success: function (result) {
                var arrOfSoap = result.split(" ### ");

                $('#subjective-textarea').val(arrOfSoap[0]);
                $('#objective-textarea').val(arrOfSoap[1]);
                $('#diagnosis-textarea').val(arrOfSoap[2]);
                $('#plan-textarea').val(arrOfSoap[3]);
                $('.open-soap-note').hide();
            }
        })
    })

    $('#prescribe').on('click', function () {
        pid = appnum.substr(appnum.length - 4);
        pname = $('#patient-fullname').html()
        redirect = window.open('extras/prescription-labresult.php?docType=prescription&pid=' + pid + '&pname=' + pname, '_blank', 'location=yes,height=800,width=1000,scrollbars=yes,status=yes')
    })

    function saveSoap() {
        appnum = openedSoapNote;

        subjectiveNote = $('#subjective-textarea').val();
        objectiveNote = $('#objective-textarea').val();
        assessmentNote = $('#diagnosis-textarea').val();
        planNote = $('#plan-textarea').val();

        clearTimeout(saveTimeout)
        $('#save').html("Saving...")
        saveTimeout = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: 'php_processes/save-soap-note.php',
                data: {
                    soapId: appnum,
                    subjectiveNote: subjectiveNote,
                    objectiveNote: objectiveNote,
                    assessmentNote: assessmentNote,
                    planNote: planNote,
                },
                success: function (result) {
                    $('#save').html("Saved")
                }
            })
        }, 1500)
    }
})
