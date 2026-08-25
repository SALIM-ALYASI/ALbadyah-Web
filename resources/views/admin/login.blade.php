<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الإدمن - البادية</title>

    <link rel="icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen grid place-items-center bg-ab-navy px-4 antialiased">
    <div class="w-full max-w-sm bg-white rounded-[28px] shadow-[0_24px_60px_rgba(20,35,41,.35)] p-8">
        <div class="flex flex-col items-center text-center gap-3 mb-6">
            <span class="w-16 h-16 rounded-full bg-ab-navy grid place-items-center text-ab-sand">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 6v6c0 5 3.4 8.4 8 10 4.6-1.6 8-5 8-10V6l-8-4Z"></path></svg>
            </span>
            <h1 class="m-0 text-xl font-bold text-ab-navy">البادية</h1>
            <p class="m-0 text-sm text-ab-body">لوحة تحكم الإدمن</p>
        </div>

        @if ($errors->any())
            <div class="flex items-start gap-3 px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm mb-5">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="9"></circle><path d="M12 8v5M12 16h.01"></path></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="flex items-start gap-3 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm mb-5">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="flex flex-col gap-4">
            @csrf
            <label class="flex flex-col gap-2">
                <span class="text-sm font-semibold text-ab-navy">مفتاح الدخول الإداري</span>
                <input type="password" id="admin_key" name="admin_key" value="{{ old('admin_key') }}"
                    placeholder="أدخل مفتاح الدخول الإداري" required autofocus minlength="6"
                    class="w-full border {{ $errors->has('admin_key') ? 'border-red-400' : 'border-ab-border-2' }} rounded-2xl px-4 py-3.5 text-ab-navy focus:outline-none focus:border-ab-teal">
                @error('admin_key')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="w-full mt-1 px-5 py-3.5 rounded-2xl bg-ab-navy text-white font-semibold hover:bg-ab-navy/90 transition">
                تسجيل الدخول
            </button>
        </form>
    </div>
</body>
</html>
