<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $popup->title }} 미리보기</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 p-8">
    <div class="mx-auto max-w-3xl rounded-lg bg-white p-6 shadow">
        <h1 class="text-xl font-semibold text-gray-900">미리보기 배경</h1>
        <p class="mt-2 text-sm text-gray-500">실제 공개 화면에서는 호스트 앱 레이아웃 위에 렌더링됩니다.</p>
    </div>

    @include('laravel-popup::public.render', ['popups' => collect([$popup])])
</body>
</html>
