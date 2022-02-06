$(document).ready(function () {
    var interval;
    var directUploadInterval;
    var intervalOngoing;

    // DYNAMIC UPDATE TABLE
    setInterval(function(){
        $.ajax({
            type: 'POST',
            url: 'php_processes/medtech-dynamic-table.php',
            success: function(result){
                $('.lab-tbl').html(result)
            }
        })
    }, 500)

    $('#create-draft').on('click', function () {
        $('.add-document-overlay').fadeIn();
        $('.medtech-draft-container').fadeIn();
        interval = setInterval(checkInputs, 100)
    })

    $('.exit-create-draft').on('click', function () {
        $('.add-document-overlay').fadeOut();
        $('.medtech-draft-container').fadeOut();
        $('#portal-registered').prop('checked', false);
        $('#patient-search').val('');
        $('#patient-search-2').val('');
        $('#test-type').val('');
        $('#collection-date').val('');
        $('#result-date').val('');
        clearInterval(interval)
    })

    $('.exit-add-ongoing').on('click', function () {
        $('.add-document-overlay').fadeOut();
        $('.medtech-draft-container-2').fadeOut();
        $('#portal-registered-3').prop('checked', false);
        $('#patient-search-3').val('');
        $('#test-type-3').val('');
        $('#collection-date-3').val('');
        $('#result-date-3').val('');
        clearInterval(intervalOngoing)
    })

    $('#add-ongoing').on('click', function(){
        $('.add-document-overlay').fadeIn();
        $('.medtech-draft-container-2').fadeIn();
        intervalOngoing = setInterval(checkInputs3, 100)
    })

    $('#upload-document').on('click', function () {
        $('.add-document-overlay').fadeIn();
        $('.direct-upload-medtech').fadeIn();
        directUploadInterval = setInterval(function () {
            var disabled = false;

            if ($('#patient-error-2').is(':visible')) {
                disabled = true;
            }
            if ($('#patient-search-2').val() == '' || $('#test-type-2').val() == '') {
                disabled = true                
            }
            if ($('#portal-registered-2').is(':checked') && $('#generate-document-2').val() == '0000') {
                disabled = true;
            }

            if (disabled) {
                $('#upload-from-device, #generate-document-2').prop('disabled', true);
            }
            else {
                $('#upload-from-device, #generate-document-2').prop('disabled', false);
            }
        }, 100)
    })

    $('.exit-direct-upload').on('click', function () {
        $('.add-document-overlay').fadeOut();
        $('.direct-upload-medtech').fadeOut();
        $('#portal-registered-2').prop('checked', false);
        $('#patient-search').val('');
        $('#patient-search-2').val('');
        $('#patient-error-2').hide();
        $('#test-type-2').val('');
        $('.autocomplete-2').hide();
        $('#upload-from-device, #file-to-database, #generate-document-2, #generate-document').val('0000')
        $('#file').hide();
        $('#file-to-database').hide();
        $('#upload-from-device').show();
        $('#file').val(null)
        $('.patient-error2').hide()
    })


    //PATIENT SEARCHING
    $('#patient-search').on('keyup', function () {
        keyword = $(this).val();

        if ($('#portal-registered').is(':checked')) {
            $.ajax({
                type: 'POST',
                url: 'php_processes/search.php',
                data: {
                    query: keyword
                },
                success: function (result) {
                    if (keyword == '') {
                        $('.p-name-documents-autocomplete').hide()
                        $('#patient-error').hide();
                    } else if (result == '') {
                        $('.p-name-documents-autocomplete').hide()
                        $('#patient-error').show();
                    } else {
                        $('.p-name-documents-autocomplete').show()
                        $('.p-name-documents-autocomplete').html(result);
                        $('#patient-error').show();
                    }
                }
            })
        }
        else {
            $('.p-name-documents-autocomplete').hide()
        }
    })


    //PATIENT SEARCHING
    $('#patient-search-2').on('keyup', function () {
        keyword = $(this).val();

        if ($('#portal-registered-2').is(':checked')) {
            $.ajax({
                type: 'POST',
                url: 'php_processes/search.php',
                data: {
                    query: keyword
                },
                success: function (result) {
                    if (keyword == '') {
                        $('.autocomplete-2').hide()
                        $('#patient-error-2').hide();
                    } else if (result == '') {
                        $('.autocomplete-2').hide()
                        $('#patient-error-2').show();
                    } else {
                        $('.autocomplete-2').show()
                        $('.autocomplete-2').html(result);
                        $('#patient-error-2').show();
                    }
                }
            })
        }
        else {
            $('.p-name-documents-autocomplete').hide()
        }
    })

    //PATIENT SEARCHING 3
    $('#patient-search-3').on('keyup', function () {
        keyword = $(this).val();
        $('#add-ongoing-test').val('0000')

        if ($('#portal-registered-3').is(':checked')) {
            $.ajax({
                type: 'POST',
                url: 'php_processes/search.php',
                data: {
                    query: keyword
                },
                success: function (result) {
                    if (keyword == '') {
                        $('.autocomplete-3').hide()
                        $('#patient-error-3').hide();
                    } else if (result == '') {
                        $('.autocomplete-3').hide()
                        $('#patient-error-3').show();
                    } else {
                        $('.autocomplete-3').show()
                        $('.autocomplete-3').html(result);
                        $('#patient-error-3').show();
                    }
                }
            })
        }
        else {
            $('.p-name-documents-autocomplete').hide()
        }
    })

    $('#portal-registered-3').on('click', function(){
        if($(this).is(':checked')){
            $('.autocomplete-3').show()
        }
    })

    //IF ONGOING TEST ADD
    $('#add-ongoing-test').on('click', function () {
        pname = $('#patient-search-3').val();
        testType = $('#test-type-3').val();
        collectionDate = $('#collection-date-3').val();
        resultDate = $('#result-date-3').val();
        pid = $(this).val();
        $('#issue-bill-medtech').val(pid)

        if (pid == '0000') {
            $.ajax({
                type: 'POST',
                url: 'php_processes/insert-ongoing-test.php',
                data: {
                    pname: pname,
                    testType: testType,
                    collectionDate: collectionDate,
                    resultDate: resultDate,
                    pid: pid
                },
                success: function (result) {
                    $('#generate-document').prop('disabled', true);
                    $('.add-document-overlay').fadeOut();
                    $('.medtech-draft-container-2').fadeOut();
                    $('#portal-registered, #portal-registered-3').prop('checked', false);
                    $('#patient-search').val('');
                    $('#patient-search-2, #patient-search-3').val('');
                    $('#test-type, #test-type-3').val('');
                    $('#collection-date, #collection-date-3').val('');
                    $('#result-date, #result-date-3').val('');
                    clearInterval(intervalOngoing)
                }
            })
        }
        //IF PATIENT IS REGISTERED, ASK FIRST IF BILL
        else {
            Swal.fire({
                title: 'Issue a bill?',
                icon: 'question',
                text: 'Before uploading, you must agree/disagree if the patient shall pay for the lab result',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Issue',
                denyButtonText: `Don't Issue`,
            }).then((result) => {
                if (result.isConfirmed) {
                    // IF CONFIRMED, DISPLAY BILL ISSUE WINDOW
                    appnum = pid;
                    $('#issue-bill-lab').val(appnum)

                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/get-patient-info.php',
                        data: {
                            appointmentNum: appnum,
                            fromLabRes: 'true'
                        },
                        success: function (result) {
                            $('.issue-details').html(result)
                        }
                    })
                    $('.dim-4').fadeIn()
                } else if (result.isDenied) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/insert-ongoing-test.php',
                        data: {
                            pname: pname,
                            testType: testType,
                            collectionDate: collectionDate,
                            resultDate: resultDate,
                            pid: pid
                        },
                        success: function (result) {
                            $('#generate-document').prop('disabled', true);
                            $('.add-document-overlay').fadeOut();
                            $('.medtech-draft-container-2').fadeOut();
                            $('#portal-registered, #portal-registered-3').prop('checked', false);
                            $('#patient-search').val('');
                            $('#patient-search-2, #patient-search-3').val('');
                            $('#test-type, #test-type-3').val('');
                            $('#collection-date, #collection-date-3').val('');
                            $('#result-date, #result-date-3').val('');
                            clearInterval(intervalOngoing)
                        }
                    })

                    Swal.fire(
                        'Success!',
                        'Ongoing Lab Test has been added!',
                        'success'
                    )
                }
            })
        }
    })

    globalFullname = ""
    //IF ONGOING TEST UPLOAD\
    $(document).on('click', '.upload-ongoing', function(){
        $('.add-document-overlay').fadeIn();
        $('.upload-ongoing-window').fadeIn();
        let id = $(this).val()
        $('#upload-ongoing-btn').val(id)
        globalFullname = $(this).parent().parent().find('span:first-child').html()
    })

    
    $(document).on('change', '#file-ongoing', function(){
        if(this.files[0].size > 5242880){
            this.value = "";
            $('.patient-error2').show();
            $('#upload-ongoing-btn').prop('disabled', true)
         }
         else{
             $('.patient-error2').hide();
             $('#upload-ongoing-btn').prop('disabled', false);
            }
    })

    $(document).on('click', '.exit-upload-ongoing', function(){
        $('.add-document-overlay').fadeOut();
        $('.upload-ongoing-window').fadeOut();
    })
    
    $(document).on('click', '#upload-ongoing-btn', function(){
        let docnum = $(this).val()
        let base64 = base64String;
        let extension = fileExt;
        let fullname = globalFullname
        
        $.ajax({
            type: 'POST',
            url: 'php_processes/update-ongoing.php',
            data:{
                doc_num: docnum,
                base64: base64,
                file_ext: extension,
                fullname: fullname
            },
            success: function(result){
                $('.add-document-overlay').fadeOut();
                $('.upload-ongoing-window').fadeOut();
                $('.lab-tbl-2').html(result);
                Swal.fire(
                    'Success!',
                    'The laboratory result has been uploaded! The patient will be notified.',
                    'success'
                  )
            }
        })
    })


    //IF AUTOCOMPLETE BUTTON CLICKED
    $(document).on('click', '.search-results-medtech', function () {
        pid = $(this).val();

        $('.p-name-documents-autocomplete').hide();
        $('#generate-document').val(pid);

        $.ajax({
            type: 'POST',
            url: 'php_processes/field-filler.php',
            data: {
                userid: pid
            },
            dataType: 'json',
            success: function (data) {
                $('#patient-search').val(data.fullname)
                $('#patient-error').hide();
            }
        })
    })

    //IF AUTOCOMPLETE BUTTON CLICKED 2
    $(document).on('click', '.search-results-medtech', function () {
        pid = $(this).val();

        $('.autocomplete-2').hide();
        $('.autocomplete-3').hide();
        $('#generate-document-2').val(pid);
        $('#add-ongoing-test').val(pid)

        $.ajax({
            type: 'POST',
            url: 'php_processes/field-filler.php',
            data: {
                userid: pid
            },
            dataType: 'json',
            success: function (data) {
                $('#patient-search-2').val(data.fullname)
                $('#patient-search-3').val(data.fullname)
                $('#upload-from-device').val(pid)
                $('#file-to-database').val(pid)
                $('#patient-error-2').hide();
                $('#patient-error-3').hide();
            }
        })
    })

    //CHECKING THE CHECKBOX
    $('#portal-registered').on('click', function () {
        if ($(this).is(':checked')) {
            $('.p-name-documents-autocomplete').show();
        }
        else {
            $('#generate-document').val('0000')
            $('.p-name-documents-autocomplete').hide()
            $('#patient-error').hide()
        }
    })

    //CHECKING THE CHECKBOX 2
    $('#portal-registered-2').on('click', function () {
        if ($(this).is(':checked')) {
            $('.autocomplete-2').show();
        }
        else {
            $('#generate-document-2').val('0000')
            $('#upload-from-device').val('0000')
            $('#file-to-database').val('0000')
            $('.autocomplete-2').hide()
            $('#patient-error-2').hide()
        }
    })

    $('#upload-from-device').on('click', function () {
        $('#file').show();
    })

    $('#file').on('change', function () {
        if(this.files[0].size > 5242880){
            this.value = "";
            $('.patient-error2').show()
            $('#upload-from-device').show();
            $('#file-to-database').hide();
         }
         else{
            $('.patient-error2').hide()
            $('#upload-from-device').hide();
            $('#file-to-database').show();
         }
    })

    $('#generate-document-2').on('click', function () {
        docType = 'labresult'
        pid = $(this).val();
        pname = $('#patient-search-2').val();
        testType = $('#test-type-2').val();
        var redirect = '';

        if (pid === '0000') {
            redirect = window.open('extras/prescription-labresult.php?docType=' + docType + '&pid=' + pid + '&pname=' + pname + "&testType=" + testType, '_blank', 'location=yes,height=800,width=1000,scrollbars=yes,status=yes')
        } else {
            redirect = window.open('extras/prescription-labresult.php?docType=' + docType + '&pid=' + pid + '&pname=' + pname + "&testType=" + testType, '_blank', 'location=yes,height=800,width=1000,scrollbars=yes,status=yes')
        }
        redirect.location;

        clearInterval(directUploadInterval);
        //STOPPED HERE
        // PUT FUNCIONALITIES ON TWO BUTTONS
    })

    base64String = "";
    fileExt = "";
    document.getElementById('file').addEventListener('change', handleFileSelect, false);
    document.getElementById('file-ongoing').addEventListener('change', handleFileSelect, false);

    function handleFileSelect(evt) {
        var f = evt.target.files[0]; // FileList object
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function (theFile) {
            return function (e) {
                var binaryData = e.target.result;
                //Converting Binary Data to base 64
                base64String = window.btoa(binaryData);
                //showing file converted to base64
                fileExt = f.type;
            };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsBinaryString(f);
    }

    $('#file-to-database').on('click', function () {
        let doctype = 'labresult'
        let base64 = base64String;
        let sentTo = $('#upload-from-device').val();
        let pname = $('#patient-search-2').val();

        if (sentTo === '0000') {
            Swal.fire({
                title: 'Are you sure?',
                text: "The patient you entered is not portal-registered. Proceed?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Upload!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/upload-document.php',
                        data: {
                            base64: base64,
                            doctype: doctype,
                            sentTo: sentTo,
                            pname: pname,
                            fileExt: fileExt
                        },
                        success: function (result) {
                            $('#patient-search-2').val('');
                            $('#file').val(null);
                            $('#file').hide();
                            $('#upload-from-device').show();
                            $('#file-to-database').hide();
                            $('.add-document-overlay').fadeOut();
                            $('.direct-upload-medtech').fadeOut();
                            $('#upload-from-device').val('0000')
                            $('#file-to-database').val('0000')
                            $('#generate-document-2').val('0000')
                            $('#test-type-2').val('')

                            if (result.includes("Error while uploading the file: ")) {
                                alert(result)
                            } else {
                                Swal.fire(
                                    'Uploaded!',
                                    'Your file has been uploaded!',
                                    'success'
                                )
                            }
                        }
                    })
                }
            })
        }
        else if (sentTo !== '0000') {
            Swal.fire({
                title: 'Issue a bill?',
                icon: 'question',
                text: 'Before uploading, you must agree/disagree if the patient shall pay for the lab result',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Issue',
                denyButtonText: `Don't Issue`,
            }).then((result) => {
                if (result.isConfirmed) {
                    // IF CONFIRMED, DISPLAY BILL ISSUE WINDOW
                    appnum = sentTo;
                    $('#issue-bill-lab').val(appnum)

                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/get-patient-info.php',
                        data: {
                            appointmentNum: appnum,
                            fromLabRes: 'true'
                        },
                        success: function (result) {
                            $('.issue-details').html(result)
                        }
                    })
                    $('.dim-4').fadeIn()
                } else if (result.isDenied) {
                    //IF DENIED SIMPLY UPLOAD TO DATABASE
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/upload-document.php',
                        data: {
                            base64: base64,
                            doctype: doctype,
                            sentTo: sentTo,
                            fileExt: fileExt
                        },
                        success: function (result) {
                            $('#patient-search').val('');
                            $('#document-type').val('default');
                            $('#file').val(null);
                            $('#file').hide();
                            $('#upload-from-device').show();
                            $('#file-to-database').hide();
                            $('.add-document-overlay').fadeOut();
                            $('.document-upload-container').fadeOut();
                            if (result.includes("Error while uploading the file: ")) {
                                alert(result)
                            } else {
                                Swal.fire('Sent', 'The patient will be notified and won\'t be required to pay a bill.', 'success')
                            }
                        }
                    })
                }
            })
        }
    })

    //IF DRAFT CREATE
    $('#generate-document').on('click', function () {
        pname = $('#patient-search').val();
        testType = $('#test-type').val();
        collectionDate = $('#collection-date').val();
        resultDate = $('#result-date').val();
        pid = $(this).val();
        $('#issue-bill-medtech').val(pid)

        //KNOW FIRST IF PATIENT IS REGISTERED OR NOT
        //IF NOT SIMPLY CREATE DRAFT
        //IF REGISTERED ASK FIRST IF BILL ISSUE OR NOT, IF NOT 

        if (pid == '0000') {
            $.ajax({
                type: 'POST',
                url: 'php_processes/insert-lab-draft.php',
                data: {
                    pname: pname,
                    testType: testType,
                    collectionDate: collectionDate,
                    resultDate: resultDate,
                    pid: pid
                },
                success: function (result) {
                    $('.lab-tbl').html(result);
                    $('#generate-document').prop('disabled', true);
                    $('.add-document-overlay').fadeOut();
                    $('.medtech-draft-container').fadeOut();
                    $('#portal-registered').prop('checked', false);
                    $('#patient-search').val('');
                    $('#patient-search-2').val('');
                    $('#test-type').val('');
                    $('#collection-date').val('');
                    $('#result-date').val('');
                    $('#generate-document').val('0000')
                    $('#upload-from-device').val('0000')
                    $('#file-to-database').val('0000')
                    $('#generate-document-2').val('0000')
                    clearInterval(interval)

                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/medtech-dynamic-table.php',
                        success: function (result) {
                            $('.lab-tbl').html(result)
                        }
                    })
                }
            })
        }
        //IF PATIENT IS REGISTERED, ASK FIRST IF BILL
        else {
            Swal.fire({
                title: 'Issue a bill?',
                icon: 'question',
                text: 'Before uploading, you must agree/disagree if the patient shall pay for the lab result',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Issue',
                denyButtonText: `Don't Issue`,
            }).then((result) => {
                if (result.isConfirmed) {
                    // IF CONFIRMED, DISPLAY BILL ISSUE WINDOW
                    appnum = pid;
                    $('#issue-bill-lab').val(appnum)

                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/get-patient-info.php',
                        data: {
                            appointmentNum: appnum,
                            fromLabRes: 'true'
                        },
                        success: function (result) {
                            $('.issue-details').html(result)
                        }
                    })
                    $('.dim-4').fadeIn()
                } else if (result.isDenied) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/insert-lab-draft.php',
                        data: {
                            pname: pname,
                            testType: testType,
                            collectionDate: collectionDate,
                            resultDate: resultDate,
                            pid: pid
                        },
                        success: function (result) {
                            $('.lab-tbl').html(result);
                            $('#generate-document').prop('disabled', true);
                            $('.add-document-overlay').fadeOut();
                            $('.medtech-draft-container').fadeOut();
                            $('#portal-registered').prop('checked', false);
                            $('#patient-search').val('');
                            $('#patient-search-2').val('');
                            $('#test-type').val('');
                            $('#collection-date').val('');
                            $('#result-date').val('');
                            $('#generate-document').val('0000')
                            $('#upload-from-device').val('0000')
                            $('#file-to-database').val('0000')
                            $('#generate-document-2').val('0000')
                            clearInterval(interval)

                            $.ajax({
                                type: 'POST',
                                url: 'php_processes/medtech-dynamic-table.php',
                                success: function (result) {
                                    $('.lab-tbl').html(result)
                                }
                            })
                        }
                    })

                    Swal.fire(
                        'Success!',
                        'Your draft has been created!',
                        'success'
                    )
                }
            })
        }
    })

    function checkInputs() {
        empty = false;

        input1 = $('#patient-search').val();
        input2 = $('#test-type').val();
        input3 = $('#collection-date').val();
        input4 = $('#result-date').val();

        if (input1 == '' || input2 == '' || input3 == '' || input4 == '') {
            empty = true;
        }

        if ($('#portal-registered').is(':checked') && $('#generate-document').val() == '0000') {
            empty = true;
        }

        if ($('#patient-error').css('display') != 'none') {
            empty = true;
        }

        $('#generate-document').prop('disabled', empty);
    }

    function checkInputs3() {
        empty = false;

        input1 = $('#patient-search-3').val();
        input2 = $('#test-type-3').val();
        input3 = $('#collection-date-3').val();
        input4 = $('#result-date-3').val();

        if (input1 == '' || input2 == '' || input3 == '' || input4 == '') {
            empty = true;
        }

        if(!$('#portal-registered-3').is(':checked')){
            $('#add-ongoing-test').val('0000')
        }

        if ($('#portal-registered-3').is(':checked') && $('#add-ongoing-test').val() == '0000') {
            empty = true;
        }

        if ($('#patient-error-3').css('display') != 'none') {
            empty = true;
        }

        $('#add-ongoing-test').prop('disabled', empty);
    }

    $(document).on('click', '.edit-draft', function () {
        docnum = $(this).val();
        pid = $(this).val()
        pid = pid.slice(pid.length - 4);
        pname = $(this).parent().parent().find('span:first-child').html();

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-lab-draft.php',
            data: {
                docnum: docnum
            },
            success: function (result) {
                let windowName = 'w_' + Date.now() + Math.floor(Math.random() * 100000).toString();
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "extras/labres-template.php");

                form.setAttribute("target", windowName);

                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", "content");
                hiddenField.setAttribute("value", result);
                form.appendChild(hiddenField);

                var hiddenField2 = document.createElement("input");
                hiddenField2.setAttribute("type", "hidden");
                hiddenField2.setAttribute("name", "pid");
                hiddenField2.setAttribute("value", pid);
                form.appendChild(hiddenField2);

                var hiddenField3 = document.createElement("input");
                hiddenField3.setAttribute("type", "hidden");
                hiddenField3.setAttribute("name", "pname");
                hiddenField3.setAttribute("value", pname);
                form.appendChild(hiddenField3);

                var hiddenField4 = document.createElement("input");
                hiddenField4.setAttribute("type", "hidden");
                hiddenField4.setAttribute("name", "docnum");
                hiddenField4.setAttribute("value", docnum);
                form.appendChild(hiddenField4);

                document.body.appendChild(form);
                window.open('', windowName);

                form.submit();
                // redirect = window.open('extras/labres-template.php?content=' + result + "&docnum=" + docnum + '&pid=' + pid + "&pname=" + pname, '_blank', 'location=yes,height=800,width=1000,scrollbars=yes,status=yes');
            }
        })
    })

    $('#reload-uploads').on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'php_processes/medtech-uploads.php',
            success: function (result) {
                $('.lab-tbl-2').html(result)
                $('#page-num').html('1')
                $('#offset').html('0')
            }
        })
    })

    $(document).on('click', '.delete-draft', function () {
        docnum = $(this).val()

        Swal.fire({
            title: 'Are you sure?',
            text: "This process is irreversible and will make the draft unrecoverable",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/delete-lab-draft.php',
                    data: {
                        docnum: docnum
                    },
                    success: function (result) {
                        $('.lab-tbl').html(result)
                    }
                })
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    })

    $(document).on('click', '.view-document', function () {
        docnum = $(this).val()
        window.location.href = 'medtech-prescription.php?docnum=' + docnum;
    })

    $(document).on('click', '.download-pdf', function () {
        docnum = $(this).val()

        Swal.fire(
            'Disclaimer',
            'This file is protected by the portal. Upon the download, you are liable of the patients\' confidentiality. Responsibility to prevent unauthorized disclosures of the document shall be a priority.',
            'warning'
        ).then((result) => {
            $.ajax({
                type: 'POST',
                url: 'php_processes/download-file.php',
                data: {
                    docnum: docnum
                },
                success: function (file) {
                    file = JSON.parse(file)
                    let base64 = file.base64;
                    let fileExt = file.file_ext;

                    if (!base64.includes('data')) {
                        base64 = 'data:' + fileExt + ';base64,' + base64;
                    }

                    var a = document.createElement("a"); //Create <a>
                    a.href = base64; //Image Base64 Goes here
                    a.download = "labresult-" + docnum;
                    a.click(); //Downloaded file
                }
            })
        })
    })

    $(document).on('click', '#prev', function () {
        var page = $(this).parent().find('#page-num').html()
        var thiss = $(this)

        //-5 offset
        var offset = parseInt($(this).parent().find('#offset').html()) - 5;

        //IF OFFSET IS LOWER THAN 0 DONT PAGINATE
        if (offset < 0) {
            offset += 5;
        }
        else {
            page -= 1;
        }
        $(this).parent().find('#offset').html(offset)

        $.ajax({
            type: 'POST',
            url: 'php_processes/labresult-pagination.php',
            data: {
                offset: offset
            },
            success: function (result) {
                $('.lab-tbl-2').html(result)
                thiss.parent().find('#page-num').html(page)
            }
        })
    })

    $(document).on('click', '#next', function () {
        var page = $(this).parent().find('#page-num').html()
        var thiss = $(this)

        //+5 OFFSET
        var offset = parseInt($(this).parent().find('#offset').html()) + 5;
        $(this).parent().find('#offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/labresult-pagination.php',
            data: {
                offset: offset
            },
            success: function (result) {
                //IF NO ITEMS IN NEXT PAGE, STAY IN LAST PAGE
                if (result == "<div class = 'empty'>You don't have any uploads</div>") {
                    offset -= 5
                    thiss.parent().find('#offset').html(offset)
                }
                else {
                    page = parseInt(page) + 1
                    $('.lab-tbl-2').html(result);
                    thiss.parent().find('#page-num').html(page)
                }
            }
        })
    })
})

