<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login Pemohon - eTalent</title>
</head>
<body>
<h1>Sistem Kenaikan Pangkat</h1>

<form method="POST" action="{{ route('app.login.submit') }}">
    @csrf

    <div>
        <label>Emel Rasmi</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email') <div style="color:red">{{ $message }}</div> @enderror
    </div>

    <div style="margin-top:10px;">
        <label>Kata Laluan</label><br>
        <input type="password" name="password" required>
        @error('password') <div style="color:red">{{ $message }}</div> @enderror
    </div>

    <div style="margin-top:10px;">
        <label>
            <input type="checkbox" name="remember" value="1"> Remember me
        </label>
    </div>

    <button style="margin-top:15px;" type="submit">Log Masuk</button>
</form>

<p style="margin-top:15px;">
    <small>Jika ini login pertama kali, kata laluan awal ialah Staff ID (sementara).</small>
</p>
</body>
</html>
