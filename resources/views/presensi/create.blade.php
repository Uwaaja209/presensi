@extends('layouts.mobile.app')
@section('content')
    <style>
        /* === BASE STYLES (MOBILE FIRST) === */
        /* Aturan dasar yang berlaku untuk semua ukuran layar, terutama mobile */
        #header-section { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; }
        #content-section { margin-top: 45px; padding: 0 !important; position: relative; z-index: 1; height: calc(100vh - 45px); overflow: hidden; }
        #facedetection { height: 100%; width: 100% !important; margin: 0 !important; }
        .webcam-capture { width: 100% !important; height: 100% !important; margin: 0 !important; padding: 0 !important; border-radius: 0; overflow: hidden; position: relative; }
        .webcam-capture video { width: 100% !important; height: 90% !important; margin: 0 !important; padding: 0 !important; border-radius: 0; object-fit: cover; transform: scaleX(-1); -webkit-transform: scaleX(-1); }
        #map-loading { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; text-align: center; }
        .fab { position: fixed; bottom: 90px; left: 50%; transform: translateX(-50%); width: 56px; height: 56px; border-radius: 50%; background-color: #007bff; color: white; border: none; display: flex; justify-content: center; align-items: center; font-size: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 1050; transition: all 0.3s ease; }
        .fab:active { transform: translateX(-50%) scale(0.95); }
        .bottom-sheet { position: fixed; bottom: 50px; left: 0; width: 100%; z-index: 1040; background: #ffffff; border-top-left-radius: 24px; border-top-right-radius: 24px; padding: 20px; box-shadow: 0 -5px 15px rgba(0,0,0,0.08); transition: transform 0.3s ease-out; transform: translateY(100%); }
        .bottom-sheet.is-visible { transform: translateY(0); }
        .fab.is-raised { transform: translateY(-350px) translateX(-50%); }
        .fab.is-raised:active { transform: translateY(-350px) translateX(-50%) scale(0.95); }
        #map { position: relative !important; width: 100% !important; height: 120px !important; margin-bottom: 20px; border-radius: 12px; }
        .info-card-presensi { position: relative !important; width: 100% !important; padding: 0 !important; box-shadow: none !important; background-color: transparent !important; }
        #face-verification-text { margin-top: 8px !important; font-weight: 500; font-size: 13px; }
        #face-verification-text.verified { color: #28a745; }
        #face-verification-text.unverified { color: #dc3545; }
        .info-card-presensi .shift-section, .info-card-presensi .links-section { display: flex; }
        .info-card-presensi .shift-section { justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; padding: 15px 0; }
        .info-card-presensi .links-section { justify-content: space-around; align-items: center; padding-top: 15px; }
        .info-card-presensi .shift-info .shift-name { font-size: 16px; font-weight: 600; color: #333; margin: 0; }
        .info-card-presensi .shift-info .shift-hours { font-size: 14px; color: #888; margin: 4px 0 0 0; }
        .additional-link { display: flex; flex-direction: column; align-items: center; gap: 6px; text-decoration: none; color: #465985; font-size: 13px; font-weight: 500; }
        .additional-link ion-icon { font-size: 22px; }

        /* === DESKTOP OVERRIDES (Layar Lebar) === */
        /* Aturan ini HANYA aktif di layar 769px ke atas */
        @media (min-width: 769px) {
            #facedetection { padding: 25px; }
            .webcam-capture, .webcam-capture video { border-radius: 15px !important; }
            .bottom-sheet { position: static !important; transform: none !important; background: transparent !important; box-shadow: none !important; padding: 0 !important; border-radius: 0 !important; width: 100% !important; height: 100% !important; }
            #map { position: absolute !important; top: 45px !important; left: 45px !important; width: 35% !important; max-width: 400px; height: 180px !important; z-index: 20; opacity: 0.95; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); }
            .info-card-presensi { position: absolute !important; top: 45px !important; right: 45px !important; left: auto !important; width: 320px !important; z-index: 20; background-color: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border-radius: 12px !important; padding: 20px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; display: flex; flex-direction: column; gap: 20px; }
            .info-card-presensi .shift-section { padding: 15px 0 !important; }
            .fab, #listcabang { display: none !important; }
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
        <button class="fab" id="toggleBottomSheet">
            <ion-icon name="chevron-up-outline"></ion-icon>
        </button>

        <div class="row" style="margin-top: 0; height: 100%;">
            <div class="col" id="facedetection">
                <div class="webcam-capture"></div>
                <div class="bottom-sheet">
                    <div id="map" style="height: 120px">
                        <div id="map-loading">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2">Memuat peta...</div>
                        </div>
                    </div>
                    <div class="info-card-presensi">
                        <div class="header-info">
                            <div class="time-section"><h1 id="jam-display"></h1></div>
                            <p id="tanggal-display"></p>
                            <p id="face-verification-text" class="unverified">Posisikan wajah di depan kamera</p>
                        </div>
                        <div class="shift-section">
                            <div class="shift-info">
                                <p class="shift-name">{{ $jam_kerja->nama_jam_kerja }}</p>
                                <p class="shift-hours">{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }} - {{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</p>
                            </div>
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
            </div>
        </div>
    </div>
    
    {{-- Elemen Audio untuk Notifikasi --}}
    <audio id="notifikasi_absenmasuk"><source src="{{ asset('assets/sound/absenmasuk.wav') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_absenpulang"><source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg"></audio>
@endsection

@push('myscript')
    <script type="text/javascript">
        window.onload = function() { jam(); }
        function jam() {
            var e = document.getElementById('jam-display'),
                tanggal_el = document.getElementById('tanggal-display'),
                d = new Date(), h, m, s;
            h = d.getHours(); m = set(d.getMinutes()); s = set(d.getSeconds());
            if(e) { e.innerHTML = (h < 10 ? '0' : '') + h + ':' + m + ':' + s; }
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            if(tanggal_el){ tanggal_el.innerHTML = d.toLocaleDateString('id-ID', options); }
            setTimeout(jam, 1000);
        }
        function set(e) { e = e < 10 ? '0' + e : e; return e; }
    </script>
    <script>
        $(function() {
            // === VARIABEL GLOBAL ===
            let lokasi;
            let lokasi_cabang = "{{ $lokasi_kantor->lokasi_cabang }}";
            let map;
            let faceRecognition = "{{ $general_setting->face_recognition }}";
            let isSubmitting = false;

            // === AUDIO NOTIFIKASI ===
            let notifikasi_absenmasuk = document.getElementById('notifikasi_absenmasuk');
            let notifikasi_absenpulang = document.getElementById('notifikasi_absenpulang');

            // === LOGIKA TOMBOL BOTTOM SHEET ===
            const toggleBtn = document.getElementById('toggleBottomSheet');
            const bottomSheet = document.querySelector('.bottom-sheet');
            const btnIcon = toggleBtn.querySelector('ion-icon');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    bottomSheet.classList.toggle('is-visible');
                    toggleBtn.classList.toggle('is-raised');
                    
                    if (bottomSheet.classList.contains('is-visible')) {
                        btnIcon.setAttribute('name', 'chevron-down-outline');
                    } else {
                        btnIcon.setAttribute('name', 'chevron-up-outline');
                    }
                });
            }

            // === INISIALISASI WEBCAM & PETA ===
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            function initWebcam() { 
                Webcam.set({
                    height: isMobile ? 360 : 480, width: isMobile ? 480 : 640,
                    image_format: 'jpeg', jpeg_quality: 90,
                    constraints: { facingMode: "user" }
                });
                Webcam.attach('.webcam-capture');
            }
            initWebcam();

            if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(successCallback, errorCallback, { enableHighAccuracy: true }); }
            function successCallback(position) {
                try {
                    lokasi = position.coords.latitude + "," + position.coords.longitude;
                    map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                    var lok = lokasi_cabang.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
                    L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                    L.circle([lat_kantor, long_kantor], { color: 'red', fillColor: '#f03', fillOpacity: 0.5, radius: radius }).addTo(map);
                    $('#map-loading').hide();
                    setTimeout(() => map.invalidateSize(), 500);
                } catch (error) {
                    $('#map-loading').html('Gagal memuat peta.');
                }
            }
            function errorCallback(error) { $('#map-loading').html('Gagal dapat lokasi. Cek izin.'); }
            
            // === FUNGSI SUBMIT PRESENSI ===
            function submitPresensi(status) {
                if (isSubmitting) return; 
                isSubmitting = true;

                Swal.fire({
                    title: 'Wajah Terverifikasi!', text: 'Mengirim data presensi ' + status + '...',
                    allowOutsideClick: false, didOpen: () => Swal.showLoading()
                });

                Webcam.snap(function(uri) { 
                    let status_presensi = status == 'masuk' ? '1' : '2';
                    $.ajax({
                        type: 'POST', url: "{{ route('presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}", image: uri, status: status_presensi,
                            lokasi: lokasi, lokasi_cabang: lokasi_cabang,
                            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                (status == 'masuk' ? notifikasi_absenmasuk : notifikasi_absenpulang).play();
                                Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 2500, showConfirmButton: false })
                                    .then(() => window.location.href = '/dashboard');
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message })
                                    .then(() => isSubmitting = false);
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan.' });
                            isSubmitting = false;
                        }
                    });
                });
            }

            // === LOGIKA FACE RECOGNITION ===
            if (faceRecognition == 1) {
                const loadingIndicator = $(`<div id="face-recognition-loading" style="position: absolute; z-index: 100;"><div class="text-light" style="background-color: rgba(0,0,0,0.5); padding: 10px; border-radius: 8px;">Memuat model AI...</div></div>`);
                $('#facedetection').append(loadingIndicator);

                Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]).then(() => startFaceRecognition()).catch(err => loadingIndicator.html(`<div class="text-danger">Gagal memuat model.</div>`));

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
                                    const img = await faceapi.fetchImage(`/storage/uploads/facerecognition/${label}/${faceData.wajah}?t=${timestamp}`);
                                    const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                                    if (detections) descriptions.push(detections.descriptor);
                                } catch (e) { console.error(`Gagal load gambar: ${e}`); }
                            }
                            return new faceapi.LabeledFaceDescriptors(label, descriptions);
                        })
                    );
                }

                async function startFaceRecognition() {
                    loadingIndicator.remove();
                    const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                    if(labeledFaceDescriptors[0].descriptors.length === 0){
                        $('#face-verification-text').text('Data wajah tidak ditemukan. Hubungi HRD.').addClass('unverified');
                        return;
                    }
                    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5);
                    const video = document.querySelector('.webcam-capture video');
                    const faceVerificationTextElement = $('#face-verification-text');
                    let consecutiveMatches = 0;
                    const requiredConsecutiveMatches = 3; 
                    
                    function updateCanvas() {
                        if (isSubmitting || !video || video.readyState < 3) {
                            if (!isSubmitting) requestAnimationFrame(updateCanvas);
                            return;
                        }

                        faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor().then(detection => {
                            if (detection) {
                                const match = faceMatcher.findBestMatch(detection.descriptor);
                                if (match.label.includes("unknown")) {
                                    consecutiveMatches = 0;
                                    faceVerificationTextElement.text('Wajah Tidak Dikenali').removeClass('verified').addClass('unverified');
                                } else {
                                    consecutiveMatches++;
                                    faceVerificationTextElement.text(`Memverifikasi... (${consecutiveMatches}/${requiredConsecutiveMatches})`).removeClass('unverified').addClass('verified');

                                    if (consecutiveMatches >= requiredConsecutiveMatches && !isSubmitting) {
                                        @if (!$presensi || $presensi->jam_in === null)
                                            submitPresensi('masuk');
                                        @elseif ($presensi->jam_in !== null && $presensi->jam_out === null)
                                            submitPresensi('pulang');
                                        @endif
                                    }
                                }
                            } else {
                                consecutiveMatches = 0;
                                faceVerificationTextElement.text('Posisikan Wajah di Depan Kamera').removeClass('verified').addClass('unverified');
                            }
                        }).catch(err => console.error("Error deteksi wajah:", err));
                        
                        if(!isSubmitting) requestAnimationFrame(updateCanvas);
                    }
                    updateCanvas();
                }
            }
        });
    </script>
@endpush