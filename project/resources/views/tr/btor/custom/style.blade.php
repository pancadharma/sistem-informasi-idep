<style>
    /* --- 1. GLOBAL SETTINGS --- */
    @page {
        size: A4 portrait;
        margin: 2 cm; /* Matches PHPWord Margins */
    }
    td.media-item {
        text-align: center !important;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Tahoma', sans-serif; /* Matches DOCX Font */
        font-size: 10pt;
        line-height: 1.3;
        color: #000;
        background: white;
        margin: 0 auto;
    }

    .print-container {
        width: 100%;
        max-width: 21cm; /* A4 Width */
        margin: 0 auto;
        padding: 0;
    }

    /* --- 2. HEADER STYLES --- */
    .report-header {
        margin-bottom: 20px;
        text-align: center;
    }

    .report-header h2 {
        font-size: 14pt;
        font-weight: bold;
        margin: 0;
        text-transform: uppercase;
        color: #000;
    }

    /* --- 3. SECTION STYLES --- */
    .section {
        margin-bottom: 15px;
        page-break-inside: avoid;
    }

    .section-title {
        font-size: 10pt;
        font-weight: bold;
        margin-top: 15pt;
        margin-bottom: 5pt;
        text-transform: none;
        color: #000;
    }

    /* --- 4. TABLE STYLES --- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 10pt;
    }

    /* Info Table (No Border) */
    .table-print td {
        padding: 2px 4px;
        vertical-align: top;
        border: none;
    }

    /* Data Table (Bordered & Green Header) */
    .table-bordered {
        width: 100%;
        border-collapse: collapse;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #000 !important; /* Matches DOCX border */
        padding: 4px;
        vertical-align: middle;
        font-size: 10pt;
        word-wrap: break-word;
    }

    .table-bordered thead th,
    .table-bordered th {
        background-color: #385623 !important; /* Green Header */
        color: #FFFFFF !important; /* White Text */
        font-weight: bold;
        text-align: center;
        -webkit-print-color-adjust: exact; /* Force print color */
    }

    /* --- 5. CONTENT UTILITIES --- */
    .content-box {
        text-align: justify;
        margin-bottom: 8px;
    }
    .content-box p {
        margin-bottom: 5px;
    }

    .text-center { text-align: center !important; }
    .text-right { text-align: right !important; }
    .text-bold { font-weight: bold; }

    /* --- 6. FOOTER STYLES --- */
    .report-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 8pt;
        color: #0F7001; /* Footer Green */
        border-top: 3px double #000;
        padding-top: 10px;
        page-break-inside: avoid;
    }
    .report-footer strong {
        color: #0D654D;
    }
    .report-footer p {
        margin: 2px 0;
    }

    /* --- 7. UI ELEMENTS (Buttons, Alerts) --- */
    .alert {
        padding: 8px 12px;
        margin-bottom: 10px;
        border: 1px solid transparent;
        border-radius: 3px;
        font-size: 9pt;
    }
    .alert-info { background-color: #d9edf7; color: #31708f; border-color: #bce8f1; }

    /* Print Controls (Floating) */
    .print-controls {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        display: block;
    }

    .btn-print, .btn-close {
        padding: 8px 15px;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        margin-left: 5px;
    }
    .btn-print { background-color: #007bff; }
    .btn-close { background-color: #6c757d; }

    /* --- 8. MEDIA QUERIES --- */
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: white !important;
        }

        .print-controls, .no-print {
            display: none !important;
        }

        .page-break {
            page-break-before: always;
        }

        /* Ensure links don't show blue/underlined in print unless intended */
        a {
            text-decoration: none;
            color: inherit;
        }
    }
/* --- 9. GALLERY STYLES (Added) --- */
    .media-gallery {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -5px; /* Negative margin for gutter */
    }

    .media-item {
        width: 33.333%; /* 3 Columns */
        padding: 0 5px;
        margin-bottom: 15px;
        page-break-inside: avoid; /* Prevent cutting images in half on print */
        box-sizing: border-box;
    }
    
    td.media-item {
        text-align: center;
        vertical-align: middle;
    }
    .media-card {
        border: 1px solid #ccc;
        background: #fff;
        padding: 5px;
        height: 100%;
    }

    .media-img-container {
        width: 100%;
        height: 150px; /* Fixed height for uniformity */
        background-color: #f0f0f0;
        overflow: hidden;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #eee;
    }

    .media-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures image covers the box without stretching */
        display: block;
    }
    
    /* Icon fallback for non-images in gallery */
    .media-icon-placeholder {
        font-size: 30pt;
        color: #ccc;
    }

    .media-caption {
        font-size: 8pt;
        text-align: center;
        line-height: 1.2;
        padding: 3px;
        word-wrap: break-word;
    }

    .media-meta {
        font-size: 7pt;
        color: #666;
        text-align: center;
        margin-top: 2px;
    }

    /* Print adjustment for gallery */
    @media print {
        .media-img-container {
            border: 1px solid #ccc; /* Ensure border prints */
        }
    }

</style>