$(document).ready(function () {
    var activePid;
    var saveTimeout;
    var openedSoapNote;
    var selectedPatient;
    var selectedPatientId;
    $('.icd-10-codes').hide();

    $(document).on('keypress', "#p-contact", function (e) {
        return isNumberKey(e)
    })

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if ((charCode < 48 || charCode > 57))
            return false;
    
        return true;
    }

    $('#search-patient').on('keyup', function () {
        val = $(this).val()
        $('#page-num').html('1');
        $('#offset').html('0');
        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-list-ajax.php',
            data: {
                keyword: val
            },
            success: function (result) {
                $('.patient-tbl').html(result);
                console.log(result)
            }
        })
    })

    $(document).on('click', '.patient-progress-btn', function () {
        pid = $(this).val();
        selectedPatient = $(this).parent().parent().find('.patient-fullname').html();
        selectedPatientId = pid;

        if (pid !== activePid) {
            $(this).parent().parent().parent().parent().find('.hidden-patient-progress-div').slideUp();
            $(this).parent().parent().parent().find('.hidden-patient-progress-div').slideDown();
            activePid = pid;
        }
        else if (pid == activePid) {
            $(this).parent().parent().parent().find('.hidden-patient-progress-div').slideUp();
            activePid = undefined;
        }
    })

    $(document).on('click', '#exit-soap', function () {
        $('.dim-soap').fadeOut();
    })

    $(document).on('click', '.view-soap', function () {
        soapId = $(this).val()

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-soap-note-file.php',
            data: {
                soap_id: soapId
            },
            success: function (result) {
                var arrOfSoap = result.split(" ### ");

                $('#soap-column-subjective').val(arrOfSoap[0])
                $('#soap-column-objective').val(arrOfSoap[1])
                $('#soap-column-assessment').val(arrOfSoap[2])
                $('#soap-column-plan').val(arrOfSoap[3])
            }
        })

        $('.dim-soap').fadeIn();
    })

    $(document).on('click', '.edit-soap', function () {
        soapId = $(this).val()
        openedSoapNote = soapId;
        $.ajax({
            type: 'POST',
            url: 'php_processes/open-soap-note-file.php',
            data: {
                soap_id: soapId
            },
            success: function (result) {
                var arrOfSoap = result.split(" ### ");

                $('#soap-column-subjective-edit').val(arrOfSoap[0])
                $('#soap-column-objective-edit').val(arrOfSoap[1])
                $('#soap-column-assessment-edit').val(arrOfSoap[2])
                $('#soap-column-plan-edit').val(arrOfSoap[3])
            }
        })

        $('.dim-soap-edit').fadeIn();
    })

    $(document).on('click', '#exit-soap-edit', function () {
        $('.icd-10-codes').hide()
        $('.dim-soap-edit').fadeOut();
        $('#saving').html('');
    })

    $(document).on('keyup', '#soap-column-subjective-edit, #soap-column-objective-edit, #soap-column-assessment-edit, #soap-column-plan-edit', function () {
        saveSoap()
    })

    function saveSoap() {
        subjectiveNote = $('#soap-column-subjective-edit').val();
        objectiveNote = $('#soap-column-objective-edit').val();
        assessmentNote = $('#soap-column-assessment-edit').val();
        planNote = $('#soap-column-plan-edit').val();
        appnum = "";

        clearTimeout(saveTimeout)
        $('#saving').html("Saving...")
        saveTimeout = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: 'php_processes/save-soap-note.php',
                data: {
                    appNum: appnum,
                    soapId: openedSoapNote,
                    subjectiveNote: subjectiveNote,
                    objectiveNote: objectiveNote,
                    assessmentNote: assessmentNote,
                    planNote: planNote,
                },
                success: function (result) {
                    $('#saving').html("Saved")
                }
            })
        }, 1500)
    }

    $(document).on('click', '.add-bullet-btn', function () {
        currentText = $(this).parent().parent().parent().find('textarea').val()

        if (currentText == "") {
            $(this).parent().parent().parent().find('textarea').val(currentText + "• ")
            $(this).parent().parent().parent().find('textarea').focus()
        }
        else {
            $(this).parent().parent().parent().find('textarea').val(currentText + "\n• ")
            $(this).parent().parent().parent().find('textarea').focus()
        }

        saveSoap()
    })

    $(document).on('click', '#insert-icd-10-edit', function () {
        $('.icd-10-codes').animate({
            height: 'toggle',
            opacity: 'toggle'
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
        currentText = $('#soap-column-assessment-edit').val()
        icdValue = $(this).val()
        $('.icd-10-codes').hide();

        $('#soap-column-assessment-edit').val(currentText + icdValue)
        $('#soap-column-assessment-edit').focus()

        saveSoap();
    })

    $(document).on('click', '.create-new-soap', function () {
        Swal.fire({
            title: 'Create New SOAP note?',
            text: "This will manually create a new SOAP Note for the selected patient: " + selectedPatient,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Create'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/manual-soap-create.php',
                    data: {
                        pid: selectedPatientId
                    },
                    success: function (result) {
                        //UPDATE PATIENT's SOAP TABLE
                        $.ajax({
                            type: 'POST',
                            url: 'php_processes/patient-soap-table.php',
                            data: {
                                pid: selectedPatientId
                            },
                            success: function (result) {
                                $('#patient' + selectedPatientId).html(result)
                            }
                        })


                        //GET SOAP ID OF THE CREATED SOAP
                        soapId = result;


                        //OPEN THE CREATED SOAP NOTE
                        openedSoapNote = soapId;
                        $.ajax({
                            type: 'POST',
                            url: 'php_processes/open-soap-note-file.php',
                            data: {
                                soap_id: soapId
                            },
                            success: function (result) {
                                var arrOfSoap = result.split(" ### ");

                                $('#soap-column-subjective-edit').val(arrOfSoap[0])
                                $('#soap-column-objective-edit').val(arrOfSoap[1])
                                $('#soap-column-assessment-edit').val(arrOfSoap[2])
                                $('#soap-column-plan-edit').val(arrOfSoap[3])
                            }
                        })

                        $('.dim-soap-edit').fadeIn();
                    }
                })
            }
        })
    })

    var addPatientInterval;

    $('.add-a-patient').on('click', function () {
        $('#add-patient').prop('disabled', true);
        $('.dim-add-a-patient').fadeIn();

        addPatientInterval = setInterval(function () {
            disabledFlag = true;

            if ($('#p-fname').val() !== "" && $('#p-mname').val() !== "" && $('#p-lname').val() !== "" && $('#p-gender').val() != null && $('#p-birthdate').val() != "") {
                disabledFlag = false;
            }

            if (!disabledFlag) {
                $('#add-patient').prop('disabled', false);
            }
            else {
                $('#add-patient').prop('disabled', true);
            }
        }, 100)
    })

    $(document).on('click', '#add-patient', function () {
        inputFname = $('#p-fname').val();
        inputMname = $('#p-mname').val();
        inputLname = $('#p-lname').val();
        inputContact = $('#p-contact').val() == "" ? "Unspecified" : $('#p-contact').val();
        inputGender = $('#p-gender').val();
        inputBirthdate = $('#p-birthdate').val();
        inputAddress = $('#p-address').val() == "" ? "Unspecified" : $('#p-address').val();

        $.ajax({
            type: 'POST',
            url: 'php_processes/add-patient.php',
            data: {
                inputFname: inputFname,
                inputMname: inputMname,
                inputLname: inputLname,
                inputContact: inputContact,
                inputGender: inputGender,
                inputBirthdate: inputBirthdate,
                inputAddress: inputAddress
            },
            success: function (result) {
                $('.dim-add-a-patient').fadeOut();
                $('.dim-add-a-patient input').val('');


                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Patient successfully added!',
                    showConfirmButton: false,
                    timer: 1000
                })
            }
        })
    })

    $(document).on('click', '.archive-soap', function () {
        soapid = $(this).val();

        Swal.fire({
            title: 'Archive this note?',
            text: "Archiving will move this note to the archives and will still be retrievable",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Archive'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/archive-soap.php',
                    data: {
                        soapid: soapid,
                        pid: selectedPatientId
                    },
                    success: function (result) {
                        idString = "#patient" + selectedPatientId
                        $(idString).html(result)

                        Swal.fire(
                            'Archived',
                            'The document has been archived!',
                            'success'
                        )
                    }
                })
            }
        })
    })


    $('.add-a-patient-exit').on('click', function () {
        clearInterval(addPatientInterval)
        $('.dim-add-a-patient').fadeOut();
    })

    $(document).on('click', '.exit-upload-other-doc', function () {
        $('.dim-doc-upload').fadeOut();
        $('#file-other-doc').val('')
        $('.upload-other-doc').prop('disabled', true);
        $('.patient-error2').hide();
    })

    $(document).on('click', '.upload-new-other-doc', function () {
        $('.dim-doc-upload').fadeIn();
    })

    var fileArr = [];

    $(document).on('change', '#file-other-doc', function (e) {

        if ($(this).val() == '') {
            $('.upload-other-doc').prop('disabled', true);
            return;
        }
        else if (this.files[0].size > 5242880) {
            $('.patient-error2').show();
            return;
        }
        else {
            $('.upload-other-doc').prop('disabled', false);
            fileArr = [];
            fileArr = getBase64(this.files[0])
        }
    })

    function getBase64(file) {
        var reader = new FileReader();
        var fileArr = [];
        reader.readAsDataURL(file);
        reader.onload = function () {
            fileArr.push(reader.result);
            fileArr.push(file.type);
        };
        reader.onerror = function (error) {
            console.log('Error: ', error);
        };

        return fileArr;
    }


    $(document).on('click', '.upload-other-doc', function () {
        //1. Get the value of the button
        var patientId = selectedPatientId;
        var fileBase64 = fileArr[0];
        var fileExt = fileArr[1];

        //2. Insert to database
        $.ajax({
            type: 'POST',
            url: 'php_processes/insert-other-doc.php',
            data: {
                patientId: patientId,
                fileBase64: fileBase64,
                fileExt: fileExt
            },
            success: function (result) {
                $('#patient-other-' + patientId).html(result);
                $('.exit-upload-other-doc').trigger('click');
                Swal.fire(
                    'Uploaded!',
                    'The file has been successfully uploaded!',
                    'success'
                )
            }
        })
    })

    $(document).on('click', '.view-other', function () {
        window.location.href = 'employee-prescription.php?docnum=' + $(this).val() + "&fromOthers=true";
    })

    $(document).on('click', '.archive-other', function () {
        docNum = $(this).val();
        pid = selectedPatientId;

        Swal.fire({
            title: 'Are you sure?',
            text: "This document will be put onto the archives and will still be recoverable",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Archive'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/archive-other-document.php',
                    data: {
                        docnum: docNum,
                        pid: pid
                    },
                    success: function (result) {
                        $('#patient-other-' + pid).html(result)
                    }
                })

                Swal.fire(
                    'Archived!',
                    'Your file has been archived',
                    'success'
                )
            }
        })
    })

    $(document).on('click', '#prev', function () {
        var page = $(this).parent().find('#page-num').html()
        var keyword = $('#search-patient').val();
        var thiss = $(this)

        //-5 offset
        var offset = parseInt($(this).parent().find('#offset').html()) - 10;

        //IF OFFSET IS LOWER THAN 0 DONT PAGINATE
        if (offset < 0) {
            offset += 10;
        }
        else {
            page -= 1;
        }
        $(this).parent().find('#offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-pagination.php',
            data: {
                offset: offset,
                keyword: keyword
            },
            success: function (result) {
                $('.patient-tbl').html(result);
                thiss.parent().find('#page-num').html(page)
            }
        })
    })



    $(document).on('click', '#next', function () {
        var page = $(this).parent().find('#page-num').html()
        var thiss = $(this)
        var keyword = $('#search-patient').val();

        //+10 OFFSET
        var offset = parseInt($(this).parent().find('#offset').html()) + 10;
        $(this).parent().find('#offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-pagination.php',
            data: {
                offset: offset,
                keyword: keyword
            },
            success: function (result) {
                //IF NO ITEMS IN NEXT PAGE, STAY IN LAST PAGE
                if (result == "<span pan class = 'no-appointments font-size-bigger'>No Patients Found</span>") {
                    offset -= 10
                    thiss.parent().find('#offset').html(offset)
                }
                else {
                    page = parseInt(page) + 1
                    $('.patient-tbl').html(result);
                    thiss.parent().find('#page-num').html(page)
                }
            }
        })
    })

    $(document).on('click', '#prev-bill', function () {
        var page = $(this).parent().find('#page-num').html()
        var thiss = $(this)

        //-5 offset
        var offset = parseInt($(this).parent().find('#offset').html()) - 10;

        //IF OFFSET IS LOWER THAN 0 DONT PAGINATE
        if (offset < 0) {
            offset += 10;
        }
        else {
            page -= 1;
        }
        $(this).parent().find('#offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/bill-pagination.php',
            data: {
                offset: offset
            },
            success: function (result) {
                $('.bills-tbl').html(result);
                thiss.parent().find('#page-num').html(page)
            }
        })
    })


    $(document).on('click', '#next-bill', function () {
        var page = $(this).parent().find('#page-num').html()
        var thiss = $(this)

        //+10 OFFSET
        var offset = parseInt($(this).parent().find('#offset').html()) + 10;
        $(this).parent().find('#offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/bill-pagination.php',
            data: {
                offset: offset
            },
            success: function (result) {
                //IF NO ITEMS IN NEXT PAGE, STAY IN LAST PAGE
                if (result == "<span class = 'no-appointments'>Bills Empty</span>") {
                    offset -= 10
                    thiss.parent().find('#offset').html(offset)
                }
                else {
                    page = parseInt(page) + 1
                    $('.bills-tbl').html(result);
                    thiss.parent().find('#page-num').html(page)
                }
            }
        })
    })


    $(document).on('click', '.view-bill-doctor', function () {
        billnum = $(this).val()

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-bill-details.php',
            data: {
                billnum: billnum,
                paystatus: 'paid'
            },
            success: function (result) {
                $('.bill-container').html(result)
            }
        })
        $('.dim-bill').fadeIn();
    })


    $(document).on('click', '.set-paid-doctor', function () {
        let billNum = $(this).val();

        Swal.fire({
            title: 'Set as paid?',
            text: "To set the bill as 'Paid', means that the patient will be no longer required to pay this bill. This action cannot be reverted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Set as Paid'
        }).then((result) => {
            if (result.isConfirmed) {
                //CHANGE THE STATUS IN DB
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/set-as-paid.php',
                    data:{
                        billnum: billNum
                    },
                    success: function(result){
                        $('#page-num').html('1')
                        $('#offset').html('0')
                        $('.bills-tbl').html(result)
                        Swal.fire({
                            title: 'Success!',
                            text: 'Bill set as Paid',
                            icon: 'success'
                        })
                    }
                })

                // UPDATE THE TABLE
            }
        })
    })

    $(document).on('click', '.exit-2', function () {
        $('.dim-bill').fadeOut()
    })
})