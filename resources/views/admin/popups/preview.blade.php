<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $popup->title }} 미리보기</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100">
    @include('laravel-popup::public.render', ['popups' => collect([$popup]), 'preview' => true])
</body>
</html>
