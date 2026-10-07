<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('doc_title', 'Dokumen Resmi') - STIP Jakarta</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #525659;
            color: #111827;
            font-family: 'Tinos', serif;
            line-height: 1.5;
            padding: 2rem 1rem;
        }

        /* Paper sheet simulation */
        .paper-sheet {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 20mm 25mm;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            position: relative;
            box-sizing: border-box;
        }

        /* Top control bar */
        .print-controls {
            width: 210mm;
            margin: 0 auto 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1f2937;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
        }

        .btn-print {
            background: #f59e0b;
            color: #000;
            font-weight: 700;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }

        .btn-print:hover {
            background: #fbbf24;
        }

        .btn-back {
            color: #9ca3af;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-back:hover {
            color: #fff;
        }

        /* Official Header / Kop Surat */
        .official-kop {
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
            text-align: center;
            position: relative;
        }

        .kop-logo {
            position: absolute;
            top: 2px;
            left: 5px;
            width: 65px;
            height: 65px;
            border: 2px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .official-kop h3 {
            font-size: 13pt;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .official-kop h2 {
            font-size: 15pt;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .official-kop h4 {
            font-size: 11pt;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .official-kop p {
            font-size: 8.5pt;
            color: #374151;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Document Title */
        .doc-title-block {
            text-align: center;
            margin: 18px 0 24px;
        }

        .doc-title-block h1 {
            font-size: 13.5pt;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.04em;
        }

        .doc-number {
            font-size: 10.5pt;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 4px;
            font-weight: 600;
        }

        /* Details Table */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
            margin: 16px 0;
        }

        .doc-table td {
            padding: 5px 4px;
            vertical-align: top;
        }

        .doc-table td.label-col {
            width: 32%;
            font-weight: 600;
        }

        .doc-table td.colon-col {
            width: 3%;
            text-align: center;
        }

        /* Grid Table for Items */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin: 16px 0;
        }

        .grid-table th, .grid-table td {
            border: 1px solid #1f2937;
            padding: 6px 8px;
            text-align: left;
        }

        .grid-table th {
            background-color: #f3f4f6;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        /* Signatures block */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sign-box {
            width: 230px;
            text-align: center;
            font-size: 10.5pt;
        }

        .sign-space {
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .official-stamp {
            border: 2px dashed #93c5fd;
            color: #1e40af;
            font-size: 8pt;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-transform: uppercase;
            transform: rotate(-5deg);
            opacity: 0.85;
        }

        .sign-name {
            font-weight: 700;
            text-decoration: underline;
        }

        /* Security watermark & footer */
        .security-footer {
            position: absolute;
            bottom: 12mm;
            left: 20mm;
            right: 20mm;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 7.5pt;
            color: #6b7280;
            font-family: 'JetBrains Mono', monospace;
        }

        .qr-placeholder {
            width: 55px;
            height: 55px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6pt;
            text-align: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .print-controls {
                display: none;
            }

            .paper-sheet {
                width: 100%;
                min-height: auto;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Controls -->
    <div class="print-controls">
        <a href="javascript:history.back()" class="btn-back">
            &larr; Kembali ke Aplikasi
        </a>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span>Status Cetak: <strong>Asli (Dokumen Sah)</strong></span>
            <button onclick="window.print()" class="btn-print">
                🖨️ Cetak / Unduh PDF
            </button>
        </div>
    </div>

    <!-- Paper Sheet -->
    <div class="paper-sheet">
        <!-- Kop Surat Resmi STIP Jakarta -->
        <div class="official-kop">
            <div class="kop-logo">⚓</div>
            <h3>KEMENTERIAN PERHUBUNGAN</h3>
            <h4>BADAN PENGEMBANGAN SUMBER DAYA MANUSIA PERHUBUNGAN</h4>
            <h2>SEKOLAH TINGGI ILMU PELAYARAN</h2>
            <p>Jl. Marunda Makmur No. 1, Cilincing, Jakarta Utara 14150 &bull; Telp: (021) 8899-xxxx Fax: (021) 8899-xxxx</p>
            <p>Laman: www.stipjakarta.ac.id &bull; Pos-el: info@stipjakarta.ac.id</p>
        </div>

        @yield('document_content')

        <!-- Security Footer -->
        <div class="security-footer">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="qr-placeholder">
                    VERIFIED<br>DIGITAL<br>SEAL
                </div>
                <div>
                    <div>Dokumen Diterbitkan oleh: <strong>SILT-STIP Terpadu</strong></div>
                    <div>Kode Pengesahan: {{ $document->document_number ?? 'STIP-VERIFIED' }}</div>
                    <div>Dicetak: {{ now()->format('d/m/Y H:i:s') }} WIB &bull; Cetakan ke: {{ $document->print_count ?? 1 }}</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div>Dokumen Sah Tanpa Tanda Tangan Basah</div>
                <div>UU ITE No. 11/2008 Pasal 5 Ayat 1</div>
            </div>
        </div>
    </div>

</body>
</html>
