<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Print' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #111; background: #fff; padding: 24px; }
        .print-actions { margin-bottom: 20px; display: flex; gap: 10px; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; cursor: pointer; border: none; }
        .btn-primary { background: #465fff; color: #fff; }
        .btn-outline { background: #fff; color: #333; border: 1px solid #ccc; }
        .header { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #111; padding-bottom: 16px; margin-bottom: 20px; }
        .header-left { display: flex; gap: 16px; align-items: flex-start; }
        .logo { max-height: 70px; max-width: 140px; object-fit: contain; }
        .company-name { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
        .meta { line-height: 1.6; color: #444; font-size: 12px; }
        .doc-title { font-size: 24px; font-weight: 700; text-align: right; }
        .doc-meta { text-align: right; margin-top: 8px; line-height: 1.7; color: #444; font-size: 12px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .5px; }
        .client-box { background: #f8f8f8; border: 1px solid #ddd; border-radius: 6px; padding: 12px 14px; }
        .client-box a { color: #465fff; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; }
        th { background: #f3f4f6; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .totals { width: 320px; margin-left: auto; margin-top: 16px; }
        .totals td { border: none; padding: 5px 0; }
        .totals .grand td { font-size: 16px; font-weight: 700; border-top: 2px solid #111; padding-top: 10px; }
        @media print {
            .print-actions { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <a href="javascript:history.back()" class="btn btn-outline">Back</a>
    </div>
    @yield('content')
</body>
</html>
