
var changesMade = false;
var searchParams = new URLSearchParams(window.location.search);
var text = "The document will be sent to the patient and be uploaded!";
var clickedSignature = "";

if (searchParams.get('pid') === '0000') {
    text = "The document will be uploaded to the database";
}

if (document.getElementById("download")) {
    document.getElementById("download").addEventListener("click", () => {
        const invoice = this.document.getElementById("invoice");

        Swal.fire({
            title: 'Upload File?',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, upload it!'
        }).then((result) => {
            if (result.isConfirmed) {
                uploadToDatabase(invoice, "")
            }
        })
    })
}

function uploadToDatabase(invoice, withbill) {
    var invoice = invoice
    const params = new URLSearchParams(window.location.search);
    doctype = params.get('docType');
    pid = params.get('pid');
    pname = params.get('pname');
    fileExt = 'application/pdf';
    withBill = withbill != undefined ? withbill : "";
    docnum = $('.bill-num').html() != undefined ? $('.bill-num').html() : "";

    var dimensions = [5.5, 8.5]
    if (doctype == 'labresult') {
        dimensions = [8.3, 11.7]
    }

    var opt = {
        margin: 0,
        filename: 'myfile.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: dimensions, orientation: 'portrait' }
    };
    file = html2pdf().set(opt).from(invoice).toPdf().get('pdf').then(function (pdfObj) {
        const perBlob = pdfObj.output('blob');

        var reader = new FileReader();
        reader.readAsDataURL(perBlob);
        reader.onloadend = function () {
            var base64data = reader.result;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                }
            }
            xhttp.open("POST", "../php_processes/blob-to-database.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send("blob=" + encodeURIComponent(base64data) + "&docnum=" + docnum + "&doctype=" + doctype + "&pid=" + pid + "&pname=" + pname + "&fileExt=" + fileExt + withBill);
        }
    })

    Swal.fire(
        'Success!',
        'The file has been uploaded.',
        'success'
    ).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Close This Window?',
                text: 'You may still want to continue editing',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes close, it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.close();
                }
            })
        }
    })
}

document.getElementById("copy").addEventListener("click", () => {
    const invoice = this.document.getElementById("invoice");

    const params = new URLSearchParams(window.location.search);
    doctype = params.get('docType');
    const date = Date.now();
    pid = params.get('pid');
    filename = doctype + "-" + date + pid;

    var paperFormat;

    if (doctype == 'prescription') {
        paperFormat = [5.5, 8.5]
    }
    else if (doctype == 'labresult') {
        paperFormat = [8.27, 11.69]
    }

    Swal.fire(
        'Disclaimer',
        'This file is protected by the portal. Upon the download, you are liable of the patients\' confidentiality. Responsibility to prevent unauthorized disclosures of the document shall be a priority.',
        'warning'
    ).then((result) => {
        var opt = {
            margin: 0,
            filename: filename,
            image: { type: 'jpeg', quality: 2 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: paperFormat, orientation: 'portrait' }
        };
        file = html2pdf().set(opt).from(invoice).save();
    })
})


