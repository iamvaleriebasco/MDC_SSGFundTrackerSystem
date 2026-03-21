<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – SSG Fund Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* ── Left Panel — School Image ───────────────────────────── */
        .image-panel {
            width: 50%;
            position: relative;
            overflow: hidden;
        }

        .image-panel .bg-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            background: linear-gradient(160deg, #0d2447 0%, #1a3a6b 40%, #2e6da4 75%, #4a90d9 100%);
        }

        .image-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(10, 24, 50, 0.55) 0%,
                rgba(10, 24, 50, 0.30) 50%,
                rgba(10, 24, 50, 0.72) 100%
            );
        }

        .image-overlay-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 2.5rem;
            color: #fff;
        }

        .image-overlay-text .school-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 30px;
            padding: .35rem .9rem;
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: .4px;
            margin-bottom: 1rem;
        }

        .image-overlay-text h2 {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: .6rem;
            text-shadow: 0 2px 8px rgba(0,0,0,.3);
        }

        .image-overlay-text p {
            font-size: .88rem;
            opacity: .8;
            line-height: 1.6;
            max-width: 340px;
        }

        .image-top-logo {
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .image-top-logo .logo-box {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            overflow: hidden;
        }

        .image-top-logo .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }

        .image-top-logo .logo-text { color: #fff; }
        .image-top-logo .logo-text .name { font-size: .9rem; font-weight: 700; line-height: 1.1; }
        .image-top-logo .logo-text .sub  { font-size: .7rem; opacity: .7; }

        /* ── Right Panel ─────────────────────────────────────────── */
        .form-panel {
            width: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
        }

        .form-inner {
            width: 100%;
            max-width: 380px;
        }

        .form-header { margin-bottom: 1.75rem; }

        .form-header .welcome {
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: #2e6da4;
            margin-bottom: .4rem;
        }

        .form-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a3a6b;
            margin-bottom: .3rem;
        }

        .form-header p { font-size: .85rem; color: #6b7280; }

        .divider {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #1a3a6b, #2e6da4);
            border-radius: 2px;
            margin: .75rem 0 1.5rem;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .35rem;
        }

        .input-group-custom { position: relative; }

        .input-group-custom .input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .95rem;
            z-index: 2;
        }

        .input-group-custom .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: .65rem .9rem .65rem 2.4rem;
            font-size: .88rem;
            color: #1f2937;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-group-custom .form-control:focus {
            border-color: #2e6da4;
            box-shadow: 0 0 0 3px rgba(46,109,164,.1);
            outline: none;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #1a3a6b 0%, #2e6da4 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .72rem;
            font-size: .95rem;
            font-weight: 600;
            letter-spacing: .3px;
            transition: opacity .2s, transform .1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .btn-register:hover  { opacity: .92; transform: translateY(-1px); }
        .btn-register:active { transform: translateY(0); }

        .alert {
            border: none;
            border-radius: 10px;
            font-size: .83rem;
            padding: .65rem .9rem;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; }
            .image-panel { width: 100%; height: 220px; flex-shrink: 0; }
            .form-panel  { width: 100%; padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>

    <!-- Left: School Image -->
    <div class="image-panel">
        <img src="{{ asset('images/school.jpg') }}" alt="School Campus"
             class="bg-photo" onerror="this.style.display='none'">

        <div class="image-top-logo">
            <div class="logo-box">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                     onerror="this.outerHTML='<i class=\'bi bi-bank\' style=\'color:#fff;font-size:1.1rem;\'></i>'">
            </div>
            <div class="logo-text">
                <div class="name">SSG Fund Tracker</div>
                <div class="sub">Supreme Student Government</div>
            </div>
        </div>

        <div class="image-overlay-text">
            <div class="school-badge">
                <i class="bi bi-mortarboard-fill"></i>
                Student Financial Management
            </div>
            <h2>Transparent.<br>Accountable.<br>Organized.</h2>
            <p>The official fund tracking system of the MDC Supreme Student Government.</p>
        </div>
    </div>

    <!-- Right: Register Form -->
    <div class="form-panel">
        <div class="form-inner">

            <div class="form-header">
                <div class="welcome">Get started</div>
                <h1>Create an account</h1>
                <p>Register to access the SSG Fund Tracker.</p>
                <div class="divider"></div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <div class="input-group-custom">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Juan dela Cruz"
                               required autofocus>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="text" name="email" class="form-control"
                               value="{{ old('email') }}"
                               placeholder="Enter your email"
                               required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" class="form-control"
                               placeholder="Min. 8 characters" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Re-enter password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="bi bi-person-plus"></i>
                    Create Account
                </button>
            </form>

            <div class="text-center mt-1" style="font-size:.83rem; color:#6b7280;">
                Already have an account?
                <a href="{{ route('login') }}" style="color:#2e6da4; font-weight:600; text-decoration:none;">
                    Sign in
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
