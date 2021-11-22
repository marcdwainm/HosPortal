document.getElementById("download").addEventListener("click", () => {
    const invoice = this.document.getElementById("invoice");

    const params = new URLSearchParams(window.location.search);
    doctype = params.get('docType');
    pid = params.get('pid');

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
            xhttp.send("blob=" + encodeURIComponent(base64data) + "&doctype=" + doctype + "&pid=" + pid);
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
        document.getElementById('name').innerHTML = 'Sample Name';
        document.getElementById('age').innerHTML = '0';
        document.getElementById('address').innerHTML = 'Sample Address';
        document.getElementById('date').innerHTML = '00/00/0000';

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

