<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Perkebunan Nyapah Banyu</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #135d37;
            --primary-hover: #0c3e24;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
            --border-focus: #135d37;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
        }

        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styling (Left Pane) */
        .login-sidebar {
            width: 45%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            overflow: hidden;
            color: #ffffff;
        }

        .sidebar-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/plantation_bg.png") }}');
            background-size: cover;
            background-position: center;
            transition: transform 12s ease;
            z-index: 1;
        }

        .login-sidebar:hover .sidebar-background {
            transform: scale(1.06);
        }

        .sidebar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(16, 44, 30, 0.45) 0%, rgba(16, 44, 30, 0.88) 100%);
            z-index: 2;
        }

        .glass-card {
            position: relative;
            z-index: 3;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px) saturate(120%);
            -webkit-backdrop-filter: blur(16px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 40px;
            width: 85%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .glass-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.25rem;
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .glass-subtitle {
            font-size: 1rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.85);
            margin: 0 0 24px 0;
        }

        .badge-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .badge-item {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .badge-item i {
            font-size: 0.8rem;
        }

        .badge-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .sidebar-footer {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            z-index: 3;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.5;
        }

        /* Form Section Styling (Right Pane) */
        .login-form-section {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            padding: 40px;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .logo-container {
            margin-bottom: 32px;
        }

        .brand-logo-svg {
            stroke: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .logo-container:hover .brand-logo-svg {
            transform: scale(1.08) rotate(3deg);
        }

        .welcome-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 2.1rem;
            color: #14532d;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }

        .welcome-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin: 0 0 32px 0;
            line-height: 1.5;
        }

        /* Input Fields Styles */
        .input-group-custom {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .custom-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .forgot-link {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .input-field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .custom-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            color: var(--text-dark);
            outline: none;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .custom-input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px rgba(19, 93, 55, 0.1);
        }

        .custom-input::placeholder {
            color: #94a3b8;
        }

        /* For password, adjust padding for the eye button on the right */
        .input-field-wrapper input[type="password"],
        .input-field-wrapper input[type="text"] {
            padding-right: 48px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #64748b;
            padding: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s, color 0.2s;
        }

        .password-toggle-btn:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }

        /* Remember me custom styling */
        .custom-checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .custom-checkbox-input {
            width: 18px;
            height: 18px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .custom-checkbox-label {
            font-size: 0.9rem;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        /* Submit Button */
        .custom-btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(19, 93, 55, 0.2);
        }

        .custom-btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 93, 55, 0.35);
        }

        .custom-btn-submit:active {
            transform: translateY(0);
        }



        /* Responsive adjustments */
        @media (max-width: 991px) {
            .login-sidebar {
                display: none;
            }
            .login-form-section {
                width: 100%;
                padding: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Section: Visual Plantation Pane -->
        <div class="login-sidebar">
            <div class="sidebar-background"></div>
            <div class="sidebar-overlay"></div>
            
            <!-- Glassmorphism Card in Center of Sidebar -->
            <div class="glass-card">
                <h1 class="glass-title">Nyapah Banyu</h1>
                <p class="glass-subtitle">Sistem Pengelolaan Perkebunan</p>
                <div class="badge-container">
                    <span class="badge-item"><i class="fa-solid fa-leaf me-2"></i>Kopi</span>
                    <span class="badge-item"><i class="fa-solid fa-location-dot me-2"></i>Sawit</span>
                    <span class="badge-item"><i class="fa-solid fa-border-all me-2"></i>Lada</span>
                </div>
            </div>
            
            <!-- Sidebar Footer Copyright -->
            <div class="sidebar-footer">
                © 2024 Pengelolaan Perkebunan Nyapah Banyu. Presisi dalam setiap panen.
            </div>
        </div>
        
        <!-- Right Section: Login Form Pane -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <!-- Custom SVG Sprout Heart Logo -->
                <div class="logo-container">
                    <svg width="68" height="68" viewBox="0 0 24 24" fill="none" stroke="#135d37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="brand-logo-svg">
                        <!-- Heart contour -->
                        <path d="M12 21C12 21 4.5 15.5 3.5 10.5C2.5 5.5 6 3 10 4.5C11.5 5 12 6.5 12 6.5C12 6.5 12.5 5 14 4.5C18 3 21.5 5.5 20.5 10.5C19.5 15.5 12 21 12 21Z" />
                        <!-- Center branch / stem -->
                        <path d="M12 20V8.5" />
                        <!-- Leaf left -->
                        <path d="M12 14.5C10 13.5 8.5 14.5 8.5 14.5C8.5 14.5 8.5 12 11.5 11.5" />
                        <!-- Leaf right -->
                        <path d="M12 11.5C14 10.5 15.5 11.5 15.5 11.5C15.5 11.5 15.5 9 12.5 8.5" />
                    </svg>
                </div>
                
                <h2 class="welcome-title">Selamat Datang Kembali</h2>
                <p class="welcome-subtitle">Silakan masuk ke akun Anda untuk mengelola perkebunan.</p>
                
                <!-- Tampilkan Error Validasi Breeze -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4" role="alert">
                        <ul class="m-0 ps-3" style="font-size: 0.85rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- E-mail Address -->
                    <div class="input-group-custom">
                        <label for="email" class="custom-label">E-mail Akun</label>
                        <div class="input-field-wrapper">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="custom-input" placeholder="hama@perusahaan.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="input-group-custom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="custom-label mb-0">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                            @else
                                <a href="#" class="forgot-link">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="input-field-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="custom-input" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" id="toggle-password" class="password-toggle-btn">
                                <i class="fa-regular fa-eye-slash" id="toggle-icon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="custom-checkbox-container">
                        <input type="checkbox" class="custom-checkbox-input" id="remember_me" name="remember">
                        <label class="custom-checkbox-label" for="remember_me">Ingat Saya</label>
                    </div>
                    
                    <!-- Button Submit -->
                    <button type="submit" class="custom-btn-submit">
                        <span>Masuk ke Sistem</span>
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </button>
                </form>
                

            </div>
        </div>
    </div>

    <!-- Script Password Toggle & Quick Fill -->
    <script>
        // Password Visibility Toggle
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('toggle-password');
        const toggleIcon = document.getElementById('toggle-icon');

        togglePasswordButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'password') {
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });


    </script>
</body>
</html>
