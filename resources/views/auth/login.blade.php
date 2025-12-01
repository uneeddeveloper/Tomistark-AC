<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — CoolPro Service AC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg1: #e6f7ff;
            --bg2: #dff4ff;
            --bg3: #cfe9ff;
            --indigo: #2563eb;
            --blue: #3b82f6;
            --cyan: #06b6d4;
            --line: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Inter, system-ui, Segoe UI, Arial, sans-serif
        }

        body {
            min-height: 100vh;
            background: radial-gradient(1200px 900px at 10% 10%, #ffffffaa 0%, #ffffff00 60%),
                radial-gradient(1000px 700px at 90% 20%, #bff0ff66 0%, #ffffff00 55%),
                linear-gradient(140deg, var(--bg1), var(--bg2) 50%, var(--bg3));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-shell {
            width: 100%;
            max-width: 1200px;
            border-radius: 28px;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffffcc, #ffffffee);
            box-shadow: 0 30px 80px rgba(14, 51, 97, .18);
            display: grid;
            grid-template-columns: 430px 1fr;
            position: relative;
        }

        .auth-shell::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 28px;
            box-shadow: inset 0 0 0 1px #e8f3ff, inset 0 0 0 2px #ffffff55;
            pointer-events: none;
        }

        /* LEFT */
        .form-pane {
            background: #fff;
            padding: 36px;
            display: flex;
            flex-direction: column;
        }

        .tabs {
            display: flex;
            gap: 8px;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 4px;
            width: max-content;
            margin: 2px auto 18px;
        }

        .tabs .tab {
            padding: 10px 16px;
            font-weight: 700;
            font-size: 14px;
            border-radius: 10px;
            color: #64748b;
        }

        .tabs .tab.active {
            background: linear-gradient(90deg, var(--blue), var(--cyan));
            color: #fff;
            box-shadow: 0 6px 16px rgba(59, 130, 246, .35);
        }

        .brand-mini {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 16px
        }

        .ut-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #dff4ff, #bde7ff);
            box-shadow: inset 0 0 0 2px #fff, 0 6px 16px #86c3ff80;
        }

        .ut-logo svg {
            width: 24px;
            height: 24px
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.2px
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #64748b;
            margin-top: 4px
        }

        .msg {
            display: none;
            margin: 10px 0 14px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px
        }

        .msg.show {
            display: block
        }

        .msg.error {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #dc2626
        }

        .msg.ok {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #10b981
        }

        .form {
            margin-top: 22px
        }

        .field {
            margin-bottom: 16px
        }

        .label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 2px;
            text-transform: uppercase;
            letter-spacing: .4px
        }

        .input-wrap {
            position: relative
        }

        .icon-left {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa7b5
        }

        .input {
            width: 100%;
            padding: 14px 40px;
            border-radius: 12px;
            border: 1.6px solid var(--line);
            background: #f8fafc;
            font-size: 15px;
            color: #0f172a;
            transition: .2s ease;
        }

        .input:focus {
            outline: none;
            background: #fff;
            border-color: #8ec5ff;
            box-shadow: 0 0 0 4px #2b7fff14
        }

        .toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa7b5;
            cursor: pointer;
        }

        .row-opts {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0 18px
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 13px
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--blue)
        }

        .captcha {
            border: 1.6px solid var(--line);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
            background: #fff;
        }

        .captcha .box {
            width: 20px;
            height: 20px;
            border: 1.6px solid #cbd5e1;
            border-radius: 4px;
            background: #f8fafc
        }

        .captcha .txt {
            font-size: 13px;
            color: #0f172a
        }

        .terms {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 8px 2px 18px;
            font-size: 12px;
            color: #475569
        }

        .terms input {
            width: 16px;
            height: 16px;
            accent-color: var(--blue)
        }

        .terms a {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 700
        }

        .btn {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 0;
            cursor: pointer;
            font-weight: 800;
            font-size: 15px;
            color: #fff;
            background: linear-gradient(90deg, var(--blue), var(--indigo));
            box-shadow: 0 10px 24px rgba(37, 99, 235, .35);
            transition: transform .12s ease, box-shadow .12s ease;
        }

        .btn:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, .45)
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0 12px
        }

        .divider .line {
            flex: 1;
            height: 1px;
            background: #e2e8f0
        }

        .divider .text {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .4px;
            color: #94a3b8
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 13px 16px;
            border-radius: 12px;
            text-decoration: none;
            border: 1.6px solid var(--line);
            background: #fff;
            color: #0f172a;
            font-weight: 800;
            box-shadow: 0 6px 14px rgba(15, 23, 42, .04);
            transition: .15s;
        }

        .btn-google:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(15, 23, 42, .06);
            border-color: #cfe3ff
        }

        .btn-google svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px
        }

        /* RIGHT */
        .hero-pane {
            position: relative;
            overflow: hidden;
            background: radial-gradient(700px 420px at 70% 20%, #ffffff 0%, #ffffff00 60%),
                linear-gradient(135deg, #e7f7ff 0%, #d6efff 40%, #c7e3ff 100%);
            padding: 42px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(#cfe8ff33 1px, transparent 1px) 0 0/24px 24px,
                linear-gradient(90deg, #cfe8ff33 1px, transparent 1px) 0 0/24px 24px;
            pointer-events: none;
        }

        .hero-top {
            position: absolute;
            right: 28px;
            top: 24px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .hero-logo {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #dff7ff, #bde7ff);
            box-shadow: inset 0 0 0 2px #fff, 0 6px 18px #8fd8ff88;
        }

        .hero-logo svg {
            width: 28px;
            height: 28px
        }

        .welcome {
            text-align: center;
            max-width: 560px;
            margin: 0 auto 22px;
            color: #0b2948;
            line-height: 1.2
        }

        .welcome h2 {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: .2px
        }

        .welcome p {
            margin-top: 10px;
            color: #3b5b7a;
            font-size: 14px
        }

        .scene {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 24px;
            margin-top: 6px;
        }

        .monitor {
            width: 420px;
            max-width: 100%;
            filter: drop-shadow(0 30px 40px rgba(27, 94, 152, .18));
        }

        .phone {
            width: 160px;
            filter: drop-shadow(0 26px 30px rgba(27, 94, 152, .18));
        }

        .arrow {
            position: absolute;
            right: 58px;
            bottom: 92px;
            width: 140px;
            height: 140px;
            rotate: -12deg;
        }

        .arrow svg {
            width: 100%;
            height: 100%
        }

        @media (max-width: 980px) {
            .auth-shell {
                grid-template-columns: 1fr
            }

            .hero-pane {
                order: -1;
                padding: 28px
            }

            .welcome h2 {
                font-size: 22px
            }

            .monitor {
                width: 86%
            }

            .phone {
                width: 140px
            }

            .arrow {
                right: 24px;
                bottom: 36px;
                width: 120px;
                height: 120px
            }
        }
    </style>
</head>

<body>
    <div class="auth-shell">
        <!-- LEFT: LOGIN -->
        <div class="form-pane">
            <div class="tabs"><span class="tab active">Sign In</span></div>

            <div class="brand-mini">
                <div class="ut-logo" aria-hidden="true" title="CoolPro Service AC">
                    <!-- logo kipas/snowflake sederhana -->
                    <svg viewBox="0 0 64 64" fill="none">
                        <circle cx="32" cy="32" r="10" stroke="#22d3ee" stroke-width="4" />
                        <path d="M32 6v12M32 46v12M6 32h12M46 32h12M13 13l8 8M51 51l-8-8M51 13l-8 8M13 51l8-8"
                            stroke="#2563eb" stroke-width="4" stroke-linecap="round" />
                    </svg>
                </div>
                <div>
                    <div class="title">RommiStark Service AC</div>
                    <div class="subtitle">Masuk untuk kelola pesanan & jadwal teknisi</div>
                </div>
            </div>

            <!-- messages -->
            <div id="errorMessage" class="msg error"><i class="fa-solid fa-triangle-exclamation"></i> <span id="errorText">Email atau password salah</span></div>
            <div id="successMessage" class="msg ok"><i class="fa-solid fa-circle-check"></i> <span id="successText">Login berhasil!</span></div>

            <!-- form -->
            <form id="loginForm" action="/login" method="POST" class="form" novalidate>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="field">
                    <label class="label" for="login">Login / Email</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user icon-left"></i>
                        <input id="login" name="login" type="text" class="input" placeholder="contoh: pelanggan@coolpro.id" required />
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock icon-left"></i>
                        <input id="password" name="password" type="password" class="input" placeholder="••••••••" required minlength="6" />
                        <i id="togglePassword" class="fa-solid fa-eye toggle" title="Tampilkan/Sembunyikan"></i>
                    </div>
                </div>

                <button class="btn" type="submit"><i class="fa-solid fa-right-to-bracket" style="margin-right:8px"></i> Masuk</button>
            </form>

            <!-- Divider + Google Sign-in (di luar form agar tidak ikut submit) -->
            <div class="divider">
                <div class="line"></div>
                <div class="text">ATAU</div>
                <div class="line"></div>
            </div>

            <a class="btn-google" href="/auth/google/redirect" aria-label="Masuk dengan Google">
                <!-- Google 'G' -->
                <svg viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12 10.2v3.9h5.4c-.2 1.2-.9 2.9-2.6 4.1l4 3.1c2.3-2.1 3.6-5.1 3.6-8.7 0-.8-.1-1.7-.3-2.4H12z" />
                    <path fill="#34A853" d="M5.3 14.3l-.8.6-3.2 2.5C3 20.7 7.1 23 12 23c3.3 0 6.1-1.1 8.1-3l-4-3.1c-1.1.8-2.5 1.3-4.1 1.3-3.2 0-5.9-2.2-6.9-5.2z" />
                    <path fill="#FBBC05" d="M1.3 7.6C.5 9.2.1 10.9.1 12.6c0 1.7.4 3.4 1.2 5l4-3.3c-.2-.6-.3-1.3-.3-2s.1-1.4.3-2.1l-4-3.6z" />
                    <path fill="#4285F4" d="M12 4.6c1.8 0 3.4.6 4.7 1.8l3.5-3.5C17.9.9 15.1 0 12 0 7.1 0 3 2.3 1.3 7.6l4 3.6c1-3 3.7-6.6 6.7-6.6z" />
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </div>

        <!-- RIGHT: HERO -->
        <div class="hero-pane">
            <div class="hero-top">
                <div class="hero-logo" title="CoolPro Service AC">
                    <!-- ikon snowflake/kipas -->
                    <svg viewBox="0 0 64 64" fill="none">
                        <circle cx="32" cy="32" r="10" stroke="#22d3ee" stroke-width="4" />
                        <path d="M32 6v12M32 46v12M6 32h12M46 32h12M13 13l8 8M51 51l-8-8M51 13l-8 8M13 51l8-8"
                            stroke="#2563eb" stroke-width="4" stroke-linecap="round" />
                    </svg>
                </div>
            </div>

            <div class="welcome">
                <h2>Selamat datang di panel RommiStark Service AC</h2>
                <p>Masuk untuk memantau <strong>status pesanan</strong>, <strong>jadwal kunjungan teknisi</strong>, dan <strong>invoice</strong> layanan seperti cuci AC, isi freon, perawatan, pemasangan, dan perbaikan.</p>
            </div>

            <div class="scene">
                <!-- monitor -->
                <svg class="monitor" viewBox="0 0 680 420" fill="none">
                    <rect x="20" y="20" width="640" height="340" rx="18" fill="#ffffff" />
                    <rect x="20" y="20" width="640" height="340" rx="18" stroke="#cfe3ff" stroke-width="2" />
                    <rect x="40" y="52" width="210" height="12" rx="6" fill="#e8f1ff" />
                    <rect x="40" y="80" width="150" height="8" rx="4" fill="#e8f1ff" />
                    <rect x="40" y="100" width="190" height="8" rx="4" fill="#e8f1ff" />
                    <!-- grid -->
                    <g opacity=".5" stroke="#eef5ff">
                        <path d="M260 64v276" />
                        <path d="M310 64v276" />
                        <path d="M360 64v276" />
                        <path d="M410 64v276" />
                        <path d="M460 64v276" />
                        <path d="M510 64v276" />
                        <path d="M560 64v276" />
                        <path d="M260 90h330" />
                        <path d="M260 130h330" />
                        <path d="M260 170h330" />
                        <path d="M260 210h330" />
                        <path d="M260 250h330" />
                        <path d="M260 290h330" />
                        <path d="M260 330h330" />
                    </g>
                    <!-- line chart -->
                    <path d="M270 300 C 300 250, 330 260, 360 220 S 420 160, 450 200 500 260, 540 190 570 160, 585 185" fill="none" stroke="url(#g1)" stroke-width="6" stroke-linecap="round" />
                    <defs>
                        <linearGradient id="g1" x1="270" y1="140" x2="585" y2="300">
                            <stop stop-color="#22d3ee" />
                            <stop offset="1" stop-color="#2563eb" />
                        </linearGradient>
                    </defs>
                    <rect x="270" y="374" width="180" height="10" rx="5" fill="#cfe3ff" />
                    <rect x="310" y="360" width="100" height="14" rx="7" fill="#e6f0ff" />
                </svg>

                <!-- phone -->
                <svg class="phone" viewBox="0 0 220 420" fill="none">
                    <rect x="14" y="10" width="192" height="400" rx="28" fill="#ffffff" />
                    <rect x="14" y="10" width="192" height="400" rx="28" stroke="#cfe3ff" stroke-width="2" />
                    <rect x="38" y="60" width="144" height="16" rx="8" fill="#eaf2ff" />
                    <rect x="38" y="92" width="120" height="12" rx="6" fill="#eaf2ff" />
                    <rect x="38" y="120" width="144" height="180" rx="16" fill="url(#gc)" />
                    <!-- ikon AC kecil -->
                    <rect x="62" y="156" width="96" height="26" rx="6" fill="#ffffff" stroke="#cfe3ff" />
                    <circle cx="80" cy="169" r="6" fill="#22d3ee" />
                    <rect x="62" y="190" width="96" height="8" rx="4" fill="#d9ebff" />
                    <rect x="38" y="318" width="80" height="12" rx="6" fill="#eaf2ff" />
                    <rect x="38" y="338" width="118" height="12" rx="6" fill="#eaf2ff" />
                    <defs>
                        <linearGradient id="gc" x1="38" y1="120" x2="182" y2="300">
                            <stop stop-color="#dff7ff" />
                            <stop offset="1" stop-color="#bfe3ff" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="arrow">
                <svg viewBox="0 0 120 120" fill="none">
                    <path d="M20 90 L60 50 L60 72 L100 30 L100 80 L78 80 L78 58 L38 100 Z" fill="url(#ga)" />
                    <defs>
                        <linearGradient id="ga" x1="20" y1="90" x2="100" y2="30">
                            <stop stop-color="#22d3ee" />
                            <stop offset="1" stop-color="#2563eb" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="hero-grid" aria-hidden="true"></div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const pw = document.getElementById('password');
        toggle.addEventListener('click', () => {
            pw.type = pw.type === 'password' ? 'text' : 'password';
            toggle.classList.toggle('fa-eye');
            toggle.classList.toggle('fa-eye-slash');
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const login = document.getElementById('login').value.trim();
            const pass = pw.value.trim();
            const err = document.getElementById('errorMessage');
            const ok = document.getElementById('successMessage');
            err.classList.remove('show');
            ok.classList.remove('show');

            if (!login || !pass) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Email/username dan password harus diisi';
                err.classList.add('show');
                return;
            }
            if (pass.length < 6) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Password minimal 6 karakter';
                err.classList.add('show');
                return;
            }
            ok.classList.add('show');
        });
    </script>
</body>

</html>