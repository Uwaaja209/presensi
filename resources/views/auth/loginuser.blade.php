<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Page - E-Presensi V2</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/vuexy/app-assets//images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/vuexy/app-assets//images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//vendors/css/vendors.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vuexy/app-assets//css/pages/page-auth.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <!-- END: Custom CSS-->

    <style>
        /* Gaya tambahan untuk area scanner */
        #qr-scanner-section {
            display: none; /* Sembunyi secara default */
        }
        #qr-reader {
            width: 100%;
            max-width: 300px; /* Sesuaikan ukuran scanner */
            margin: 1rem auto;
            border: 5px solid #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="auth-wrapper auth-v2">
                    <div class="auth-inner row m-0">
                        <!-- Brand logo-->
                        <a class="brand-logo" href="javascript:void(0);">
                            <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" height="28">
                                <!-- SVG Logo Anda -->
                            </svg>
                            <h2 class="brand-text text-primary ml-1">E-Presensi</h2>
                        </a>
                        <!-- /Brand logo-->

                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                                <img class="img-fluid" src="{{ asset('assets/vuexy/app-assets//images/pages/login-v2.svg') }}" alt="Login V2" />
                            </div>
                        </div>
                        <!-- /Left Text-->

                        <!-- Login-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                
                                <!-- ====================================================== -->
                                <!-- BAGIAN LOGIN DENGAN PASSWORD -->
                                <!-- ====================================================== -->
                                <div id="password-login-section">
                                    <h2 class="card-title font-weight-bold mb-1">Selamat Datang! 👋</h2>
                                    <p class="card-text mb-2">Silakan login untuk memulai sesi Anda</p>
                                    
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
                                    <div id="scan-success-alert" style="display: none;" class="alert alert-success"></div>

                                    <form class="auth-login-form mt-2" id="formAuthentication" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label" for="id_user_input">Username</label>
                                            <input class="form-control" id="id_user_input" type="text" name="id_user" placeholder="xxxxxx" autofocus="" tabindex="1" required />
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="password_input">Password</label>
                                            </div>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="password_input" type="password" name="password" placeholder="············" tabindex="2" required />
                                                <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="remember-me" name="remember" type="checkbox" tabindex="3" />
                                                <label class="custom-control-label" for="remember-me"> Remember Me</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-block" tabindex="4">Sign in</button>
                                    </form>
                                    <div class="divider my-2">
                                        <div class="divider-text">atau</div>
                                    </div>
                                    <div class="auth-footer-btn d-flex justify-content-center">
                                        <a class="btn btn-info" id="qr-scanner-toggle" href="#"><i data-feather="grid"></i> Login dengan Pindai QR</a>
                                    </div>
                                </div>

                                <!-- ====================================================== -->
                                <!-- BAGIAN LOGIN DENGAN SCANNER QR -->
                                <!-- ====================================================== -->
                                <div id="qr-scanner-section">
                                    <h2 class="card-title font-weight-bold mb-1">Pindai QR Code</h2>
                                    <p class="card-text mb-2">Arahkan QR Code Karyawan ke Kamera</p>
                                    <div id="qr-reader"></div>
                                    <div class="divider my-2">
                                        <div class="divider-text">atau</div>
                                    </div>
                                    <a class="btn btn-outline-secondary btn-block" id="password-login-toggle" href="#">Login dengan Password</a>
                                </div>

                            </div>
                        </div>
                        <!-- /Login-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('assets/vuexy/app-assets//vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('assets/vuexy/app-assets//vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('assets/vuexy/app-assets//js/core/app-menu.js') }}"></script>
    <script src="{{ asset('assets/vuexy/app-assets//js/core/app.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('assets/vuexy/app-assets//js/scripts/pages/page-auth-login.js') }}"></script>
    <!-- END: Page JS-->

    <!-- Library untuk Scan QR Code -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // ======================================================================
        // PERBAIKAN: Gunakan $(function() { ... }) untuk memastikan jQuery siap
        // ======================================================================
        $(function() {
            'use strict';

            // Logika Feather Icons dari template
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }

            // Logika untuk QR Scanner
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
                    if (isScanSuccess) return;
                    isScanSuccess = true;

                    console.log(`Scan result: ${decodedText}`);

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

                            qrSection.style.display = 'none';
                            passwordSection.style.display = 'block';

                            scanSuccessAlert.textContent = `QR Code untuk ${username} berhasil dipindai. Melakukan login otomatis...`;
                            scanSuccessAlert.style.display = 'block';

                            setTimeout(() => {
                                loginForm.submit();
                            }, 1000);

                        } else {
                            handleScanError("Format QR Code tidak valid.");
                        }
                    } catch (err) {
                        handleScanError("Gagal mem-parsing QR Code. Pastikan formatnya benar.");
                    }
                };

                const config = {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                };

                html5QrCode.start({
                        facingMode: "environment"
                    }, config, qrCodeSuccessCallback)
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
<!-- END: Body-->
</html>
