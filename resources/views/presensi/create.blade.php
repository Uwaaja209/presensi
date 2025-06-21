@extends('layouts.mobile.app')
@section('content')
    {{-- <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }
    </style> --}}
    <style>
        .webcam-capture {
            display: inline-block;
            width: 100% !important;
            margin: 0 !important;
            margin-top: 40px !important;
            margin-bottom: 90px !important;
            padding: 10px !important;
            height: calc(100vh - 120px) !important;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            border-radius: 15px;
            object-fit: cover;
        }

        #map {
            height: 120px;
            width: 50%;
            position: absolute;
            top: 55px;
            left: 20px;
            z-index: 10;
            opacity: 0.8;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        canvas {
            position: absolute;
            border-radius: 0;
            box-shadow: none;
        }

        #facedetection {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 100%;
            margin: 0 !important;
            /* Menghapus margin */
            padding: 0 !important;
            /* Menghapus padding */
            width: 100% !important;
            /* Memastikan lebar penuh */
        }

        /* Tambahkan style untuk indikator loading maps */
        #map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
        }

        /* Perbaikan untuk posisi content-section */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 45px;
            padding: 0 !important;
            /* Menghapus padding */
            position: relative;
            z-index: 1;
            height: calc(100vh - 45px);
            overflow: hidden;
        }

        /* Style untuk tombol scan */
        .scan-buttons {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            z-index: 20;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 0 10px;
        }

        #listcabang {
            position: absolute;
            bottom: 100px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            z-index: 20;
        }

        #listcabang .select-wrapper {
            position: relative;
            width: 90%;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 5px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        #listcabang .select-wrapper::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>');
            background-repeat: no-repeat;
            background-position: center;
            pointer-events: none;
        }

        #listcabang select {
            width: 100%;
            height: 45px;
            border-radius: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            padding: 0 15px 0 45px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        #listcabang select:hover {
            background-color: rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        #listcabang select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            background-color: rgba(0, 0, 0, 0.6);
            animation: pulse 1.5s infinite;
        }

        #listcabang select option {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
        }

        /* Tambahkan arrow icon kustom */
        #listcabang .select-wrapper::after {
            content: "";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
            background-repeat: no-repeat;
            background-position: center;
            pointer-events: none;
        }

        .scan-button {
            height: 45px !important;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            width: 42%;
        }

        .scan-button ion-icon {
            margin-right: 5px;
        }

        /* TAMBAHKAN CSS BARU INI */
.info-card-presensi {
    position: absolute;
    top: 55px;
    left: 75%;
    right: 15px;
    z-index: 20;
    background-color: #ffffff;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-card-presensi .header-info {
    text-align: center;
}

.info-card-presensi .time-section {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.info-card-presensi .time-section ion-icon {
    font-size: 28px;
    color: #333;
}

#jam-display {
    font-size: 38px;
    font-weight: 700;
    color: #212529;
    margin: 0;
    line-height: 1;
}

#tanggal-display {
    font-size: 16px;
    color: #6c757d;
    margin: 5px 0 0 0;
}
/* Aturan untuk layar dengan lebar maksimum 768px (Tablet dan Ponsel) */
@media (max-width: 768px) {
    /* ================================================================== */
    /* BLOK CSS MEDIA QUERY YANG DIPERBARUI UNTUK MOBILE                 */
    /* ================================================================== */
    
    /* 1. Panel Bawah (Bottom Sheet) */
    .bottom-sheet {
        position: fixed; /* Menggunakan fixed agar selalu di bawah layar */
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 20;
        background: #ffffff;
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        padding: 20px 20px 85px 20px; /* Padding bawah 85px untuk memberi ruang bagi tombol "Masuk" */
        box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease-out;
    }

    /* 2. Atur Ulang Peta agar tidak 'melayang' */
    #map {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        width: 100% !important;
        height: 120px !important;
        margin-bottom: 20px;
        border-radius: 12px;
    }

    /* 3. Atur Ulang Kartu Info agar tidak 'melayang' */
    .info-card-presensi {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        width: 100% !important;
        box-shadow: none !important;
        padding: 0 !important;
        background-color: transparent !important;
        border-radius: 0 !important;
    }
}
/* Style untuk teks verifikasi wajah (menggunakan ID lama) */
#face-verification-text {
    margin-top: 8px !important;
    font-weight: 500;
    font-size: 13px;
}
#face-verification-text.verified {
    color: #28a745; /* Hijau */
}
#face-verification-text.unverified {
    color: #dc3545; /* Merah */
}

.info-card-presensi .shift-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    padding: 15px 0;
}

