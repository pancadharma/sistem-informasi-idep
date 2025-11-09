    <style>
        .kegiatan-table-data .tb-header {
            width: 20%;
        }

        .kegiatan-table-data .separator {
            width: 1%;
        }

        .kegiatan-table-data .tb-value {
            width: 79%;
        }

        /* Optional: If you want to disable Bootstrap's responsive table behavior for this specific table */
        .kegiatan-table-data {
            width: 100% !important;
            /* Force the table to take full width */
            table-layout: fixed;
            /* Crucial for fixed column widths */
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dee2e6;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }

        .timeline > div {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }

        .timeline > div > .fa,
        .timeline > div > .fas,
        .timeline > div > .far,
        .timeline > div > .fab {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
            padding: 9px 0;
            font-size: 12px;
            color: #fff;
        }

        .timeline > .time-label > span {
            font-weight: 600;
            color: #fff;
            font-size: 12px;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 4px;
        }

        .timeline > div > .timeline-item {
            margin: 0 0 0 60px;
            background: #fff;
            color: #444;
            border-radius: 3px;
            padding: 15px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .timeline > div > .timeline-item > .time {
            color: #999;
            float: right;
            font-size: 12px;
        }

        .timeline > div > .timeline-item > .timeline-header {
            margin: 0 0 10px 0;
            border-bottom: 1px solid #f4f4f4;
            padding-bottom: 10px;
            font-size: 16px;
            line-height: 1.1;
        }

        .timeline > div > .timeline-item > .timeline-body {
            margin: 0;
            padding: 0;
        }

        .timeline > div > .timeline-item > .timeline-footer {
            background: #fff;
            padding: 10px;
            border-top: 1px solid #f4f4f4;
        }

        /* File Cards Styles */
        .file-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }

        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .file-icon {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .file-card .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .file-meta {
            font-size: 0.8rem;
        }

        .file-card .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Tab Styles */
        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Empty State Styles */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .empty-state h6 {
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #adb5bd;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .file-card {
                margin-bottom: 1rem;
            }

            .file-icon {
                height: 100px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                margin-bottom: 0.25rem;
            }
        }
        /* AdminLTE v3 Timeline Styles */
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dee2e6;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }
        .timeline > li {
            position: relative;
            margin-bottom: 20px;
            min-height: 50px;
        }
        .timeline > li > .fa,
        .timeline > li > .fas,
        .timeline > li > .far,
        .timeline > li > .fab {
            position: absolute;
            left: 18px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            padding: 7px 0;
            color: #fff;
            z-index: 2;
        }
        .timeline > .time-label > span {
            font-weight: 600;
            color: #fff;
            font-size: 12px;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 4px;
            margin-left: 60px;
        }
        .timeline > li > .timeline-item {
            margin-left: 60px;
            background: #fff;
            color: #444;
            border-radius: 3px;
            padding: 15px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .timeline > li > .timeline-item > .time {
            color: #999;
            float: right;
            font-size: 12px;
        }
        .timeline > li > .timeline-item > .timeline-header {
            margin: 0 0 10px 0;
            border-bottom: 1px solid #f4f4f4;
            padding-bottom: 10px;
            font-size: 16px;
            line-height: 1.1;
        }
        .timeline > li > .timeline-item > .timeline-body {
            margin: 0;
            padding: 0;
        }
        .bg-blue { background-color: #007bff !important; }
        .bg-green { background-color: #28a745 !important; }
        .bg-red { background-color: #dc3545 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-purple { background-color: #6f42c1 !important; }
        .bg-gray { background-color: #6c757d !important; }
    </style>
