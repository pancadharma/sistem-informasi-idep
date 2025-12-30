<style>
    /* =====================================================
       TABLE-BASED REPEATING HEADER/FOOTER
       =====================================================
       This is the ONLY reliable pure-CSS method for 
       repeating headers/footers in browser print.
       
       Structure:
       <table class="print-wrapper">
         <thead> = repeating header
         <tfoot> = repeating footer  
         <tbody> = content
       </table>
    */

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Tahoma', sans-serif;
        font-size: 10pt;
        line-height: 1.3;
        color: #000;
        background: white;
        margin: 0;
        padding: 0;
    }

    /* --- PRINT WRAPPER TABLE --- */
    .print-wrapper {
        width: 100%;
        border-collapse: collapse;
    }

    .print-wrapper > thead,
    .print-wrapper > tfoot {
        display: table-header-group;
    }

    .print-wrapper > tfoot {
        display: table-footer-group;
    }

    .print-wrapper > tbody {
        display: table-row-group;
    }

    /* --- HEADER STYLES --- */
    .print-header-row th {
        padding: 0;
        border: none;
        background: white;
    }

    .print-header-content {
        text-align: center;
        padding: 10px 0 15px 0;
        border-bottom: 2px solid #0D654D;
    }

    .print-header-content img {
        height: 38px;
        width: auto;
    }

    .print-header-content h2 {
        font-size: 14pt;
        font-weight: bold;
        margin: 0;
        color: #000;
    }

    /* --- FOOTER STYLES --- */
    .print-footer-row td {
        padding: 0;
        border: none;
        background: white;
    }

    .print-footer-content {
        text-align: center;
        font-size: 8pt;
        color: #0F7001;
        border-top: 3px double #000;
        padding: 10px 0;
        margin-top: 15px;
    }

    .print-footer-content strong {
        color: #0D654D;
    }

    .print-footer-content p {
        margin: 2px 0;
    }

    /* --- BODY CONTENT --- */
    .print-body-row td {
        padding: 15px 0;
        vertical-align: top;
    }

    .print-container {
        background-color: white;
    }

    /* --- PRINT MEDIA --- */
    @media print {
        html, body {
            height: 100%;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print {
            display: none !important;
        }

        /* Make table fill the entire page height to push footer to bottom */
        .print-wrapper {
            height: 100%;
        }

        /* CRITICAL: These make thead/tfoot repeat on each page */
        .print-wrapper > thead {
            display: table-header-group !important;
        }

        .print-wrapper > tfoot {
            display: table-footer-group !important;
        }

        /* Make body row fill available space */
        .print-body-row {
            height: 100%;
        }

        .print-body-row td {
            height: 100%;
            vertical-align: top;
        }

        .section {
            page-break-inside: avoid;
        }

        .page-break-before {
            page-break-before: always;
        }

        .page-break-after {
            page-break-after: always;
        }
    }

    /* --- SCREEN PREVIEW --- */
    @media screen {
        body {
            background: #e0e0e0;
            padding: 20px;
        }

        .print-wrapper {
            max-width: 21cm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }

        .print-body-row td {
            padding: 20px;
        }
    }

    /* --- DATA TABLE STYLES --- */
    table:not(.print-wrapper) {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 10pt;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #000 !important;
        padding: 4px;
        vertical-align: middle;
    }

    .table-bordered thead th, .table-bordered th {
        background-color: #385623 !important;
        color: #FFFFFF !important;
        font-weight: bold;
        text-align: center;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .table-print td {
        padding: 2px 4px;
        vertical-align: top;
        border: none;
    }

    /* --- GALLERY STYLES --- */
    .media-gallery { display: flex; flex-wrap: wrap; margin: 0 -5px; }
    .media-item { width: 33.333%; padding: 0 5px; margin-bottom: 15px; page-break-inside: avoid; }
    .media-card { border: 1px solid #ccc; padding: 5px; }
    .media-img-container {
        width: 100%; height: 150px; background: #f0f0f0; overflow: hidden;
        display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #eee;
    }
    .media-img-container img { width: 100%; height: 100%; object-fit: cover; }
    .media-caption { font-size: 8pt; text-align: center; padding: 3px; }
    .media-meta { font-size: 7pt; color: #666; text-align: center; }

    /* --- UTILITY CLASSES --- */
    .section-title { font-size: 10pt; font-weight: bold; margin-top: 15pt; margin-bottom: 5pt; }
    .content-box { text-align: justify; }
    .text-center { text-align: center; }

    /* Print Controls */
    .btn-print { background-color: #007bff; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-close { background-color: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px; }
    .print-controls { position: fixed; top: 20px; right: 20px; background: white; padding: 10px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 9999; }
</style>