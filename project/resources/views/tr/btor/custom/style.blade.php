<style>
    /* --- GLOBAL SETTINGS --- */
    @page {
        size: A4 portrait;
        /* We use smaller margins here because the Body Padding below 
           will act as the "visual" margin for the content */
        margin: 1cm; 
    }

    body {
        font-family: 'Tahoma', sans-serif;
        font-size: 10pt;
        line-height: 1.3;
        color: #000;
        background: white;
    }

    /* --- FIXED HEADER & FOOTER STYLES --- */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 50px; /* Adjust based on your Logo height */
        text-align: center;
        background: white;
        z-index: 1000;
    }

    .fixed-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px; /* Adjust based on footer text amount */
        background: white;
        z-index: 1000;
        border-top: 3px double #000; /* Aesthetic border */
        padding-top: 10px;
    }

    .report-footer-content {
        text-align: center;
        font-size: 8pt;
        color: #0F7001;
    }
    .report-footer-content strong { color: #0D654D; }
    .report-footer-content p { margin: 2px 0; }

    /* --- CONTENT SPACING (Crucial) --- */
    /* This pushes the text away from the fixed header/footer */
    @media print {
        body {
            /* Top Padding = Header Height + Space */
            padding-top: 80px; 
            /* Bottom Padding = Footer Height + Space */
            padding-bottom: 100px; 
        }
        
        /* Ensure fixed elements print */
        .fixed-header, .fixed-footer {
            display: block !important;
            z-index: 1000;
        }

        .no-print { display: none !important; }
    }

    /* On Screen, we simulate the look */
    @media screen {
        .fixed-header { position: sticky; top: 0; border-bottom: 1px dashed #ccc; margin-bottom: 20px; z-index: 1000; padding: 10px; }
        .fixed-footer { position: static; border-top: 1px solid #ccc; margin-top: 20px; }
    }

    /* --- TABLE STYLES --- */
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10pt; }
    
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
        -webkit-print-color-adjust: exact;
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

    /* Utils */
    .section-title { font-size: 10pt; font-weight: bold; margin-top: 15pt; margin-bottom: 5pt; }
    .content-box { text-align: justify; }
    .page-break { page-break-before: always; }
    .btn-print { background-color: #007bff; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-close { background-color: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px; }
    .print-controls { position: fixed; top: 70px; right: 20px; background: white; padding: 10px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
</style>