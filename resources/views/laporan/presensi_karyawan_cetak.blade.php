<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi Karyawan </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
        @page {
            size: A4
        }

        .sheet {
            overflow: visible !important; /* Mengganti overflow agar tidak memotong bayangan/border */
        }

        .tablereport {
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            width: 100%; /* Menambahkan lebar agar konsisten */
        }

        .tablereport td, .tablereport th {
            border: 1px solid #000;
            padding: 8px; /* Sedikit menyesuaikan padding */
            font-size: 12px;
            vertical-align: top; /* Menjaga alignment */
        }

        .tablereport th {
            text-align: center; /* Judul tabel lebih baik di tengah */
            background-color: #0949b8;
            color: #fff;
            font-size: 13px;
        }
    </style>
</head>

<body class="A4">
    <section class="sheet padding-10mm">
        <div class="header" style="margin-bottom: 10px; border-bottom: 2px solid #000; padding-bottom:10px;">
            <table>
                <tr>
                    <td style="width: 120px">
                        @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                            {{-- Gunakan path absolut untuk rendering PDF yang andal --}}
                            <img src="{{ storage_path('app/public/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan" style="max-width: 100px;">
                        @else
                            <img src="https://placehold.co/100x100?text=Logo" alt="Logo Default" style="max-width: 100px;">
                        @endif
                    </td>
                    <td>
                        <h4 style="line-height: 20px; margin-bottom: 5px; font-family: Arial, Helvetica, sans-serif;">
                            LAPORAN PRESENSI KARYAWAN
                            <br>
                            <span style="font-size: 1.2rem;">{{ $generalsetting->nama_perusahaan }}</span>
                            <br>
                            PERIODE {{ date('d-m-Y', strtotime($periode_dari)) }} - {{ date('d-m-Y', strtotime($periode_sampai)) }}
                        </h4>
                        <span style="font-style: italic; font-family: Arial, Helvetica, sans-serif; font-size:12px">{{ $generalsetting->alamat }}</span><br>
                        <span style="font-style: italic; font-family: Arial, Helvetica, sans-serif; font-size:12px">{{ $generalsetting->telepon }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="datakaryawan" style="display: flex; gap: 20px; margin-top: 20px">
            <div id="fotokaryawan">
                @php
                    $foto_path = storage_path('app/public/karyawan/' . $karyawan->foto);
                    $default_path = public_path('assets/img/avatars/No_Image_Available.jpg');
                @endphp
                @if (!empty($karyawan->foto) && file_exists($foto_path))
                    <img src="{{ $foto_path }}" alt="user image" style="width: 140px; height: 150px; object-fit: cover; border-radius: 8px;">
                @else
                    <img src="{{ $default_path }}" alt="user image" style="width: 140px; height: 150px; object-fit: cover; border-radius: 8px;">
                @endif
            </div>
            <div id="detailkaryawan" style="flex-grow: 1;">
                <table class="tablereport" style="border: none;">
                    {{-- FIX: Menghilangkan duplikasi 'Nama' dan menggantinya dengan 'NIK' --}}
                    <tr>
                        <td style="border: none; padding: 2px; width:100px;">NIK</td>
                        <td style="border: none; padding: 2px; width:10px;">:</td>
                        <td style="border: none; padding: 2px;">{{ $karyawan->nik }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px;">Nama</td>
                        <td style="border: none; padding: 2px;">:</td>
                        <td style="border: none; padding: 2px;">{{ $karyawan->nama_karyawan }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px;">Jabatan</td>
                        <td style="border: none; padding: 2px;">:</td>
                        <td style="border: none; padding: 2px;">{{ $karyawan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px;">Departemen</td>
                        <td style="border: none; padding: 2px;">:</td>
                        <td style="border: none; padding: 2px;">{{ $karyawan->nama_dept }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px;">Cabang</td>
                        <td style="border: none; padding: 2px;">:</td>
                        <td style="border: none; padding: 2px;">{{ $karyawan->nama_cabang }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="presensi" style="margin-top: 20px">
            <table class="tablereport">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jadwal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                        <th>Denda</th>
                        <th>Pot. Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_hadir = 0; $total_izin = 0; $total_sakit = 0; $total_cuti = 0; $total_alfa = 0;
                        $total_terlambat = 0; $total_denda = 0; $total_potongan_jam = 0;
                    @endphp
                    @foreach ($presensi as $d)
                        @php
                            // Blok kalkulasi, dibuat lebih aman
                            $jam_jadwal_masuk = $d->tanggal . ' ' . $d->jam_masuk;

                            // Panggil helper hanya jika jam_in ada isinya
                            $terlambat = !empty($d->jam_in) ? hitungjamterlambat($d->jam_in, $jam_jadwal_masuk) : null;

                            // Panggil helper hanya jika jam_out ada isinya
                            $pulangcepat = !empty($d->jam_out) ? hitungpulangcepat(
                                $d->tanggal, $d->jam_out, $d->jam_pulang, $d->istirahat,
                                $d->jam_awal_istirahat, $d->jam_akhir_istirahat, $d->lintashari
                            ) : 0;

                            $potongan_tidak_hadir = $d->status == 'a' ? $d->total_jam : 0;
                            $potongan_jam_terlambat = 0;
                            $denda = 0;

                            if ($terlambat != null) {
                                if ($terlambat['desimal_terlambat'] < 1) {
                                    $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                                } else {
                                    $potongan_jam_terlambat = $terlambat['desimal_terlambat'];
                                }
                            }

                            if ($d->status == 'h') $total_hadir++;
                            elseif ($d->status == 'i') $total_izin++;
                            elseif ($d->status == 's') $total_sakit++;
                            elseif ($d->status == 'c') $total_cuti++;
                            elseif ($d->status == 'a') $total_alfa++;

                            $total_denda += $denda;
                            $potongan_jam = $pulangcepat + $potongan_jam_terlambat + $potongan_tidak_hadir;
                            $total_potongan_jam += $potongan_jam;

                            $status_map = ['h' => 'green', 'i' => 'yellow', 's' => 'blue', 'c' => 'orange', 'a' => 'red'];
                            $color_status = $status_map[$d->status] ?? 'grey';
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td style="text-align: center;">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td>{{ $d->nama_jam_kerja }} ({{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }})</td>
                            <td style="text-align: center">{!! !empty($d->jam_in) ? date('H:i', strtotime($d->jam_in)) : '<span style="color: red;">-</span>' !!}</td>
                            <td style="text-align: center">
                                {!! !empty($d->jam_out) ? date('H:i', strtotime($d->jam_out)) : '<span style="color: red;">-</span>' !!}
                                @if ($pulangcepat > 0)
                                    <br><span style="color: red; font-size:10px;">(Cepat: {{ $pulangcepat }} Jam)</span>
                                @endif
                            </td>
                            <td style="text-align: center; background-color: {{ $color_status }}; color:white; font-weight:bold;">
                                {{ strtoupper($d->status) }}
                            </td>
                            <td style="text-align: center">{!! $terlambat['show'] ?? '' !!}</td>
                            <td style="text-align: right; color: red">{{ $denda > 0 ? formatAngka($denda) : '' }}</td>
                            <td style="text-align: center; color: red">{{ $potongan_jam > 0 ? $potongan_jam : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="rekap" style="margin-top: 20px; float:right;">
            <table class="tablereport" style="width: 350px;">
                <tr>
                    <th colspan="2">Rekapitulasi Presensi</th>
                </tr>
                <tr>
                    <td>Hadir</td>
                    <td style="text-align: center;">{{ $total_hadir }} Hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td style="text-align: center;">{{ $total_izin }} Hari</td>
                </tr>
                <tr>
                    <td>Sakit</td>
                    <td style="text-align: center;">{{ $total_sakit }} Hari</td>
                </tr>
                <tr>
                    <td>Cuti</td>
                    <td style="text-align: center;">{{ $total_cuti }} Hari</td>
                </tr>
                <tr>
                    <td>Alfa</td>
                    <td style="text-align: center;">{{ $total_alfa }} Hari</td>
                </tr>
                <tr>
                    <td>Total Denda Terlambat</td>
                    <td style="text-align: right; color:red; font-weight:bold;">{{ formatAngka($total_denda) }}</td>
                </tr>
                <tr>
                    <td>Total Potongan Jam</td>
                    <td style="text-align: right; color:red; font-weight:bold;">{{ $total_potongan_jam }} Jam</td>
                </tr>
            </table>
        </div>
    </section>
</body>
</html>