$(document).ready(function () {
    var drugNumber = 0;

    $('body').on('click', function () {
        changesMade = true;
    })
    $('body').on('keyup', function () {
        changesMade = true;
    })

    setInterval(function () {
        if (changesMade) {
            $(window).bind('beforeunload', function () {
                return 'Are you sure you want to leave?';
            })
        }
        else if (!changesMade) {
            $(window).unbind('beforeunload');
        }
    }, 100)


    addDiv = function () {
        var medicineTableContent = document.createElement('div');
        medicineTableContent.classList.add('medicine-table-content')
        medicineTableContent.innerHTML = "<span contenteditable = 'true' class = 'left'>Drug Name</span><span contenteditable = 'true' class = 'center'>0mg</span><span contenteditable = 'true' class = 'right'>Sample Frequency</span><button class = 'delete-div'>Delete</button>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(medicineTableContent);
        drugNumber += 1;
    }

    addDivLab = function () {
        if ($('.lab-result-content, .lab-test-head').length >= 25) {
            $('#add-div-lab, #add-div-lab-medtech').prop('disabled', true);
            $('#add-head-lab, #add-head-lab-medtech').prop('disabled', true);
        }
        var labResultContent = document.createElement('tr');
        labResultContent.classList.add('lab-result-content');
        labResultContent.innerHTML = "<td contenteditable='true'>Test Name</td><td><button class = 'add-row'>Add Row</button><span contenteditable = 'true'>0</span></td><td><button class = 'add-row'>Add Row</button><span contenteditable = 'true'>0</span><button class = 'delete-lab-div'>Delete</button></td>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(labResultContent);
    }

    addHeadLab = function () {
        if ($('.lab-result-content, .lab-test-head').length >= 25) {
            $('#add-div-lab, #add-div-lab-medtech').prop('disabled', true);
            $('#add-head-lab, #add-head-lab-medtech').prop('disabled', true);
        }
        var labResultContent = document.createElement('tr');
        labResultContent.classList.add('lab-result-content')
        labResultContent.innerHTML = "<td colspan = '3' contenteditable='true' class = 'head-title'>Head Title<button class = 'delete-lab-div'>Delete</button></td>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(labResultContent);
    }

    $(document).on('click', '#add-div', function () {
        if (drugNumber >= 10) {
        } else {
            addDiv();
        }
    })

    $(document).on('click', '#add-div-lab, #add-div-lab-medtech', function () {
        addDivLab();
    })

    $(document).on('click', '#add-head-lab, #add-head-lab-medtech', function () {
        addHeadLab();
    })

    $(document).on('click', '#revert', function () {

        if (searchParams.get('pid') === '0000') {
            var today = new Date();
            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();

            document.getElementById('name').innerHTML = ucwords(searchParams.get('pname'));
            document.getElementById('age').innerHTML = 'Unspecified';
            document.getElementById('address').innerHTML = 'Patient Address';
            document.getElementById('date').innerHTML = date;

            document.getElementById('licNo').innerHTML = '101888';
            document.getElementById('ptrNo').innerHTML = '114283';
        }
        else if (searchParams.get('pid') !== '0000') {
            var today = new Date();
            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();
            pid = searchParams.get('pid')

            $.ajax({
                url: '../php_processes/field-filler.php',
                method: 'POST',
                data: {
                    userid: pid
                },
                dataType: 'json',
                success: function (data) {
                    document.getElementById('name').innerHTML = ucwords(data.fullname);
                    document.getElementById('age').innerHTML = data.age;
                    document.getElementById('address').innerHTML = data.address;
                    document.getElementById('date').innerHTML = date;

                    document.getElementById('follow-up').innerHTML = "";
                    document.getElementById('doctor').innerHTML = data.doctorfullname;
                    document.getElementById('licNo').innerHTML = '101888';
                    document.getElementById('ptrNo').innerHTML = '114283';
                }
            })
        }

        var divTable = document.getElementById('medicine-table');
        var toDelete = divTable.getElementsByClassName('medicine-table-content');

        while (toDelete[0]) {
            toDelete[0].parentNode.removeChild(toDelete[0]);
        }

        drugNumber = 0;
        addDiv();
    })



    $(document).on('click', "#revert-lab", function () {
        if (searchParams.get('pid') === '0000') {
            var today = new Date();
            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();

            document.getElementById('name').innerHTML = ucwords(searchParams.get('pname'));
            document.getElementById('age').innerHTML = 'Unspecified';
            document.getElementById('date').innerHTML = date;
            document.getElementById('sex').innerHTML = 'Unspecified';
            document.getElementById('address').innerHTML = 'Unspecified';
            document.getElementById('physician').innerHTML = '';
            document.getElementById('lab-title').innerHTML = 'Test Results'
            document.getElementById('remarks').innerHTML = ''
            document.getElementById('first-med-name').innerHTML = 'CLARISSE R. SALVADOR, RMT'
            document.getElementById('first-med-pos').innerHTML = 'Medical Technologist'
            document.getElementById('first-med-no').innerHTML = 'Lic. No. 63417'
            document.getElementById('second-med-name').innerHTML = 'RHODERICK M. CRUZ, MD, DPSP'
            document.getElementById('second-med-pos').innerHTML = 'Pathologist'
            document.getElementById('second-med-no').innerHTML = 'Lic. No. 92087'

        }
        else if (searchParams.get('pid') !== '0000') {
            var today = new Date();
            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();
            pid = searchParams.get('pid')

            $.ajax({
                url: '../php_processes/field-filler.php',
                method: 'POST',
                data: {
                    userid: pid
                },
                dataType: 'json',
                success: function (data) {
                    document.getElementById('name').innerHTML = ucwords(data.fullname);
                    document.getElementById('age').innerHTML = data.age;
                    document.getElementById('date').innerHTML = date;
                    document.getElementById('sex').innerHTML = ucwords(data.sex);
                    document.getElementById('address').innerHTML = 'Unspecified';
                    document.getElementById('physician').innerHTML = '';
                    document.getElementById('lab-title').innerHTML = 'Test Results'
                    document.getElementById('remarks').innerHTML = ''
                    document.getElementById('first-med-name').innerHTML = 'CLARISSE R. SALVADOR, RMT'
                    document.getElementById('first-med-pos').innerHTML = 'Medical Technologist'
                    document.getElementById('first-med-no').innerHTML = 'Lic. No. 63417'
                    document.getElementById('second-med-name').innerHTML = 'RHODERICK M. CRUZ, MD, DPSP'
                    document.getElementById('second-med-pos').innerHTML = 'Pathologist'
                    document.getElementById('second-med-no').innerHTML = 'Lic. No. 92087'
                }
            })
        }

        var divTable = document.getElementById('medicine-table');
        var toDelete = divTable.getElementsByClassName('lab-result-content');

        while (toDelete[0]) {
            toDelete[0].parentNode.removeChild(toDelete[0]);
        }

        toDelete = divTable.getElementsByClassName('lab-test-head');
        while (toDelete[0]) {
            toDelete[0].parentNode.removeChild(toDelete[0]);
        }

        addDivLab();
        $('#add-div-lab, #add-div-lab-medtech').prop('disabled', false);
        $('#add-head-lab, #add-head-lab-medtech').prop('disabled', false);
    })

    $(document).on('mouseover', '.lab-result-content', function () {
        $(this).find('.delete-div').show();
        $(this).find('.delete-lab-div').show();
    })

    $(document).on('mouseleave', '.lab-result-content', function () {
        $(this).find('.delete-div').hide();
        $(this).find('.delete-lab-div').hide();
    })

    $(document).on('mouseover', '.lab-result-content td:nth-child(2), .lab-result-content td:nth-child(3)', function () {
        $(this).find('button').show();
    })

    $(document).on('mouseleave', '.lab-result-content td:nth-child(2), .lab-result-content td:nth-child(3)', function () {
        $(this).find('button').hide();
    })

    $(document).on('click', '.lab-result-content td:nth-child(2) .add-row, .lab-result-content td:nth-child(3) .add-row', function () {
        $(this).parent().append("<span contenteditable = 'true'>0</span>");
        $(this).parent().find('span:nth-child(1)').css({
            'width': '50%',
            'float': 'right',
            'height': '100%'
        })
        $(this).parent().find('span:nth-child(2)').css({
            'float': 'left',
            'width': '50%',
            'height': '100%'
        })
        $(this).html('Delete Row')
        $(this).removeClass('add-row');
        $(this).addClass('delete-row')
    })

    $(document).on('click', '.delete-row', function () {
        $(this).parent().find('span:last-child').remove();
        $(this).parent().find('span').css({
            'width': '100%',
            'height': '100%'
        })
        $(this).html('Add Row')
        $(this).removeClass('delete-row');
        $(this).addClass('add-row')
    })

    $(document).on('mouseover', '.lab-test-head', function () {
        $(this).find('.delete-div').show();
    })

    $(document).on('mouseleave', '.lab-test-head', function () {
        $(this).find('.delete-div').hide();
    })

    $(document).on('click', '.delete-div', function () {
        $(this).parent().remove();

        if (drugNumber > 0) {
            drugNumber -= 1;
        }
    })

    $(document).on('click', '.delete-lab-div', function () {
        $(this).parent().parent().remove();
    })

    $(document).on('mouseover', '.medicine-table-content', function () {
        $(this).find('.delete-div').show();
    })

    $(document).on('mouseleave', '.medicine-table-content', function () {
        $(this).find('.delete-div').hide();
    })

    $(document).on('click', '#print ', function () {
        window.print();
    })

    $(document).on('click', '#save-draft', function () {
        changesMade = false;
        savedContents = $('.wrapper').html()
        s = $('.invisible-params').html();
        arrayList = s.split(', ');
        docnum = arrayList[2];

        $.ajax({
            type: 'POST',
            url: '../php_processes/save-draft.php',
            data: {
                savedContents: savedContents,
                docnum: docnum
            },
            success: function (result) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Draft has been saved',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })
    })

    //SAVE EVERY 15 SECONDS
    setInterval(function () {
        changesMade = false;
        savedContents = $('.wrapper').html()
        s = $('.invisible-params').html();
        arrayList = s.split(', ');
        docnum = arrayList[2];

        $.ajax({
            type: 'POST',
            url: '../php_processes/save-draft.php',
            data: {
                savedContents: savedContents,
                docnum: docnum
            },
            success: function (result) {
            }
        })
    }, 15000)

    $(document).on('click', '.e-sig', function () {
        clickedSignature = $(this).attr('class').split(' ')[1];
        $('#signature-adder-dim').fadeIn();
    })

    $(document).on('click', '.signature-adder-exit', function () {
        $('#signature-adder-dim').fadeOut();
    })

    const canvas = document.getElementById('signature-canvas')
    const signaturePad = new SignaturePad(canvas);

    $(document).on('click', '#clear-signature', function () {
        $("." + clickedSignature).html("")
        signaturePad.clear();
    })

    $(document).on('click', '#add-to-document-signature', function () {
        var imageData = signaturePad.toDataURL();
        console.log(imageData)
        $("." + clickedSignature).html("<img src = '" + imageData + "'/>")
    })

    $(document).on('click', '#upload-signature', function () {
        $('#upload-image-signature').fadeToggle();
    })


    $(document).on('change', '#upload-image-signature', function () {
        var img = this.files[0];

        var reader = new FileReader();

        reader.onloadend = function () {
            $("." + clickedSignature).html("<img src = '" + reader.result + "'/>")
        }
        reader.readAsDataURL(img);

        $(this).hide()
    })

    base64String = "";

    $(document).on('click', '.upload-with-bill', function () {
        const params = new URLSearchParams(window.location.search);
        var pid = params.get('pid')

        if (pid != '0000') {
            //IF PORTAL REGISTERED ASK BILL ISSUING
            Swal.fire({
                title: 'Issue a bill?', icon: 'question', text: 'Before uploading, you must agree/disagree if the patient shall pay for the lab result', showDenyButton: true, showCancelButton: true, confirmButtonText: 'Issue', denyButtonText: `Don't Issue`,
            }).then((result) => {
                if (result.isConfirmed) {
                    // IF CONFIRMED, DISPLAY BILL ISSUE WINDOW
                    appnum = pid;
                    $('#issue-bill-medtech').val(appnum)

                    $.ajax({
                        type: 'POST',
                        url: '../php_processes/get-patient-info.php',
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
                    const invoice = document.getElementById("invoice");
                    uploadToDatabase(invoice, "")
                }
            })
        }
        // IF USER IS 0000 SIMPLY UPLOAD IN DATABASE
        else {
            const invoice = document.getElementById("invoice");
            Swal.fire({
                title: 'Upload File?', text: text, icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, upload it!'
            }).then((result) => { if (result.isConfirmed) { uploadToDatabase(invoice) } })
        }
    })
})


function ucwords(words) {
    var separateWord = words.toLowerCase().split(' ');
    for (var i = 0; i < separateWord.length; i++) {
        separateWord[i] = separateWord[i].charAt(0).toUpperCase() +
            separateWord[i].substring(1);
    }
    return separateWord.join(' ');
}
