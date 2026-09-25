<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>S4kByLaxmi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8ecf1;
            padding: 20px;
        }

        #app {
            width: 100%;
            max-width: 420px;
            position: relative;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 40px 36px 32px;
            box-shadow:
                0 8px 40px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        /* Icono */
        .login-icon {
            font-size: 2.4rem;
            color: #1a1a2e;
            text-align: center;
            display: block;
            margin-bottom: 4px;
            opacity: 0.15;
            font-weight: 300;
            letter-spacing: 2px;
        }

        /* Títulos */
        .login-title {
            font-size: 1.8rem;
            font-weight: 300;
            color: #1a1a2e;
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 2px;
        }

        .login-sub {
            font-size: 0.8rem;
            color: #5a5a72;
            text-align: center;
            margin-bottom: 28px;
            letter-spacing: 1px;
            font-weight: 300;
        }
        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: #5a5a72;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 14px;
            font-size: 0.9rem;
            font-family: inherit;
            color: #1a1a2e;
            transition: all 0.25s ease;
            outline: none;
        }

        .input-group input::placeholder {
            color: #8a8aa0;
            font-weight: 300;
            font-size: 0.8rem;
        }

        .input-group input:focus {
            border-color: #1a1a2e;
            background: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 0 4px rgba(26, 26, 46, 0.04);
        }

        .input-group input:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: #5a5a72;
            cursor: pointer;
            font-weight: 400;
        }

        .checkbox-label input {
            display: none;
        }

        .checkmark {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .checkbox-label input:checked + .checkmark {
            background: #1a1a2e;
            border-color: #1a1a2e;
        }

        .checkbox-label input:checked + .checkmark::after {
            content: '✓';
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.6rem;
        }

        .forgot-link {
            font-size: 0.75rem;
            color: #5a5a72;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #1a1a2e;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a1a2e, #2a2a4a);
            border: none;
            border-radius: 30px;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            box-shadow: 0 4px 16px rgba(26, 26, 46, 0.15);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(26, 26, 46, 0.2);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .signup-link {
            text-align: center;
            font-size: 0.8rem;
            color: #5a5a72;
            margin-top: 20px;
            letter-spacing: 0.5px;
        }

        .signup-link a {
            color: #1a1a2e;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .signup-link a:hover {
            color: #ff6b35;
        }

        .login-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 0.6rem;
            color: #8a8aa0;
            letter-spacing: 2px;
            opacity: 0.5;
        }

        .login-container {
            animation: fadeUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) both;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 28px 20px 24px;
                border-radius: 24px;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .login-sub {
                font-size: 0.7rem;
                margin-bottom: 20px;
            }

            .input-group input {
                padding: 10px 14px;
                font-size: 0.85rem;
                border-radius: 12px;
            }

            .btn-login {
                padding: 12px;
                font-size: 0.75rem;
            }

            .login-options {
                flex-wrap: wrap;
                gap: 8px;
            }

            .login-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="login-container">
            <div class="login-icon">✦</div>
            <h1 class="login-title">Login</h1>
            <p class="login-sub">sign in to your account</p>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                </div>
            @endif
            <form id="loginForm" action="{{ route('front-user.postSignup') }}" autocomplete="off" method="POST">
                @csrf
                <div class="input-group">
                    <label for="Name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required />
                </div>
                <div class="input-group">
                    <label for="username">Email</label>
                    <input type="text" id="username" name="email" placeholder="Enter your email" required />
                </div>
                <div class="input-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="username" name="phone_number" placeholder="Enter your Phone Number" required />
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required />
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter your Confirm password" required />
                </div>
                <button type="submit" class="btn-login">SIGN UP</button>
            </form>
        </div>
    </div>
    <script>
        (function() {
            'use strict';

            const form = document.getElementById('loginForm');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            form.addEventListener('submit', function(e) {
                if (username.value.trim() === '' || password.value.trim() === '') {
                    if (username.value.trim() === '') {
                        username.style.borderColor = '#ff6b35';
                        username.style.boxShadow = '0 0 0 4px rgba(255, 107, 53, 0.08)';
                        setTimeout(function() {
                            username.style.borderColor = '';
                            username.style.boxShadow = '';
                        }, 1200);
                    }
                    if (password.value.trim() === '') {
                        password.style.borderColor = '#ff6b35';
                        password.style.boxShadow = '0 0 0 4px rgba(255, 107, 53, 0.08)';
                        setTimeout(function() {
                            password.style.borderColor = '';
                            password.style.boxShadow = '';
                        }, 1200);
                    }
                    return;
                }
                const btn = form.querySelector('.btn-login');
                const originalText = btn.textContent;
                btn.textContent = '...';
                btn.style.opacity = '0.6';

                setTimeout(function() {
                    btn.textContent = '✓';
                    btn.style.opacity = '1';
                    btn.style.background = 'linear-gradient(135deg, #1a8a4a, #2aaa5a)';

                    setTimeout(function() {
                        btn.textContent = originalText;
                        btn.style.background = '';
                        form.reset();
                        // Resetear estilos de los campos
                        username.style.borderColor = '';
                        username.style.boxShadow = '';
                        password.style.borderColor = '';
                        password.style.boxShadow = '';
                    }, 1200);
                }, 800);
            });

            // Efecto de entrada para el contenedor
            const container = document.querySelector('.login-container');
            container.addEventListener('mouseenter', function() {
                container.style.transform = 'scale(1.005)';
                container.style.boxShadow = '0 8px 48px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(255, 255, 255, 0.7)';
            });
            container.addEventListener('mouseleave', function() {
                container.style.transform = 'scale(1)';
                container.style.boxShadow = '0 8px 40px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(255, 255, 255, 0.6)';
            });

            console.log('✦ Login page loaded');

        })();
    </script>
</body>
</html>