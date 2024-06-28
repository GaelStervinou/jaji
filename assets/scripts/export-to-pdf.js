import html2canvas from "html2canvas-pro";
import html2pdf from "html2pdf.js";

window.onload = (async function () {
    document.getElementById('export-patient-diag').addEventListener('click', function (event) {
        exportToPdf();
    });
})

async function exportToPdf() {
    try {
        const element = document.getElementById("patient-page");
        let detailsElements = element.querySelectorAll('details');
        detailsElements.forEach((details) => {
            if (!details.hasAttribute('open')) {
                details.setAttribute('open', 'open');
            }
        });
        let opt = {
            margin: 1,
            filename: 'myfile.pdf',
            image: {type: 'jpeg', quality: 0.98},
            html2canvas: {scale: 2},
            jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
        };
        const elem = await html2canvas(element);
        html2pdf().set(opt).from(elem).toPdf().save().catch(err => console.error(err));
        detailsElements.forEach((details) => {
            details.removeAttribute('open');
        });
    } catch (error) {
        console.error(error);
    }
}
