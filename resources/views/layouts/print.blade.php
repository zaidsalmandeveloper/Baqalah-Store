<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Print Document' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-50: #ecf3ff;
            --brand-100: #dde9ff;
            --brand-500: #465fff;
            --brand-600: #3641f5;
            --brand-700: #2a31d8;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-700: #374151;
            --gray-900: #111827;
            --success: #12b76a;
            --warning: #f79009;
            --error: #f04438;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: var(--gray-900);
            background: #eef2f7;
            padding: 24px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-actions {
            max-width: 210mm;
            margin: 0 auto 16px;
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-primary { background: var(--brand-500); color: #fff; }
        .btn-primary:hover { background: var(--brand-600); }
        .btn-outline { background: #fff; color: var(--gray-700); border: 1px solid var(--gray-200); }

        .document {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 10px 40px rgba(16, 24, 40, 0.12);
            border-radius: 12px;
            overflow: hidden;
        }

        .document-accent {
            height: 6px;
            background: linear-gradient(90deg, var(--brand-600), var(--brand-500), #7592ff);
        }

        .document-body { padding: 32px 36px 28px; }

        .doc-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 28px;
        }

        .brand-block { display: flex; gap: 16px; align-items: flex-start; flex: 1; }

        .logo {
            max-height: 72px;
            max-width: 120px;
            object-fit: contain;
        }

        .logo-placeholder {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            background: var(--brand-50);
            border: 1px solid var(--brand-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-500);
            font-size: 22px;
            font-weight: 700;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .brand-meta { color: var(--gray-500); font-size: 11px; line-height: 1.7; }
        .brand-meta strong { color: var(--gray-700); font-weight: 600; }

        .doc-badge-wrap { text-align: right; }

        .doc-badge {
            display: inline-block;
            background: var(--brand-500);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 8px 16px;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .doc-badge.invoice { background: var(--brand-700); }

        .doc-number {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 4px;
        }

        .doc-meta-line { font-size: 11px; color: var(--gray-500); margin-bottom: 2px; }
        .doc-meta-line span { color: var(--gray-700); font-weight: 600; }

        .status-pill {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-pill.success { background: #ecfdf3; color: var(--success); }
        .status-pill.pending { background: #fffaeb; color: var(--warning); }
        .status-pill.reject { background: #fef3f2; color: var(--error); }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-card {
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
        }

        .info-card-header {
            background: var(--brand-50);
            border-bottom: 1px solid var(--brand-100);
            padding: 8px 14px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--brand-600);
        }

        .info-card-body { padding: 12px 14px; font-size: 11px; line-height: 1.7; color: var(--gray-700); }
        .info-card-body .name { font-size: 13px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
        .info-card-body a { color: var(--brand-600); text-decoration: none; font-weight: 600; }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--brand-600);
            margin-bottom: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .items-table thead th {
            background: var(--brand-500);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 11px 12px;
            text-align: left;
            border: none;
        }

        .items-table thead th.text-right { text-align: right; }

        .items-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--gray-100);
            font-size: 11px;
            color: var(--gray-700);
        }

        .items-table tbody tr:nth-child(even) td { background: var(--gray-50); }
        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table tbody td.product { font-weight: 600; color: var(--gray-900); }
        .items-table .text-right { text-align: right; }

        .summary-row {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            align-items: flex-start;
        }

        .tax-note {
            flex: 1;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 11px;
            color: var(--gray-500);
        }

        .tax-note strong { display: block; color: var(--gray-700); margin-bottom: 4px; font-size: 12px; }

        .totals-box {
            width: 280px;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
        }

        .totals-box table { width: 100%; border-collapse: collapse; }
        .totals-box td { padding: 9px 14px; font-size: 11px; color: var(--gray-600); }
        .totals-box td.amount { text-align: right; font-weight: 600; color: var(--gray-900); }
        .totals-box tr:not(:last-child) td { border-bottom: 1px solid var(--gray-100); }
        .totals-box tr.grand td {
            background: var(--brand-500);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 14px;
        }
        .totals-box tr.grand td.amount { color: #fff; font-size: 15px; }

        .document-footer {
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            font-size: 10px;
            color: var(--gray-400);
        }

        .footer-brand { font-weight: 600; color: var(--brand-500); }

        @page { size: A4; margin: 12mm; }

        @media print {
            body { background: #fff; padding: 0; }
            .print-actions { display: none !important; }
            .document { box-shadow: none; border-radius: 0; max-width: 100%; min-height: auto; }
            .document-body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="javascript:history.back()" class="btn btn-outline">Back</a>
    </div>
    @yield('content')
</body>
</html>
