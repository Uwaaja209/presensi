@extends('layouts.mobile.app')
@section('content')
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
            padding: 0 !important;
            width: 100% !important;
        }

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
            position: relative;
            z-index: 1;
            height: calc(100vh - 45px);
            overflow: hidden;
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
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
            70% { box-shadow: 0 0 0 5px rgba(255, 255, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
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

        .info-card-presensi .header-info { text-align: center; }
        .info-card-presensi .time-section { display: flex; align-items: center; justify-content: center; gap: 10px; }
        .info-card-presensi .time-section ion-icon { font-size: 28px; color: #333; }
        #jam-display { font-size: 38px; font-weight: 700; color: #212529; margin: 0; line-height: 1; }
        #tanggal-display { font-size: 16px; color: #6c757d; margin: 5px 0 0 0; }

        @media (max-width: 768px) {
            .bottom-sheet {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                z-index: 20;
                background: #ffffff;
                border-top-left-radius: 24px;
                border-top-right-radius: 24px;
                padding: 20px 20px 20px 20px;
                box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.08);
                transition: transform 0.3s ease-out;
            }
            #map {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                width: 100% !important;
                height: 120px !important;
                margin-bottom: 20px;
                border-radius: 12px;
            }
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
        #face-verification-text {
            margin-top: 8px !important;
            font-weight: 500;
            font-size: 13px;
        }
        #face-verification-text.verified { color: #28a745; /* Hijau */ }
        #face-verification-text.unverified { color: #dc3545; /* Merah */ }

        .info-card-presensi .shift-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 0;
        }
        .info-card-presensi .shift-info .shift-name { font-size: 16px; font-weight: 600; color: #333; margin: 0; }
        .info-card-presensi .shift-info .shift-hours { font-size: 14px; color: #888; margin: 4px 0 0 0; }

        .info-card-presensi .links-section {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .additional-link { display: flex; flex-direction: column; align-items: center; gap: 6px; text-decoration: none; color: #465985; font-size: 13px; font-weight: 500; }
        .additional-link ion-icon { font-size: 22px; }
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
                <div class="bottom-sheet">
                    <div id="map" style="height: 120px">
                        <div id="map-loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading.</span>
                            </div>
                            <div class="mt-2">Memuat peta...</div>
                        </div>
                    </div>

                    <div class="info-card-presensi">
                        <div class="header-info">
                            <div class="time-section">
                                <h1 id="jam-display"></h1>
                            </div>
                            <p id="tanggal-display"></p>
                            <p id="face-verification-text" class="unverified">Posisikan wajah di depan kamera</p>
                        </div>

                        <div class="shift-section">
                            <div class="shift-info">
                                <p class="shift-name">{{ $jam_kerja->nama_jam_kerja }}</p>
                                <p class="shift-hours">{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }} - {{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</p>
                            </div>
                            
                            {{-- ====================================================== --}}
                            {{-- Tombol Absen/Pulang Manual Dinonaktifkan Sesuai Request --}}
                            {{-- ====================================================== --}}
                            {{-- 
                            @if (!$presensi || $presensi->jam_in === null)
                                <button class="btn check-in-button-card" id="absenmasuk" statuspresensi="masuk">
                                    <span style="font-size:14px">Masuk</span>
                                </button>
                            @elseif ($presensi->jam_in !== null && $presensi->jam_out === null)
                                <button class="btn check-out-button-card" id="absenpulang" statuspresensi="pulang">
                                    <span style="font-size:14px">Pulang</span>
                                </button>
                            @endif 
                            --}}
                        </div>

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
            </div>
        </div>
    </div>
    
    {{-- Elemen Audio untuk Notifikasi --}}
    <audio id="notifikasi_radius"><source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_mulaiabsen"><source src="{{ asset('assets/sound/mulaiabsen.wav') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_akhirabsen"><source src="{{ asset('assets/sound/akhirabsen.wav') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_sudahabsen"><source src="{{ asset('assets/sound/sudahabsen.wav') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_absenmasuk"><source src="{{ asset('assets/sound/absenmasuk.wav') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_sudahabsenpulang"><source src="{{ asset('assets/sound/sudahabsenpulang.mp3') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_absenpulang"><source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg"></audio>
@endsection

@push('myscript')
    <script type="text/javascript">
        // Fungsi jam() dan set() tetap sama, tidak perlu diubah
        window.onload = function() { jam(); }
        function jam() {
            var e = document.getElementById('jam-display'),
                tanggal_el = document.getElementById('tanggal-display'),
                d = new Date(), h, m, s;
            h = d.getHours(); m = set(d.getMinutes()); s = set(d.getSeconds());
            if(e) { e.innerHTML = h + ':' + m + ':' + s; }
            const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            if(tanggal_el){ tanggal_el.innerHTML = d.toLocaleDateString('id-ID', options); }
            setTimeout(jam, 1000);
        }
        function set(e) { e = e < 10 ? '0' + e : e; return e; }
    </script>
    <script>
        $(function() {
            // ==================================================================
            // VARIABEL-VARIABEL GLOBAL
            // ==================================================================
            let lokasi;
            let lokasi_cabang = document.getElementById('cabang').value;
            let map;
            let faceRecognition = "{{ $general_setting->face_recognition }}";
            let isSubmitting = false; // Flag untuk mencegah submit ganda

            // Inisialisasi notifikasi audio
            let notifikasi_radius = document.getElementById('notifikasi_radius');
            let notifikasi_mulaiabsen = document.getElementById('notifikasi_mulaiabsen');
            let notifikasi_akhirabsen = document.getElementById('notifikasi_akhirabsen');
            let notifikasi_sudahabsen = document.getElementById('notifikasi_sudahabsen');
            let notifikasi_absenmasuk = document.getElementById('notifikasi_absenmasuk');
            let notifikasi_sudahabsenpulang = document.getElementById('notifikasi_sudahabsenpulang');
            let notifikasi_absenpulang = document.getElementById('notifikasi_absenpulang');

            // ==================================================================
            // FUNGSI INISIALISASI WEBCAM & PETA (Tidak diubah)
            // ==================================================================
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            function initWebcam() { 
                Webcam.set({
                    height: isMobile ? 360 : 480,
                    width: isMobile ? 480 : 640,
                    image_format: 'jpeg',
                    jpeg_quality: isMobile ? 85 : 95,
                    constraints: {
                        facingMode: "user"
                    }
                });
                Webcam.attach('.webcam-capture');
            }
            initWebcam();
            if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(successCallback, errorCallback); }
            function successCallback(position) {
                try {
                    map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                    lokasi = position.coords.latitude + "," + position.coords.longitude;
                    var lok = lokasi_cabang.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);
                    var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                    var circle = L.circle([lat_kantor, long_kantor], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: radius
                    }).addTo(map);
                    document.getElementById('map-loading').style.display = 'none';
                    setTimeout(function() { map.invalidateSize(); }, 500);
                } catch (error) {
                    console.error("Error initializing map:", error);
                    document.getElementById('map-loading').innerHTML = 'Gagal memuat peta.';
                }
            }
            function errorCallback(error) { 
                console.error("Error getting geolocation:", error);
                document.getElementById('map-loading').innerHTML = 'Gagal mendapatkan lokasi. Silakan cek izin lokasi.';
            }
            
            // ==================================================================
            // FUNGSI BARU UNTUK SUBMIT PRESENSI OTOMATIS
            // ==================================================================
            function submitPresensi(status) {
                if (isSubmitting) return; 
                isSubmitting = true;

                // Tampilkan pesan loading
                Swal.fire({
                    title: 'Wajah Terverifikasi!',
                    text: 'Sedang mengirim data presensi ' + status + '...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                let image;
                Webcam.snap(function(uri) { 
                    image = uri; 

                    let status_presensi = status == 'masuk' ? '1' : '2';

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            image: image,
                            status: status_presensi,
                            lokasi: lokasi,
                            lokasi_cabang: lokasi_cabang,
                            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                        },
                        success: function(data) {
                            if (data.status == true) {
                                if (status == 'masuk') { notifikasi_absenmasuk.play(); } 
                                else { notifikasi_absenpulang.play(); }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => { 
                                    window.location.href = '/dashboard'; 
                                });
                            } else {
                                // Penanganan jika dari sisi server validasi gagal (misal: diluar jam pulang)
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                }).then(() => {
                                    isSubmitting = false; // Izinkan mencoba lagi
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan, silakan coba lagi.'
                            });
                            isSubmitting = false; // Izinkan mencoba lagi
                        }
                    });
                });
            }

            // ==================================================================
            // LOGIKA FACE RECOGNITION (DENGAN MODIFIKASI UNTUK AUTO SUBMIT)
            // ==================================================================
            if (faceRecognition == 1) {
                // Tampilkan loading saat model face-api dimuat
                const loadingIndicator = document.createElement('div');
                loadingIndicator.id = 'face-recognition-loading';
                loadingIndicator.innerHTML = `<div class="text-light" style="background-color: rgba(0,0,0,0.5); padding: 10px; border-radius: 8px;">Memuat model pengenalan wajah...</div>`;
                loadingIndicator.style.position = 'absolute';
                loadingIndicator.style.zIndex = '100';
                document.getElementById('facedetection').appendChild(loadingIndicator);

                Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]).then(() => {
                    document.getElementById('face-recognition-loading').remove();
                    startFaceRecognition();
                }).catch(err => { 
                    console.error("Error loading models:", err);
                    loadingIndicator.innerHTML = `<div class="text-danger">Gagal memuat model.</div>`;
                });

                async function getLabeledFaceDescriptions() {
                    const labels = ["{{ $karyawan->nik }}-{{ getNamaDepan(strtolower($karyawan->nama_karyawan)) }}"];
                    return Promise.all(
                        labels.map(async label => {
                            const descriptions = [];
                            const timestamp = new Date().getTime();
                            const response = await fetch(`/facerecognition/getwajah?t=${timestamp}`);
                            const data = await response.json();
                            for (const faceData of data) {
                                try {
                                    const imagePath = `/storage/uploads/facerecognition/${label}/${faceData.wajah}?t=${timestamp}`;
                                    const img = await faceapi.fetchImage(imagePath);
                                    const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                                    if (detections) {
                                        descriptions.push(detections.descriptor);
                                    }
                                } catch (e) {
                                    console.log(`Could not load image for ${label}: ${e}`);
                                }
                            }
                            return new faceapi.LabeledFaceDescriptors(label, descriptions);
                        })
                    );
                }

                async function startFaceRecognition() {
                    const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
                    const video = document.querySelector('.webcam-capture video');
                    const faceVerificationTextElement = document.getElementById('face-verification-text');
                    
                    let consecutiveMatches = 0;
                    const requiredConsecutiveMatches = 5; // Verifikasi stabil selama 5 frame
                    
                    function updateCanvas() {
                        // Jangan proses jika sudah submit atau video belum siap
                        if(isSubmitting || video.readyState < 3) {
                             if (!isSubmitting) requestAnimationFrame(updateCanvas);
                             return;
                        }

                        faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor().then(detection => {
                            if (detection) {
                                const match = faceMatcher.findBestMatch(detection.descriptor);
                                if (match.toString().includes("unknown") || match.distance > 0.5) {
                                    consecutiveMatches = 0;
                                    faceVerificationTextElement.innerText = 'Wajah Tidak Dikenali';
                                    faceVerificationTextElement.className = 'unverified';
                                } else {
                                    consecutiveMatches++;
                                    faceVerificationTextElement.innerText = `Memverifikasi... (${consecutiveMatches}/${requiredConsecutiveMatches})`;
                                    faceVerificationTextElement.className = 'verified';

                                    if (consecutiveMatches >= requiredConsecutiveMatches) {
                                        // =============================================
                                        // TRIGGER ABSENSI OTOMATIS
                                        // =============================================
                                        @if (!$presensi || $presensi->jam_in === null)
                                            submitPresensi('masuk');
                                        @elseif ($presensi->jam_in !== null && $presensi->jam_out === null)
                                            submitPresensi('pulang');
                                        @endif
                                        // =============================================
                                    }
                                }
                            } else {
                                consecutiveMatches = 0;
                                faceVerificationTextElement.innerText = 'Wajah Tidak Terdeteksi';
                                faceVerificationTextElement.className = 'unverified';
                            }
                        }).catch(err => {
                            console.error("Error dalam deteksi wajah:", err);
                        });
                        
                        if(!isSubmitting) {
                            requestAnimationFrame(updateCanvas);
                        }
                    }
                    updateCanvas();
                }
            }
            
            // Event listener untuk ganti cabang (tetap sama)
            $("#cabang").change(function() { 
                lokasi_cabang = $(this).val();
                if (typeof map !== 'undefined' && map !== null) { map.remove(); }
                document.getElementById('map-loading').style.display = 'block';
                if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(successCallback, errorCallback); }
            });
        });
    </script>
@endpush