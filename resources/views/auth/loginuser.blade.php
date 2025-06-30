<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in & Sign up Form</title>
    <link rel="stylesheet" href="{{ asset('assets/login/css/style.css') }}" />
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            animation: slideIn 0.5s ease-out;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Gaya baru untuk Tombol dan Kontainer Scanner */
        .qr-scanner-toggle {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #555;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .qr-scanner-toggle:hover {
            color: #000;
            text-decoration: underline;
        }

        #qr-scanner-section {
            display: none; /* Sembunyi secara default */
            text-align: center;
            padding: 0 1rem;
        }
        
        #qr-reader {
            width: 100%;
            max-width: 400px;
            margin: 1rem auto;
            border: 5px solid #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>
    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <!-- Form Login dengan Username & Password -->
                    <div id="password-login-section">
                        <form id="formAuthentication" class="sign-in-form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="logo">
                                <img src="{{ asset('assets/login/images/logoweb-1.png') }}" alt="easyclass" />
                                <h4>E-PRESENSI V2</h4>
                            </div>

                            <div class="heading">
                                <h2>Welcome Back</h2>
                            </div>

                            <div id="scan-success-alert" style="display: none;" class="alert alert-success"></div>

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif

                            <div class="actual-form">
                                <div class="input-wrap">
                                    <input id="id_user_input" type="text" minlength="4" class="input-field" name="id_user" value="{{ old('id_user') }}" autocomplete="off" placeholder="Username / Email" required />
                                </div>
                                <div class="input-wrap">
                                    <input id="password_input" type="password" minlength="4" name="password" class="input-field" autocomplete="off" placeholder="Password" required />
                                </div>
                                <div class="checkbox-wrap">
                                    <input type="checkbox" id="remember" name="remember" style="margin-right: 8px; width: 16px; height: 16px;">
                                    <label for="remember" style="color: #666; font-size: 14px; cursor: pointer; margin-left: 20px;">Remember Me</label>
                                </div>
                                <input type="submit" value="Sign In" class="sign-btn" />
                                
                                <a href="#" id="qr-scanner-toggle" class="qr-scanner-toggle">atau Login dengan Pindai QR</a>
                            </div>
                        </form>
                    </div>

                    <!-- Area untuk Menampilkan Scanner QR (Awalnya tersembunyi) -->
                    <div id="qr-scanner-section">
                        <div class="logo">
                            <img src="{{ asset('assets/login/images/logoweb-1.png') }}" alt="easyclass" />
                            <h4>Pindai QR Code</h4>
                        </div>
                        <div class="heading">
                            <h6>Arahkan QR Code Karyawan ke Kamera</h6>
                        </div>
                        <div id="qr-reader"></div>
                        <a href="#" id="password-login-toggle" class="qr-scanner-toggle">atau Login dengan Password</a>
                    </div>
                </div>

                <div class="carousel">
                    {{-- Carousel Anda tidak perlu diubah --}}
                </div>
            </div>
        </div>
    </main>

    <!-- Javascript file -->
    <script src="{{ asset('assets/login/script/app.js') }}"></script>
    <!-- Library untuk Scan QR Code -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordSection = document.getElementById('password-login-section');
        const qrSection = document.getElementById('qr-scanner-section');
        const qrScannerToggle = document.getElementById('qr-scanner-toggle');
        const passwordLoginToggle = document.getElementById('password-login-toggle');
        const idUserInput = document.getElementById('id_user_input');
        const passwordInput = document.getElementById('password_input');
        const scanSuccessAlert = document.getElementById('scan-success-alert');
        const loginForm = document.getElementById('formAuthentication');

        let html5QrCode = null;
        let isScanSuccess = false;

        qrScannerToggle.addEventListener('click', (e) => {
            e.preventDefault();
            isScanSuccess = false; 
            passwordSection.style.display = 'none';
            qrSection.style.display = 'block';
            startScanner();
        });

        passwordLoginToggle.addEventListener('click', (e) => {
            e.preventDefault();
            stopScanner();
            qrSection.style.display = 'none';
            passwordSection.style.display = 'block';
        });

        function startScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
            
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                if (isScanSuccess) {
                    return; 
                }
                isScanSuccess = true;

                console.log(`Scan result: ${decodedText}`, decodedResult);
                
                stopScanner();

                try {
                    const parts = decodedText.split('|');
                    const userPart = parts[0].split(':');
                    const passPart = parts[1].split(':');

                    if (userPart.length > 1 && passPart.length > 1 && userPart[0].trim() === 'user' && passPart[0].trim() === 'password') {
                        const username = userPart[1];
                        const password = passPart[1];

                        idUserInput.value = username;
                        passwordInput.value = password;
                        
                        // Kembali ke form login untuk menampilkan pesan
                        qrSection.style.display = 'none';
                        passwordSection.style.display = 'block';
                        
                        // Tampilkan pesan bahwa login otomatis sedang berjalan
                        // scanSuccessAlert.textContent = `QR Code untuk ${username} berhasil dipindai. Melakukan login otomatis...`;
                        scanSuccessAlert.style.display = 'block';
                        
                        // ======================================================================
                        // PERUBAHAN: Otomatis submit form setelah jeda singkat
                        // ======================================================================
                        setTimeout(() => {
                            loginForm.submit();
                        }, 1000); // Jeda 1 detik agar pesan terbaca

                    } else {
                        handleScanError("Format QR Code tidak valid.");
                    }
                } catch (err) {
                    handleScanError("Gagal mem-parsing QR Code. Pastikan formatnya benar.");
                }
            };

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch(err => {
                    console.log(`Unable to start scanning, error: ${err}`);
                    alert('Gagal memulai kamera. Pastikan Anda memberikan izin.');
                });
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(ignore => {
                    console.log("QR Code scanning stopped.");
                }).catch(err => {
                    console.log("Scanner already stopped or failed to stop.");
                });
            }
        }

        function handleScanError(message) {
            alert(message);
            isScanSuccess = false; 
        }
    });
    </script>
</body>

</html>
