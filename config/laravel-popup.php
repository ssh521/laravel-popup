<?php

return [
    'admin' => [
        'route_prefix' => env('LARAVEL_POPUP_ADMIN_PREFIX', 'popups'),
        'route_name_prefix' => 'popup.admin.',
        'middleware' => null,
    ],

    'public' => [
        'enabled' => env('LARAVEL_POPUP_PUBLIC_ENABLED', true),
        'middleware' => ['web'],
        'max_items_per_type' => [
            'popup' => 3,
            'banner' => 5,
            'notice_bar' => 1,
        ],
    ],

    'close_state' => [
        'driver' => env('LARAVEL_POPUP_CLOSE_DRIVER', 'localStorage'),
        'cookie_name' => 'laravel_popup_closed',
        'cookie_minutes' => 1440,
    ],

    'device_detection' => [
        'strategy' => 'user_agent',
    ],

    'views' => [
        'admin_layout' => 'laravel-admin::admin.layouts.admin',
    ],

    'media' => [
        'driver' => 'laravel-file',
    ],

    'types' => [
        'popup' => '팝업',
        'banner' => '배너',
        'notice_bar' => '공지 바',
    ],

    'statuses' => [
        'draft' => '초안',
        'active' => '활성',
        'inactive' => '비활성',
    ],

    'devices' => [
        'all' => '전체',
        'desktop' => '데스크톱',
        'tablet' => '태블릿',
        'mobile' => '모바일',
    ],

    'positions' => [
        'top' => '상단',
        'bottom' => '하단',
        'center' => '중앙',
        'top_left' => '좌상단',
        'top_right' => '우상단',
        'bottom_left' => '좌하단',
        'bottom_right' => '우하단',
    ],

    'close_policies' => [
        'none' => '닫기 없음',
        'close' => '일반 닫기',
        'hide_today' => '오늘 하루 닫기',
        'hide_period' => '기간 닫기',
    ],
];