.info-card-presensi .shift-info .shift-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.info-card-presensi .shift-info .shift-hours {
    font-size: 14px;
    color: #888;
    margin: 4px 0 0 0;
}

.check-in-button-card {
    background-color: #465985;
    color: white;
    padding: 10px 18px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
}

.check-in-button-card:hover {
    background-color: #3b4a6b;
    color: white;
}

.check-out-button-card {
    background-color:rgb(133, 70, 70);
    color: white;
    padding: 10px 18px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
}

.check-out-button-card:hover {
    background-color:rgb(107, 59, 59);
    color: white;
}

.info-card-presensi .links-section {
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.additional-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: #465985;
    font-size: 13px;
    font-weight: 500;
}

.additional-link ion-icon {
    font-size: 22px;
}

        /* Style modern untuk box deteksi wajah */
        .face-detection-box {
            border: 2px solid #4CAF50;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
            transition: all 0.3s ease;
        }

        .face-detection-box.unknown {
            border-color: #F44336;
            box-shadow: 0 0 10px rgba(244, 67, 54, 0.5);
        }

        .face-detection-label {
            background-color: rgba(76, 175, 80, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .face-detection-label.unknown {
            background-color: rgba(244, 67, 54, 0.8);
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="javascript:;" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">E-Presensi</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 0; height: 100%;">
            <div class="col" id="facedetection">
                <div class="webcam-capture"></div>
                <!-- <div id="map">
                    <div id="map-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="mt-2">Memuat peta...</div>
                    </div>
                </div> -->
                <div class="bottom-sheet">
                {{-- Peta sekarang ada di dalam bottom-sheet --}}
                <div id="map" style="height: 120px">
                    <div id="map-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading.</span>
                        </div>
                        <div class="mt-2">Memuat peta...</div>
                    </div>
                </div>

                {{-- Kartu Info sekarang juga ada di dalam bottom-sheet --}}
                <div class="info-card-presensi">
                    {{-- Bagian Jam dan Tanggal --}}
                    <div class="header-info">
                        <div class="time-section">
                            <!-- <ion-icon name="time-outline"></ion-icon> -->
                            <h1 id="jam-display"></h1>
                        </div>
                        <p id="tanggal-display"></p>
                        <p id="face-verification-text" class="unverified"></p>
                    </div>

                    {{-- Bagian Jadwal & Tombol Aksi --}}
                    <div class="shift-section">
                        <div class="shift-info">
                            <p class="shift-name">{{ $jam_kerja->nama_jam_kerja }}</p>
                            <p class="shift-hours">{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }} - {{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</p>
                        </div>
                        <!-- @if (!$presensi || $presensi->jam_in === null)
                        <a href="#" class="check-in-button-card" onclick="document.getElementById('absenmasuk').click(); return false;">
                            Check-in <ion-icon name="chevron-forward-outline"></ion-icon>
                        </a>
                        @else
                        <a href="#" class="check-in-button-card" style="background-color: #6c757d;" onclick="document.getElementById('absenpulang').click(); return false;">
                            Check-out <ion-icon name="chevron-forward-outline"></ion-icon>
                        </a>
                        @endif -->

                        @if (!$presensi || $presensi->jam_in === null)
                        {{-- Jika tidak ada data presensi untuk hari ini, atau jam_in masih null, tampilkan tombol Masuk --}}
                        <button class="btn check-in-button-card" id="absenmasuk" statuspresensi="masuk">
                            <span style="font-size:14px">Masuk</span>
                        </button>
                         @elseif ($presensi->jam_in !== null && $presensi->jam_out === null)
                        {{-- Jika sudah absen masuk (jam_in ada) tapi belum absen pulang (jam_out null), tampilkan tombol Pulang --}}
                        <button class="btn check-out-button-card" id="absenpulang" statuspresensi="pulang">
                            <span style="font-size:14px">Pulang</span>
                        </button>
                       
                        @endif
                    </div>

                    {{-- Bagian Link Tambahan --}}
                    <div class="links-section">
                        <a href="{{ route('presensi.histori') }}" class="additional-link">
                            <ion-icon name="document-text-outline"></ion-icon>
                            <span>Riwayat Kehadiran</span>
                        </a>

                        <a href="{{ route('presensi.jadwalkerja') }}" class="additional-link">
                            <ion-icon name="calendar-outline"></ion-icon>
                            <span>Jadwal Kerja</span>
                        </a>
                    </div>
            </div>
        </div>
                <!-- <div class="jam-digital-malasngoding">
                    <p>{{ DateToIndo(date('Y-m-d')) }}</p>
                    <p id="jam"></p>
                    <p>{{ $jam_kerja->nama_jam_kerja }} </p>
                    <p style="display: flex; justify-content:space-between">
                    <span>{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }} - {{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</span>
                    </p>
                   
                    <span id="face-verification-text"></span>
                </div> -->
               
               <div id="listcabang" style="display: none;">
                    <div class="select-wrapper">
                        <select name="cabang" id="cabang" class="form-control">
                            @foreach ($cabang as $item)
                                <option {{ $item->kode_cabang == $karyawan->kode_cabang ? 'selected' : '' }} value="{{ $item->lokasi_cabang }}">
                                    {{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
              <!-- <div class="scan-buttons">
                    @if (!$presensi || $presensi->jam_in === null)
                        {{-- Jika tidak ada data presensi untuk hari ini, atau jam_in masih null, tampilkan tombol Masuk --}}
                        <button class="btn btn-success bg-primary scan-button" id="absenmasuk" statuspresensi="masuk">
                            <span style="font-size:14px">Masuk</span>
                        </button>
                    @elseif ($presensi->jam_in !== null && $presensi->jam_out === null)
                        {{-- Jika sudah absen masuk (jam_in ada) tapi belum absen pulang (jam_out null), tampilkan tombol Pulang --}}
                        <button class="btn btn-danger scan-button" id="absenpulang" statuspresensi="pulang">
                            <span style="font-size:14px">Pulang</span>
                        </button>
                    @endif
                </div> -->
            </div>
        </div>
    </div>
    <audio id="notifikasi_radius">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_mulaiabsen">
        <source src="{{ asset('assets/sound/mulaiabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_akhirabsen">
        <source src="{{ asset('assets/sound/akhirabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_sudahabsen">
        <source src="{{ asset('assets/sound/sudahabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenmasuk">
        <source src="{{ asset('assets/sound/absenmasuk.wav') }}" type="audio/mpeg">
    </audio>


    <!--Pulang-->
    <audio id="notifikasi_sudahabsenpulang">
        <source src="{{ asset('assets/sound/sudahabsenpulang.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenpulang">
        <source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg">
    </audio>
@endsection
@push('myscript')
    <script type="text/javascript">
        // Fungsi yang dijalankan ketika halaman selesai dimuat
        window.onload = function() {
            // Memanggil fungsi jam() untuk menampilkan waktu secara real-time
            jam();
        }

        // Fungsi untuk menampilkan waktu secara real-time
       function jam() {
    var e = document.getElementById('jam-display'),
        tanggal_el = document.getElementById('tanggal-display'),
        d = new Date(),
        h, m, s;
    
    h = d.getHours();
    m = set(d.getMinutes());
    s = set(d.getSeconds());

    // Update tampilan jam di elemen baru
    if(e) {
        e.innerHTML = h + ':' + m + ':' + s;
    }

    // Update tampilan tanggal dengan format Indonesia
    const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
    if(tanggal_el){
        tanggal_el.innerHTML = d.toLocaleDateString('id-ID', options);
    }
    
    setTimeout(jam, 1000);
}

// Fungsi ini tidak perlu diubah
function set(e) {
    e = e < 10 ? '0' + e : e;
    return e;
}

        // Fungsi untuk menambahkan '0' di depan angka jika kurang dari 10
        function set(e) {
            // Jika angka kurang dari 10, tambahkan '0' di depan
            e = e < 10 ? '0' + e : e;
            // Mengembalikan angka yang telah ditambahkan '0' di depan jika perlu
            return e;
        }
    </script>
    <script>
        // Fungsi yang dijalankan ketika dokumen siap
        $(function() {
            // Variabel untuk menampung lokasi
            let lokasi;
            // Variabel untuk menampung lokasi user
            let lokasi_user;
            let lokasi_cabang = document.getElementById('cabang').value;
            // Variabel map global
            let map;
            // alert(lokasi_cabang);
            // Mengambil elemen HTML dengan id 'notifikasi_radius'
            let notifikasi_radius = document.getElementById('notifikasi_radius');
            // Mengambil elemen HTML dengan id 'notifikasi_mulaiabsen'
            let notifikasi_mulaiabsen = document.getElementById('notifikasi_mulaiabsen');
            // Mengambil elemen HTML dengan id 'notifikasi_akhirabsen'
            let notifikasi_akhirabsen = document.getElementById('notifikasi_akhirabsen');
            // Mengambil elemen HTML dengan id 'notifikasi_sudahabsen'
            let notifikasi_sudahabsen = document.getElementById('notifikasi_sudahabsen');
            // Mengambil elemen HTML dengan id 'notifikasi_absenmasuk'
            let notifikasi_absenmasuk = document.getElementById('notifikasi_absenmasuk');

            // Mengambil elemen HTML dengan id 'notifikasi_sudahabsenpulang'
            let notifikasi_sudahabsenpulang = document.getElementById('notifikasi_sudahabsenpulang');
            // Mengambil elemen HTML dengan id 'notifikasi_absenpulang'
            let notifikasi_absenpulang = document.getElementById('notifikasi_absenpulang');

            // Variabel untuk menampung status face recognition
            let faceRecognitionDetected = 0; // Inisialisasi variabel face recognition detected
            // Mengambil nilai face recognition dari variabel $general_setting->face_recognition
            let faceRecognition = "{{ $general_setting->face_recognition }}";

            // Deteksi perangkat mobile
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator
                .userAgent);

            // Fungsi untuk inisialisasi webcam
            function initWebcam() {
                // Inisialisasi webcam dengan pengaturan yang sesuai
                Webcam.set({
                    // Tinggi webcam
                    height: isMobile ? 360 : 480,
                    // Lebar webcam
                    width: isMobile ? 480 : 640,
                    // Format gambar
                    image_format: 'jpeg',
                    // Kualitas gambar
                    jpeg_quality: isMobile ? 85 : 95,
                    // Frame rate
                    fps: isMobile ? 25 : 30,
                    // Konstrain untuk video
                    constraints: {
                        video: {
                            // Lebar ideal
                            width: {
                                ideal: isMobile ? 480 : 640
                            },
                            // Tinggi ideal
                            height: {
                                ideal: isMobile ? 360 : 480
                            },
                            // Menggunakan kamera depan
                            facingMode: "user",
                            // Frame rate ideal
                            frameRate: {
                                ideal: isMobile ? 25 : 30
                            }
                        }
                    }
                });

                // Menghubungkan webcam ke elemen HTML dengan class 'webcam-capture'
                Webcam.attach('.webcam-capture');

                // Tambahkan event listener untuk memastikan webcam berjalan setelah refresh
                Webcam.on('load', function() {
                    console.log('Webcam loaded successfully');
                });

                // Tambahkan event listener untuk menangani error
                Webcam.on('error', function(err) {
                    console.error('Webcam error:', err);
                    // Coba inisialisasi ulang webcam jika terjadi error
                    setTimeout(initWebcam, 1000);
                });
            }

            // Inisialisasi webcam
            initWebcam();

            // Tambahkan event listener untuk visibility change
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    // Jika halaman menjadi visible, cek apakah webcam perlu diinisialisasi ulang
                    if (!Webcam.isInitialized()) {
                        console.log('Reinitializing webcam after visibility change');
                        initWebcam();
                    }
                }
            });


            // Tampilkan Map
            if (navigator.geolocation) {
                // Menggunakan geolocation untuk mendapatkan lokasi saat ini
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            }

            // Fungsi untuk memuat peta

            // Fungsi yang dijalankan ketika geolocation berhasil
            function successCallback(position) {
                try {
                    // Membuat objek map
                    //alert(position.coords.latitude + "," + position.coords.longitude);
                    map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                    //alert(position.coords.latitude + "," + position.coords.longitude);
                    // Mengambil lokasi kantor dari variabel $lokasi_kantor->lokasi_cabang
                    var lokasi_kantor = lokasi_cabang;
                    // Mengambil lokasi saat ini
                    lokasi = position.coords.latitude + "," + position.coords.longitude;
                    // Memisahkan lokasi kantor menjadi latitude dan longitude
                    var lok = lokasi_kantor.split(",");
                    // Mengambil latitude kantor
                    var lat_kantor = lok[0];
                    // Mengambil longitude kantor
                    var long_kantor = lok[1];
                    console.log(position.coords.latitude + "," + position.coords.longitude);
                    // Mengambil radius dari variabel $lokasi_kantor->radius_cabang
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";

                    // Menambahkan lapisan peta
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        // Maksimum zoom
                        maxZoom: 19,
                        // Atribusi
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    // Menambahkan marker untuk lokasi saat ini
                    var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                    // Menambahkan lingkaran untuk radius
                    var circle = L.circle([lat_kantor, long_kantor], {
                        // Warna lingkaran
                        color: 'red',
                        // Warna isi lingkaran
                        fillColor: '#f03',
                        // Opasitas isi lingkaran
                        fillOpacity: 0.5,
                        // Radius lingkaran
                        radius: radius
                    }).addTo(map);

                    // Sembunyikan indikator loading setelah peta dimuat
                    document.getElementById('map-loading').style.display = 'none';

                    // Pastikan peta diperbarui setelah dimuat
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 500);
                } catch (error) {
                    console.error("Error initializing map:", error);
                    document.getElementById('map-loading').style.display = 'none';
                }
            }

            // Fungsi yang dijalankan ketika geolocation gagal
            function errorCallback(error) {
                console.error("Error getting geolocation:", error);
                document.getElementById('map-loading').innerHTML = 'Gagal mendapatkan lokasi. Silakan cek izin lokasi.';

                // Coba inisialisasi peta dengan lokasi cabang default
                try {
                    var lok = lokasi_cabang.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];

                    // Inisialisasi peta dengan lokasi cabang
                    map = L.map('map').setView([lat_kantor, long_kantor], 18);

                    // Tambahkan tile layer
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    // Tambahkan lingkaran radius
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";
                    var circle = L.circle([lat_kantor, long_kantor], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: radius
                    }).addTo(map);

                    document.getElementById('map-loading').style.display = 'none';
                } catch (mapError) {
                    console.error("Error initializing map:", mapError);
                }
            }

            // Jika face recognition diaktifkan
              if (faceRecognition == 1) {
                // Tambahkan indikator loading dengan styling yang lebih baik
                const loadingIndicator = document.createElement('div');
                loadingIndicator.id = 'face-recognition-loading';
                loadingIndicator.innerHTML = `
                    <div class="spinner-border text-light" role="status">
                        <span class="sr-only">Memuat pengenalan wajah...</span>
                    </div>
                    <div class="mt-2 text-light">Memuat model pengenalan wajah...</div>
                `;
                loadingIndicator.style.position = 'absolute';
                loadingIndicator.style.top = '50%';
                loadingIndicator.style.left = '50%';
                loadingIndicator.style.transform = 'translate(-50%, -50%)';
                loadingIndicator.style.zIndex = '1000';
                loadingIndicator.style.textAlign = 'center';
                document.getElementById('facedetection').appendChild(loadingIndicator);

                // Preload model di background
                const modelLoadingPromise = Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]);

                // Mulai pengenalan wajah setelah model dimuat
                modelLoadingPromise.then(() => {
                    document.getElementById('face-recognition-loading').remove();
                    startFaceRecognition();
                }).catch(err => {
                    console.error("Error loading models:", err);
                    document.getElementById('face-recognition-loading').remove();
                    // Coba muat ulang model jika terjadi error
                    setTimeout(() => {
                        console.log('Retrying to load face recognition models');
                        modelLoadingPromise.then(() => {
                            startFaceRecognition();
                        });
                    }, 2000);
                });

                async function getLabeledFaceDescriptions() {
                    const labels = [
                        "{{ $karyawan->nik }}-{{ getNamaDepan(strtolower($karyawan->nama_karyawan)) }}"
                    ];
                    let namakaryawan;
                    let jmlwajah = "{{ $wajah == 0 ? 1 : $wajah }}";

                    // Tambahkan indikator loading untuk memuat data wajah
                    const faceDataLoading = document.createElement('div');
                    faceDataLoading.id = 'face-data-loading';
                    faceDataLoading.innerHTML = `
                        <div class="spinner-border text-light" role="status">
                            <span class="sr-only">Memuat data wajah...</span>
                        </div>
                        <div class="mt-2 text-light">Memuat data wajah...</div>
                    `;
                    faceDataLoading.style.position = 'absolute';
                    faceDataLoading.style.top = '50%';
                    faceDataLoading.style.left = '50%';
                    faceDataLoading.style.transform = 'translate(-50%, -50%)';
                    faceDataLoading.style.zIndex = '1000';
                    faceDataLoading.style.textAlign = 'center';
                    document.getElementById('facedetection').appendChild(faceDataLoading);

                    try {
                        // Tambahkan timestamp untuk mencegah cache
                        const timestamp = new Date().getTime();
                        const response = await fetch(`/facerecognition/getwajah?t=${timestamp}`);
                        const data = await response.json();
                        console.log('Data wajah yang diterima:', data);

                        const result = await Promise.all(
                            labels.map(async (label) => {
                                const descriptions = [];
                                let validFaceFound = false;

                                // Proses setiap data wajah yang diterima
                                for (const faceData of data) {
                                    try {
                                        console.log('Memproses data wajah:', faceData);
                                        console.log('NIK:', faceData.nik);
                                        console.log('Nama file wajah:', faceData.wajah);

                                        // Cek keberadaan file foto wajah terlebih dahulu
                                        const checkImage = async (label, wajahFile) => {
                                            try {
                                                // Tambahkan timestamp untuk mencegah cache
                                                const imagePath =
                                                    `/storage/uploads/facerecognition/${label}/${wajahFile}?t=${timestamp}`;
                                                console.log('Mencoba mengakses file:',
                                                    imagePath);

                                                const response = await fetch(imagePath);
                                                if (!response.ok) {
                                                    console.warn(
                                                        `File foto wajah ${wajahFile} tidak ditemukan untuk ${label}`
                                                    );
                                                    return null;
                                                }
                                                console.log('File wajah berhasil diakses:',
                                                    imagePath);
                                                return await faceapi.fetchImage(imagePath);
                                            } catch (err) {
                                                console.error(
                                                    `Error checking image ${wajahFile} for ${label}:`,
                                                    err);
                                                return null;
                                            }
                                        };

                                        // Gunakan nilai dari key wajah sebagai nama file
                                        const img = await checkImage(label, faceData.wajah);

                                        if (img) {
                                            try {
                                                console.log('Memulai deteksi wajah untuk file:',
                                                    faceData.wajah);
                                                // Deteksi wajah dengan SSD MobileNet dan threshold yang lebih seimbang
                                                const detections = await faceapi.detectSingleFace(
                                                        img, new faceapi.SsdMobilenetv1Options({
                                                            minConfidence: 0.5
                                                        }))
                                                    .withFaceLandmarks()
                                                    .withFaceDescriptor();

                                                if (detections) {
                                                    console.log(
                                                        'Wajah berhasil dideteksi dan descriptor dibuat'
                                                    );
                                                    descriptions.push(detections.descriptor);
                                                    validFaceFound = true;
                                                }
                                            } catch (err) {
                                                console.error(
                                                    `Error processing image ${faceData.wajah} for ${label}:`,
                                                    err);
                                            }
                                        }
                                    } catch (err) {
                                        console.error(`Error processing face data:`, err);
                                    }
                                }

                                if (!validFaceFound) {
                                    console.warn(`Tidak ditemukan wajah valid untuk ${label}`);
                                    namakaryawan = "unknown";
                                } else {
                                    namakaryawan = label;
                                }

                                return new faceapi.LabeledFaceDescriptors(namakaryawan,
                                    descriptions);
                            })
                        );

                        // Hapus indikator loading setelah data wajah dimuat
                        document.getElementById('face-data-loading').remove();
                        return result;
                    } catch (error) {
                        console.error('Error dalam getLabeledFaceDescriptions:', error);
                        document.getElementById('face-data-loading').remove();
                        throw error;
                    }
                }

                async function startFaceRecognition() {
                    try {
                        const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

                        const video = document.querySelector('.webcam-capture video');

                        if (!video || !video.readyState) {
                            console.log('Video not ready, waiting...');
                            setTimeout(startFaceRecognition, 1000);
                            return;
                        }

                        const canvas = faceapi.createCanvasFromMedia(video);
                        canvas.style.display = 'none'; // HIDDEN: Hide the canvas entirely, as we are not drawing on it anymore
                        document.getElementById('facedetection').appendChild(canvas);

                        const ctx = canvas.getContext("2d"); // Keep ctx defined, though not used for drawing box

                        const displaySize = {
                            width: video.videoWidth,
                            height: video.videoHeight
                        };
                        faceapi.matchDimensions(canvas, displaySize);

                        let lastDetectionTime = 0;
                        const detectionInterval = isMobile ? 200 : 100;
                        let isProcessing = false;
                        let consecutiveMatches = 0;
                        const requiredConsecutiveMatches = 2;

                        const faceVerificationTextElement = document.getElementById('face-verification-text'); // GET THE NEW TEXT ELEMENT

                        async function detectFaces() {
                            try {
                                const detection = await faceapi.detectSingleFace(video, new faceapi
                                        .SsdMobilenetv1Options({
                                            minConfidence: 0.6
                                        }))
                                    .withFaceLandmarks()
                                    .withFaceDescriptor();

                                return detection ? [detection] : [];
                            } catch (error) {
                                console.error("Error dalam deteksi wajah:", error);
                                return [];
                            }
                        }

                        function updateCanvas() {
                            if (!isProcessing) {
                                const now = Date.now();
                                if (now - lastDetectionTime > detectionInterval) {
                                    isProcessing = true;
                                    lastDetectionTime = now;

                                    detectFaces()
                                        .then(detections => {
                                            const resizedDetections = faceapi.resizeResults(detections,
                                                displaySize);
                                            // ctx.clearRect(0, 0, canvas.width, canvas.height); // COMMENTED: Clear canvas not needed if canvas is hidden

                                            faceRecognitionDetected = 0; // Reset status deteksi

                                            if (resizedDetections.length > 0) {
                                                resizedDetections.forEach((detection) => {
                                                    if (detection.descriptor) {
                                                        const match = faceMatcher.findBestMatch(
                                                            detection.descriptor);

                                                        const isUnknown = match.toString().includes("unknown");
                                                        const isNotRecognized = match.distance > 0.55;

                                                        let labelText;

                                                        if (isUnknown || isNotRecognized) {
                                                            labelText = 'Wajah Tidak Dikenali';
                                                            consecutiveMatches = 0;
                                                            faceVerificationTextElement.className = 'unverified'; // Set class for styling
                                                        } else {
                                                            labelText = "{{ $karyawan->nama_karyawan }} Terverifikasi";
                                                            consecutiveMatches++;
                                                            if (consecutiveMatches >= requiredConsecutiveMatches) {
                                                                faceRecognitionDetected = 1;
                                                                faceVerificationTextElement.className = 'verified'; // Set class for styling
                                                            }
                                                        }

                                                        // --- START: REMOVED / COMMENTED OUT CANVAS DRAWING CODE ---
                                                        // All drawing related to the box and "Posisikan wajah" text are removed.
                                                        // This means lines like ctx.strokeStyle, ctx.beginPath, ctx.stroke, ctx.fillText, etc. are gone.
                                                        // --- END: REMOVED / COMMENTED OUT CANVAS DRAWING CODE ---

                                                        // NEW: Update the text element
                                                        faceVerificationTextElement.innerText = labelText;
                                                        faceVerificationTextElement.style.display = 'block'; // Show the text
                                                    }
                                                });
                                            } else {
                                                // No face detected
                                                faceVerificationTextElement.innerText = 'Wajah Tidak Terdeteksi';
                                                faceVerificationTextElement.className = 'unverified';
                                                faceVerificationTextElement.style.display = 'block'; // Show the text
                                                consecutiveMatches = 0;
                                            }

                                            isProcessing = false;
                                        })
                                        .catch(err => {
                                            console.error("Error dalam deteksi wajah:", err);
                                            isProcessing = false;
                                        });
                                }
                            }

                            requestAnimationFrame(updateCanvas);
                        }

                        // Mulai loop animasi
                        updateCanvas();
                    } catch (error) {
                        console.error("Error starting face recognition:", error);
                        // Coba inisialisasi ulang face recognition jika terjadi error
                        setTimeout(() => {
                            console.log('Retrying face recognition initialization');
                            startFaceRecognition();
                        }, 2000);
                    }
                }
            }

            $("#absenmasuk").click(function() {
                // alert(lokasi);
                $("#absenmasuk").prop('disabled', true);
                $("#absenpulang").prop('disabled', true);
                $("#absenmasuk").html(
                    '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading.</span></div> <span style="font-size:16px">Loading.</span>'

                );
                let status = '1';
                Webcam.snap(function(uri) {
                    image = uri;
                });

                // alert(faceRecognitionDetected);
                // return false;
                if (faceRecognitionDetected == 0 && faceRecognition == 1) {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Wajah tidak terdeteksi',
                        didClose: function() {
                            $("#absenmasuk").prop('disabled', false);
                            $("#absenpulang").prop('disabled', false);
                            $("#absenmasuk").html(
                                '<span style="font-size:14px">Masuk</span>'
                            );
                        }
                    })
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            image: image,
                            status: status,
                            lokasi: lokasi,
                            lokasi_cabang: lokasi_cabang,
                            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                        },
                        success: function(data) {
                            if (data.status == true) {
                                notifikasi_absenmasuk.play();
                                swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(function() {
                                    window.location.href = '/dashboard';
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON.notifikasi == "notifikasi_radius") {
                                notifikasi_radius.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_mulaiabsen") {
                                notifikasi_mulaiabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_akhirabsen") {
                                notifikasi_akhirabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_sudahabsen") {
                                notifikasi_sudahabsen.play();
                            }
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                didClose: function() {
                                    $("#absenmasuk").prop('disabled', false);
                                    $("#absenpulang").prop('disabled', false);
                                    $("#absenmasuk").html(
                                        '<span style="font-size:14px">Masuk</span>'
                                    );
                                }

                            });
                        }
                    });
                }

            });

            $("#absenpulang").click(function() {
                // alert(lokasi);
                $("#absenmasuk").prop('disabled', true);
                $("#absenpulang").prop('disabled', true);
                $("#absenpulang").html(
                    '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading.</span></div> <span style="font-size:16px">Loading.</span>'

                );
                let status = '2';
                Webcam.snap(function(uri) {
                    image = uri;
                });
                if (faceRecognitionDetected == 0 && faceRecognition == 1) {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Wajah tidak terdeteksi',
                        didClose: function() {
                            $("#absenmasuk").prop('disabled', false);
                            $("#absenpulang").prop('disabled', false);
                            $("#absenmasuk").html(
                                '<span style="font-size:14px">Masuk</span>'
                            );
                        }
                    })
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            image: image,
                            status: status,
                            lokasi: lokasi,
                            lokasi_cabang: lokasi_cabang,
                            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                        },
                        success: function(data) {
                            if (data.status == true) {
                                notifikasi_absenpulang.play();
                                swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(function() {
                                    window.location.href = '/dashboard';
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON.notifikasi == "notifikasi_radius") {
                                notifikasi_radius.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_mulaiabsen") {
                                notifikasi_mulaiabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_akhirabsen") {
                                notifikasi_akhirabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_sudahabsen") {
                                notifikasi_sudahabsenpulang.play();
                            }
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                didClose: function() {
                                    $("#absenmasuk").prop('disabled', false);
                                    $("#absenpulang").prop('disabled', false);
                                    $("#absenpulang").html(
                                        '<span style="font-size:14px">Pulang</span>'
                                    );
                                }

                            });
                        }
                    });
                }
            });

            $("#cabang").change(function() {
                // Ambil nilai lokasi cabang yang dipilih
                lokasi_cabang = $(this).val();
                console.log("Lokasi cabang berubah: " + lokasi_cabang);

                // Ambil teks cabang yang dipilih
                let cabangText = $("#cabang option:selected").text();

                // Tampilkan notifikasi cabang berubah
                swal.fire({
                    icon: 'info',
                    title: 'Lokasi Berubah',
                    text: 'Lokasi cabang berubah menjadi: ' + cabangText,
                    showConfirmButton: false,
                    timer: 2000
                });

                // Jika lokasi cabang berubah, reload peta
                if (typeof map !== 'undefined' && map !== null) {
                    map.remove(); // Hapus peta sebelumnya
                }

                // Tampilkan indikator loading
                document.getElementById('map-loading').style.display = 'block';

                try {
                    // Buat array dari string lokasi
                    var lok = lokasi_cabang.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];

                    // Inisialisasi peta baru dengan lokasi cabang yang dipilih


                    // Jika geolocation tersedia, tambahkan marker lokasi user
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            // Update lokasi user
                            lokasi = position.coords.latitude + "," + position.coords.longitude;
                            map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);

                            // Tambahkan tile layer
                            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);
                            // Tambahkan marker untuk lokasi user
                            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

                            // Tambahkan lingkaran radius
                            var radius = "{{ $lokasi_kantor->radius_cabang }}";
                            var circle = L.circle([lat_kantor, long_kantor], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: radius
                            }).addTo(map);

                            // Sembunyikan indikator loading
                            document.getElementById('map-loading').style.display = 'none';
                        }, function(error) {
                            // Tangani error geolocation
                            console.error("Error getting geolocation:", error);

                            // Tambahkan lingkaran radius tanpa marker user
                            var radius = "{{ $lokasi_kantor->radius_cabang }}";
                            var circle = L.circle([lat_kantor, long_kantor], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: radius
                            }).addTo(map);

                            // Sembunyikan indikator loading
                            document.getElementById('map-loading').style.display = 'none';

                            // Tampilkan pesan error
                            document.getElementById('map-loading').innerHTML =
                                'Gagal mendapatkan lokasi. Silakan cek izin lokasi.';
                            document.getElementById('map-loading').style.display = 'block';
                            setTimeout(function() {
                                document.getElementById('map-loading').style.display = 'none';
                            }, 3000);
                        });
                    } else {
                        // Jika geolocation tidak didukung
                        // Tambahkan lingkaran radius tanpa marker user
                        var radius = "{{ $lokasi_kantor->radius_cabang }}";
                        var circle = L.circle([lat_kantor, long_kantor], {
                            color: 'red',
                            fillColor: '#f03',
                            fillOpacity: 0.5,
                            radius: radius
                        }).addTo(map);

                        // Sembunyikan indikator loading
                        document.getElementById('map-loading').style.display = 'none';

                        // Tampilkan pesan error
                        document.getElementById('map-loading').innerHTML = 'Geolokasi tidak didukung oleh perangkat ini.';
                        document.getElementById('map-loading').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('map-loading').style.display = 'none';
                        }, 3000);
                    }
                } catch (error) {
                    console.error("Error initializing map:", error);
                    document.getElementById('map-loading').innerHTML = 'Gagal memuat peta. Silakan coba lagi.';
                    document.getElementById('map-loading').style.display = 'block';
                    setTimeout(function() {
                        document.getElementById('map-loading').style.display = 'none';
                    }, 3000);
                }
            });
        });
    </script>
@endpush
