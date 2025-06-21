@extends('layouts.mobile.app')

@section('content')
    {{-- CSS ini sudah mencakup semua style yang kita butuhkan --}}
    <style>
        .calendar-container{padding:15px}.calendar-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}.calendar-header .month-year{font-size:1.2rem;font-weight:600}.calendar{width:100%;border-collapse:collapse}.calendar td,.calendar th{text-align:center;padding:5px;width:14.28%}.calendar th{font-size:.8rem;color:#888;padding-bottom:10px}.calendar .day{padding:8px 5px;border:1px solid #f0f0f0;vertical-align:top;height:85px}.calendar .day-number{font-size:.85rem;font-weight:500;color:#333}.calendar .other-month .day-number{color:#ccc}.calendar .current-day{background-color:#e7f0ff}.calendar .current-day .day-number{background-color:#007bff;color:#fff;border-radius:50%;width:24px;height:24px;display:inline-flex;align-items:center;justify-content:center}
        /* Style yang sudah disesuaikan untuk label multi-baris */
        .schedule-label{display:block;font-size:.7rem;padding:5px;border-radius:4px;margin-top:5px;color:#fff;text-align:left;line-height:1.3}
        .schedule-work{background-color:#28a745} /* Hijau */
        .schedule-off{background-color:#dc3545} /* Merah */
        .schedule-holiday{background-color:#ffc107;color:#333!important} /* Kuning */
    </style>

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Jadwal Kerja</div>
        <div class="right"></div>
    </div>

    @php
        $carbon = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
        $jumlahHari = $carbon->daysInMonth;
        $hariPertama = $carbon->dayOfWeek; // 0 (Minggu) - 6 (Sabtu)
        $hariIni = date('Y-m-d');

        // Navigasi bulan menggunakan Carbon untuk akurasi
        $prev_carbon = $carbon->copy()->subMonth();
        $next_carbon = $carbon->copy()->addMonth();
    @endphp

    <div id="content-section" class="calendar-container">
        <div class="section-title" style="margin-top:5px; margin-bottom: 20px;">Jadwal: {{ $nama_karyawan }}</div>
        <div class="calendar-header">
            <a href="{{ route('presensi.jadwalkerja', ['bulan' => $prev_carbon->format('m'), 'tahun' => $prev_carbon->format('Y')]) }}" class="btn btn-sm btn-primary">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
            <div class="month-year">{{ $nama_bulan[(int)$bulan] }} {{ $tahun }}</div>
            <a href="{{ route('presensi.jadwalkerja', ['bulan' => $next_carbon->format('m'), 'tahun' => $next_carbon->format('Y')]) }}" class="btn btn-sm btn-primary">
                <ion-icon name="chevron-forward-outline"></ion-icon>
            </a>
        </div>

        <table class="calendar">
            <thead>
                <tr>
                    <th>Min</th> <th>Sen</th> <th>Sel</th> <th>Rab</th> <th>Kam</th> <th>Jum</th> <th>Sab</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php $day_count = 1; @endphp
                    @for ($i = 0; $i < $hariPertama; $i++) <td></td> @endfor

                    @while ($day_count <= $jumlahHari)
                        @if ($hariPertama % 7 == 0 && $hariPertama != 0) </tr><tr> @endif
                        
                        @php
                            $tanggalPenuh = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day_count, 2, '0', STR_PAD_LEFT);
                            $namaHariIni = getnamaHari(date('D', strtotime($tanggalPenuh)));
                            $isCurrentDay = ($tanggalPenuh == $hariIni);
                        @endphp

                        <td class="day {{ $isCurrentDay ? 'current-day' : '' }}">
                            <div class="day-number">{{ $day_count }}</div>
                            
                            {{-- LOGIKA BARU YANG SUDAH DISESUAIKAN --}}
                            @php
    $jadwalHariIni = null;
    $isLibur = false;
    $keteranganLibur = '';
    $jenisLibur = '';

    // --- PRIORITAS SUDAH DIPERBAIKI ---

    // Prioritas 1: Cek Libur Khusus Karyawan (Paling Spesifik)
    if (isset($liburKhususKaryawan[$tanggalPenuh])) {
        $isLibur = true;
        $keteranganLibur = $liburKhususKaryawan[$tanggalPenuh]->keterangan;
        $jenisLibur = 'khusus'; // Tandai sebagai libur khusus (merah)
    }
    // Prioritas 2: Cek Libur Nasional
    elseif (isset($hariLibur[$tanggalPenuh])) {
        $isLibur = true;
        $keteranganLibur = $hariLibur[$tanggalPenuh]->keterangan;
        $jenisLibur = 'nasional'; // Tandai sebagai libur nasional (kuning)
    }
    // Prioritas 3: Cek Jadwal Khusus per Tanggal
    elseif (isset($jadwalByDate[$tanggalPenuh])) {
        $jadwalHariIni = $jadwalByDate[$tanggalPenuh];
    }
    // Prioritas 4: Cek Jadwal Pribadi Mingguan
    elseif (isset($jadwalMingguan[$namaHariIni])) {
        $jadwalHariIni = $jadwalMingguan[$namaHariIni];
    }
    // Prioritas 5: Cek Jadwal Departemen
    elseif(isset($jadwalDeptMingguan[$namaHariIni])) {
        $jadwalHariIni = $jadwalDeptMingguan[$namaHariIni];
    }
@endphp

                            {{-- Logika untuk menampilkan jadwal atau libur dengan warna berbeda --}}
                            @if ($isLibur)
                                @if ($jenisLibur == 'khusus')
                                    {{-- Jika libur khusus, gunakan class .schedule-off (MERAH) --}}
                                    <div class="schedule-label schedule-off" title="{{ $keteranganLibur }}">
                                        {{ $keteranganLibur }}
                                    </div>
                                @else
                                    {{-- Jika libur nasional, gunakan class .schedule-holiday (KUNING) --}}
                                    <div class="schedule-label schedule-holiday" title="{{ $keteranganLibur }}">
                                        {{ $keteranganLibur }}
                                    </div>
                                @endif
                            @elseif ($jadwalHariIni)
                                <div class="schedule-label schedule-work">
                                    <div style="font-weight: 600;">{{ $jadwalHariIni->nama_jam_kerja }}</div>
                                    <small style="display: block; margin-top: 2px;">
                                        {{ date('H:i', strtotime($jadwalHariIni->jam_masuk)) }} - {{ date('H:i', strtotime($jadwalHariIni->jam_pulang)) }}
                                    </small>
                                </div>
                            @else
                                <div class="schedule-label schedule-off">LIBUR</div>
                            @endif
                        </td>

                        @php $day_count++; $hariPertama++; @endphp
                    @endwhile
                    
                    @while ($hariPertama % 7 != 0) <td></td> @php $hariPertama++; @endphp @endwhile
                </tr>
            </tbody>
        </table>
    </div>
@endsection