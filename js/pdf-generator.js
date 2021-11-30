var searchParams = new URLSearchParams(window.location.search);
var text = "The document will be sent to the patient and be uploaded!";

if (searchParams.get('pid') === '0000') {
    text = "The document will be uploaded to the database";
}

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
            const params = new URLSearchParams(window.location.search);
            doctype = params.get('docType');
            pid = params.get('pid');
            pname = params.get('pname');

            var opt = {
                margin: 0,
                filename: 'myfile.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            file = html2pdf().from(invoice).toPdf().get('pdf').then(function (pdfObj) {
                const perBlob = pdfObj.output('blob');

                var reader = new FileReader();
                reader.readAsDataURL(perBlob);
                reader.onloadend = function () {
                    var base64data = reader.result;

                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(xhttp.responseText);
                        }
                    }
                    xhttp.open("POST", "../php_processes/blob-to-database.php", true);
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhttp.send("blob=" + encodeURIComponent(base64data) + "&doctype=" + doctype + "&pid=" + pid + "&pname=" + pname);
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
    })


})


document.getElementById("copy").addEventListener("click", () => {
    const invoice = this.document.getElementById("invoice");

    const params = new URLSearchParams(window.location.search);
    doctype = params.get('docType');
    const date = Date.now();
    pid = params.get('pid');
    filename = doctype + "-" + date + pid;

    var opt = {
        margin: 0,
        filename: filename,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    file = html2pdf().set(opt).from(invoice).save();
})


$(document).ready(function () {
    addDiv = function () {
        var medicineTableContent = document.createElement('div');
        medicineTableContent.classList.add('medicine-table-content')
        medicineTableContent.innerHTML = "<span contenteditable = 'true' class = 'left'>Drug Name</span><span contenteditable = 'true' class = 'center'>0mg</span><span contenteditable = 'true' class = 'right'>Sample Frequency</span><button class = 'delete-div'>Delete</button>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(medicineTableContent);
    }

    addDivLab = function () {
        if ($('.lab-result-content, .lab-test-head').length >= 30) {
            $('#add-div-lab').prop('disabled', true);
            $('#add-head-lab').prop('disabled', true);
        }
        var labResultContent = document.createElement('div');
        labResultContent.classList.add('lab-result-content');
        labResultContent.innerHTML = "<span contenteditable='true'>Test Name</span><span contenteditable='true'>0</span><span contenteditable='true'>#/#</span><span contenteditable='true'>0/0</span><button class = 'delete-div'>Delete</button>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(labResultContent);
    }

    addHeadLab = function () {
        if ($('.lab-result-content, .lab-test-head').length >= 30) {
            $('#add-div-lab').prop('disabled', true);
            $('#add-head-lab').prop('disabled', true);
        }
        var labResultContent = document.createElement('div');
        labResultContent.classList.add('lab-test-head');
        labResultContent.innerHTML = "<span>Enter Head Title</span><button class = 'delete-div'>Delete</button>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(labResultContent);
    }

    $('#add-div').click(function () {
        addDiv();
    })

    $('#add-div-lab').click(function () {
        addDivLab();
    })

    $('#add-head-lab').click(function () {
        addHeadLab();
    })

    $("#revert").click(function () {

        if (searchParams.get('pid') === '0000') {
            var today = new Date();
            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();

            document.getElementById('name').innerHTML = ucwords(searchParams.get('pname'));
            document.getElementById('age').innerHTML = '0';
            document.getElementById('address').innerHTML = 'Patient Address';
            document.getElementById('date').innerHTML = date;

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
                    document.getElementById('address').innerHTML = 'Patient Address';
                    document.getElementById('date').innerHTML = date;
                }
            })
        }

        var divTable = document.getElementById('medicine-table');
        var toDelete = divTable.getElementsByClassName('medicine-table-content');

        while (toDelete[0]) {
            toDelete[0].parentNode.removeChild(toDelete[0]);
        }

        addDiv();
    })



    $("#revert-lab").click(function () {

        if (searchParams.get('pid') === '0000') {
            var today = new Date();
            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();

            document.getElementById('name').innerHTML = ucwords(searchParams.get('pname'));
            document.getElementById('age').innerHTML = 'Unspecified';
            document.getElementById('date').innerHTML = date;
            document.getElementById('sex').innerHTML = 'Unspecified';
            document.getElementById('doctor').innerHTML = searchParams.get('doctorname');

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
                    document.getElementById('doctor').innerHTML = searchParams.get('doctorname');
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
        $('#add-div-lab').prop('disabled', false);
        $('#add-head-lab').prop('disabled', false);
    })

    $(document).on('mouseover', '.lab-result-content', function () {
        $(this).find('.delete-div').show();
    })

    $(document).on('mouseleave', '.lab-result-content', function () {
        $(this).find('.delete-div').hide();
    })

    $(document).on('mouseover', '.lab-test-head', function () {
        $(this).find('.delete-div').show();
    })

    $(document).on('mouseleave', '.lab-test-head', function () {
        $(this).find('.delete-div').hide();
    })

    $(document).on('click', '.delete-div', function () {
        $(this).parent().remove();
    })

    $(document).on('mouseover', '.medicine-table-content', function () {
        $(this).find('.delete-div').show();
    })

    $(document).on('mouseleave', '.medicine-table-content', function () {
        $(this).find('.delete-div').hide();
    })

    $('#print').on('click', function () {
        window.print();
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
