<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Superdigitech Portal</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    * {
      box-sizing: border-box;
    }

    body, html {
      margin: 0; padding: 0; height: 100%;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-wrapper {
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      width: 420px;
      padding: 50px 40px;
      color: #fff;
      box-sizing: border-box;
      text-align: center;
    }

    .login-header {
      font-weight: 700;
      font-size: 29px;
      margin-bottom: 30px;
      letter-spacing: 2px;
      text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      text-align: left;
      font-size: 1rem;
      color: #eee;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    input[type=email], input[type=password] {
      width: 100%;
      padding: 15px 18px;
      margin-bottom: 25px;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      outline: none;
      background: rgba(255, 255, 255, 0.3);
      color: #222;
      box-shadow: inset 2px 2px 6px rgba(255, 255, 255, 0.7),
                  inset -2px -2px 6px rgba(0, 0, 0, 0.15);
      transition: background 0.3s ease;
    }
    input[type=email]::placeholder,
    input[type=password]::placeholder {
      color: #666;
      font-style: italic;
    }
    input[type=email]:focus,
    input[type=password]:focus {
      background: #fff;
      box-shadow: 0 0 8px 2px #764ba2;
      color: #000;
    }

    .form-check {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      gap: 12px;
      justify-content: flex-start;
      color: #ddd;
      font-weight: 500;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .form-check input[type=checkbox] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #764ba2;
      border-radius: 4px;
      border: 1px solid #fff;
      box-shadow: 0 0 2px #764ba2;
      transition: box-shadow 0.3s ease;
    }
    .form-check input[type=checkbox]:focus {
      box-shadow: 0 0 6px 2px #764ba2;
      outline: none;
    }

    button[type=submit] {
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: 14px;
      font-weight: 700;
      font-size: 1.2rem;
      color: white;
      background: linear-gradient(135deg, #764ba2, #667eea);
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(118, 75, 162, 0.8);
      transition: background 0.3s ease, box-shadow 0.3s ease;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    button[type=submit]:hover {
      background: linear-gradient(135deg, #5a2a83, #5465d1);
      box-shadow: 0 10px 25px rgba(90, 42, 131, 0.9);
    }

    .forgot-password {
      margin-top: 20px;
      text-align: right;
      font-size: 0.9rem;
      color: #ddd;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .forgot-password a {
      color: #f5e9ff;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .forgot-password a:hover {
      color: #c3afff;
      text-decoration: underline;
    }

    .invalid-feedback {
      font-size: 0.85rem;
      color: #ff6b6b;
      margin-top: -18px;
      margin-bottom: 20px;
      text-align: left;
      text-shadow: none;
    }
  </style>
</head>
<body>
  <div class="login-wrapper" role="main">
    <h1 class="login-header">Superdigitech Portal</h1>

    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <label for="email">{{ __('E-Mail Address') }}</label>
      <input
        id="email"
        type="email"
        name="email"
        required
        autofocus
        placeholder="your.email@example.com"
        class="@error('email') is-invalid @enderror"
      />
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror

      <label for="password">{{ __('Password') }}</label>
      <input
        id="password"
        type="password"
        name="password"
        required
        autocomplete="current-password"
        placeholder="Enter your password"
        class="@error('password') is-invalid @enderror"
      />
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror

      <div class="form-check">
        <input
          type="checkbox"
          name="remember"
          id="remember"
          {{ old('remember') ? 'checked' : '' }}
        />
        <label for="remember">{{ __('Remember Me') }}</label>
      </div>

      <button type="submit">{{ __('Login') }}</button>

      @if (Route::has('password.request'))
        <div class="forgot-password">
          <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
        </div>
      @endif
    </form>
  </div>
</body>
</html>
