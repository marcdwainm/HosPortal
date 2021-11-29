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
        medicineTableContent.innerHTML = "<span contenteditable = 'true' class = 'left'>Drug Name</span><span contenteditable = 'true' class = 'center'>0mg</span><span contenteditable = 'true' class = 'right'>Sample Frequency</span>";

        var divTable = document.getElementById('medicine-table');
        divTable.appendChild(medicineTableContent);
    }

    $('#add-div').click(function () {
        addDiv();
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
