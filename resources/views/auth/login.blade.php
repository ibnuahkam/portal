<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Superdigitech Portal</title>

    <link rel="icon" href="https://superdigitech.com/static/media/logo.8546d407f4d0a55db47d.jpeg"
        type="image/x-icon" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1E293B, #0F172A);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* === MAIN CARD === */
        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 1040px;
            height: 600px;
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(12px);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .35);
        }

        /* === LEFT SIDE === */
        .login-left {
            position: relative;
            background:
                linear-gradient(135deg, rgba(15, 23, 42, .85), rgba(30, 41, 59, .85)),
                url("https://images.unsplash.com/photo-1526378722445-98a9e2b9b07e?auto=format&fit=crop&w=900&q=80") center / cover no-repeat;
            color: #fff;
            padding: 40px;
        }

        .login-left::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, .2), rgba(0, 0, 0, .55));
        }

        .login-left-content {
            position: relative;
            z-index: 2;
        }

        .login-left h2 {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-left p {
            font-size: 15px;
            line-height: 1.6;
            opacity: .9;
        }

        /* === RIGHT SIDE === */
        .login-right {
            padding: 50px 45px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* LOGO */
        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .login-logo img {
            width: 85px;
            height: auto;
            background: #fff;
            padding: 8px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .4);
        }

        .login-header {
            font-size: 26px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 6px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 14px;
            opacity: .7;
            margin-bottom: 30px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 10px;
            border-radius: 12px;
            border: none;
            outline: none;
            background: rgba(255, 255, 255, .9);
            font-size: 14px;
        }

        button {
            padding: 14px;
            border-radius: 14px;
            border: none;
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, .6);
        }

        .invalid-feedback {
            font-size: 12px;
            color: #ff9a9a;
            margin-bottom: 6px;
        }

        /* === RESPONSIVE === */
        @media (max-width: 900px) {
            .login-container {
                grid-template-columns: 1fr;
                width: 95%;
                height: auto;
            }

            .login-left {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- LEFT -->
        <div class="login-left">
            <div class="login-left-content">
                <h2>Welcome Back 👋</h2>
                <p>
                    Superdigitech Portal membantu kamu mengelola konten,
                    kategori, dan artikel dengan cepat dan aman.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="login-right">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="https://superdigitech.com/static/media/logo.8546d407f4d0a55db47d.jpeg"
                    alt="Superdigitech Logo">
            </div>

            <h1 class="login-header">Superdigitech Portal</h1>
            <p class="login-subtitle">Sign in to continue</p>

            <form id="loginForm">
                @csrf

                <label>Email</label>
                <input type="email" name="email" id="email">
                <div class="invalid-feedback" id="email-error"></div>

                <label>Password</label>
                <input type="password" name="password" id="password">
                <div class="invalid-feedback" id="password-error"></div>

                <button type="submit">Sign In</button>
            </form>
        </div>

    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            document.getElementById('email-error').innerText = '';
            document.getElementById('password-error').innerText = '';

            const formData = new FormData(this);

            const response = await fetch("{{ url('/login') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.status === 422) {
                if (data.errors?.email)
                    document.getElementById('email-error').innerText = data.errors.email[0];
                if (data.errors?.password)
                    document.getElementById('password-error').innerText = data.errors.password[0];
                return;
            }

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
            }
        });
    </script>

</body>

</html>
