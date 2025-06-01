<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportData['title'] ?? 'SIKMA - Rekapitulasi Presensi' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', Arial, sans-serif;
            background: #ffffff;
            color: #1f2937;
            line-height: 1.6;
            font-size: 9pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }

        .pdf-container {
            min-height: 100vh;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            padding: 4px;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        /* --- Header --- */
        .header-pdf {
            background: #ffffff;
            padding: 15px 20px;
            border-bottom: 2px solid #1f2937;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: #1f2937;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 22px;
            flex-shrink: 0;
        }

        .brand-info h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 2px;
            letter-spacing: -0.25px;
        }

        .brand-info p {
            color: #4b5563;
            font-size: 11pt;
            font-weight: 500;
        }

        .report-title-pdf {
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }

        .report-title-pdf h2 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .report-title-pdf .subtitle {
            color: #4b5563;
            font-size: 11pt;
        }

        /* --- Info Grid --- */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            padding: 15px 20px;
        }

        .info-card {
            background: #f9fafb;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #3b82f6;
            border-radius: 4px;
        }

        .info-label {
            font-size: 8pt;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 10pt;
            font-weight: 500;
            color: #1f2937;
        }

        /* --- Table Container & Header --- */
        .table-container-pdf {
            padding: 0 20px 20px;
        }

        .table-header-pdf {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #d1d5db;
        }

        .table-title-pdf {
            font-size: 14pt;
            font-weight: 600;
            color: #111827;
        }

        .stats-pills {
            display: flex;
            gap: 8px;
        }

        .stat-pill {
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            background: #f3f4f6;
            font-size: 8pt;
            font-weight: 600;
            color: #374151;
            border-radius: 16px;
        }

        /* --- Attendance Table --- */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03), 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .attendance-table th {
            background: #f3f4f6;
            color: #374151;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #d1d5db;
        }

        .attendance-table td {
            padding: 9px 8px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            font-size: 9pt;
        }

        .attendance-table tr:last-child td {
            border-bottom: none;
        }

        .attendance-table tr:nth-child(even) td {
            background: #f9fafb;
        }

        /* Status Badge di dalam tabel */
        .status-badge-pdf {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 500;
            display: inline-block;
            line-height: 1.3;
            text-align: center;
        }

        .status-badge-pdf.hadir {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-badge-pdf.sangat-baik {
            background-color: #c7e6d3;
            color: #14532d;
        }

        .status-badge-pdf.cukup {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .status-badge-pdf.kurang {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-badge-pdf.terlambat {
            background-color: #ffedd5;
            color: #c2410c;
        }

        /* --- Footer --- */
        .footer-pdf {
            background: #ffffff;
            padding: 12px 20px;
            border-top: 2px solid #1f2937;
            margin-top: auto;
            /* Penting: Ini yang membuat footer selalu di bawah */
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            gap: 20px;
        }

        .signature-section {
            text-align: center;
            flex-basis: 45%;
        }

        .signature-section h4 {
            color: #374151;
            font-size: 10pt;
            font-weight: 500;
            margin-bottom: 40px;
            line-height: 1.4;
        }

        .signature-line {
            border-bottom: 1px solid #374151;
            width: 170px;
            margin: 0 auto 6px;
        }

        .signature-name {
            font-weight: 600;
            color: #111827;
            font-size: 10.5pt;
        }

        .generation-info {
            color: #6b7280;
            font-size: 8pt;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 15px;
        }

        .page-break-before {
            page-break-before: always;
        }

        .text-center {
            text-align: center;
        }

        /* CSS khusus untuk PDF print */
        @media print {
            .pdf-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .footer-pdf {
                margin-top: auto;
                /* Untuk memastikan footer selalu di bawah saat print */
                position: relative;
            }
        }
    </style>
</head>

<body>
    <div class="pdf-container">
        <div class="content-wrapper">
            <div class="main-content">
                <header class="header-pdf">
                    <div class="logo-section">
                        <div class="brand-info">
                            <h1>{{ $reportData['namaSistem'] ?? 'SIKMA' }}</h1>
                            <p>{{ $reportData['namaInstitusi'] ?? 'Sistem Kehadiran Mahasiswa' }}</p>
                        </div>
                    </div>
                    <div class="report-title-pdf">
                        <h2>{{ $reportData['reportTitle'] ?? 'Rekapitulasi Presensi' }}</h2>
                        <p class="subtitle">{{ $reportData['reportSubtitle'] ?? 'Laporan Kehadiran Mahasiswa' }}</p>
                    </div>
                </header>

                <section class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Semester / Tahun</div>
                        <div class="info-value">{{ $reportData['infoSemesterTahun'] ?? '-' }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Program Studi</div>
                        <div class="info-value">
                            {{ $reportData['infoProgramStudi'] ?? '-' }}</div>
                    </div>
                </section>

                <section class="table-container-pdf">
                    <header class="table-header-pdf">
                        <h3 class="table-title-pdf">Data Kehadiran Mahasiswa</h3>
                        {{-- <div class="stats-pills">
                            <div class="stat-pill">Hadir: {{ $reportData['summaryHadir'] ?? 0 }}</div>
                            <div class="stat-pill">Terlambat: {{ $reportData['summaryTerlambat'] ?? 0 }}</div>
                            <div class="stat-pill">Tidak Hadir: {{ $reportData['summaryTidakHadir'] ?? 0 }}</div>
                        </div> --}}
                    </header>

                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align:center;">No</th>
                                <th style="width: 15%;">NIM</th>
                                <th style="width: 30%;">Nama Mahasiswa</th>
                                <th style="width: 8%; text-align:center;">Hadir</th>
                                <th style="width: 10%; text-align:center;">Tidak Hadir</th>
                                <th style="width: 10%; text-align:center;">Izin/Sakit</th>
                                <th style="width: 12%; text-align:center;">% Kehadiran</th>
                                <th style="width: 10%; text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData['students'] ?? [] as $index => $student)
                                @php
                                    $statusAkhir = $student['status_akhir'] ?? '-';
                                    $statusBadgeClass = '';
                                    if (isset($student['status_akhir'])) {
                                        if ($statusAkhir === 'Sangat Baik') {
                                            $statusBadgeClass = 'sangat-baik';
                                        } elseif ($statusAkhir === 'Baik') {
                                            $statusBadgeClass = 'hadir';
                                        }
                                        // Menggunakan .hadir untuk Baik
                                        elseif ($statusAkhir === 'Cukup') {
                                            $statusBadgeClass = 'cukup';
                                        } elseif ($statusAkhir === 'Kurang') {
                                            $statusBadgeClass = 'kurang';
                                        } elseif ($statusAkhir === 'Terlambat') {
                                            $statusBadgeClass = 'terlambat';
                                        } // Jika ada status ini
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student['nim'] ?? '-' }}</td>
                                    <td>{{ $student['name'] ?? '-' }}</td>
                                    <td class="text-center">{{ $student['totalhadir'] ?? 0 }}</td>
                                    <td class="text-center">{{ $student['totaltidakhadir'] ?? 0 }}</td>
                                    <td class="text-center">
                                        {{ $student['totalizinsakit'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ isset($student['persentase_kehadiran']) ? number_format($student['persentase_kehadiran'], 1) . '%' : '0%' }}
                                    </td>
                                    <td class="text-center"><span
                                            class="status-badge-pdf {{ $statusBadgeClass }}">{{ $statusAkhir }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="no-data text-center">Tidak ada data mahasiswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </div>

            <footer class="footer-pdf">
                <div class="footer-content">
                    <div class="signature-section">
                        <h4>Mengetahui,<br>Kepala Program Studi {{ $reportData['infoProgramStudi'] ?? '' }}</h4>
                        <div style="height: 50px;">&nbsp;</div> {{-- Spasi untuk TTD --}}
                        <div class="signature-line"></div>
                        <div class="signature-name">
                            {{ $reportData['namaKaprodi'] ?? '(.........................................)' }}</div>
                    </div>
                    <div class="signature-section">
                        <h4>{{ $reportData['kotaTtd'] ?? 'Kota' }},
                            {{ $reportData['tanggalTtd'] ?? \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Dosen
                            Wali</h4>
                        <div style="height: 50px;">&nbsp;</div> {{-- Spasi untuk TTD --}}
                        <div class="signature-line"></div>
                        <div class="signature-name">
                            {{ $reportData['infoDosenPengampu'] ?? '(.........................................)' }}
                        </div>
                    </div>
                </div>
                <div class="generation-info">
                    Dokumen ini dibuat secara otomatis oleh {{ $reportData['namaSistem'] ?? 'SIKMA' }} | Dicetak pada:
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
