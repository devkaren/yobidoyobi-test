<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="layout-container">
<div class="layout-logo">
<img src="{{ asset('assets/logo.png') }}" width="238" height="51" alt="{{ config('app.name') }}">
</div>
{{ $slot }}
</div>
</body>
</html>
