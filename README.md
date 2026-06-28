# Laravel Popup

`ssh521/laravel-popup`은 Laravel 사이트에 팝업, 배너, 공지 바를 추가하고 `ssh521/laravel-admin` 관리자 화면에서 운영할 수 있게 하는 패키지입니다.

운영자는 코드 수정 없이 노출 기간, 위치, 디바이스, 페이지 조건, 닫기 정책을 관리할 수 있습니다. 방문자 화면에는 현재 요청 URL, 디바이스, 노출 기간, 닫기 상태에 맞는 항목만 렌더링됩니다.

## 요구 사항

- PHP `^8.3`
- Laravel `^13.0`
- `ssh521/laravel-admin` `^1.0`
- `ssh521/laravel-file` `^1.0`

## 주요 기능

- 표시 타입: 팝업 `popup`, 배너 `banner`, 공지 바 `notice_bar`
- 상태 관리: 초안 `draft`, 활성 `active`, 비활성 `inactive`
- 노출 기간: 시작 시각, 종료 시각, 무기한 노출
- 노출 위치: 상단, 하단, 중앙, 좌상단, 우상단, 좌하단, 우하단
- 디바이스 조건: 전체, 데스크톱, 태블릿, 모바일
- 페이지 조건: include/exclude path 패턴
- 닫기 정책: 닫기 없음, 일반 닫기, 오늘 하루 닫기, 기간 닫기
- 관리자 CRUD, 복제, 미리보기
- `laravel-admin` 메뉴/권한 seeder
- Blade include 또는 package component 기반 공개 렌더링

## 설치

일반 설치:

```bash
composer require ssh521/laravel-popup
php artisan vendor:publish --tag=laravel-popup-config
php artisan migrate
```

로컬 개발 환경에서 `adminTest` 같은 워크벤치 앱에 path repository로 연결할 때는 앱의 `composer.json`에 저장소와 require 항목을 추가합니다.

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../packages/ssh521/laravel-popup",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "ssh521/laravel-popup": "^1.0"
    }
}
```

```bash
composer update ssh521/laravel-popup --with-all-dependencies
php artisan migrate
```

## 관리자 메뉴와 권한 등록

`laravel-admin` 메뉴와 권한을 등록하려면 seeder를 실행합니다.

```bash
php artisan db:seed --class="Ssh521\\LaravelPopup\\Database\\Seeders\\LaravelPopupAdminMenuSeeder"
```

등록되는 기본 메뉴:

| 메뉴 | Route name | 설명 |
|------|------------|------|
| 팝업 대시보드 | `popup.admin.dashboard` | 팝업/배너 운영 요약 |
| 팝업 목록 | `popup.admin.items.index` | 팝업, 배너, 공지 바 목록 |

등록되는 권한:

| 권한 | 설명 |
|------|------|
| `laravel-popup-dashboard-access` | 팝업 대시보드 접근 |
| `laravel-popup-items-view` | 팝업 항목 조회 |
| `laravel-popup-items-create` | 팝업 항목 생성 |
| `laravel-popup-items-update` | 팝업 항목 수정 |
| `laravel-popup-items-delete` | 팝업 항목 삭제 |
| `laravel-popup-items-publish` | 팝업 항목 활성화 |

## 관리자 URL

관리자 prefix는 `config('laravel-admin.route_prefix', 'admin')`와 `config('laravel-popup.admin.route_prefix', 'popups')`를 조합합니다. 기본값은 `/admin/popups`입니다.

| 경로 | Route name | 설명 |
|------|------------|------|
| `/admin/popups` | `popup.admin.dashboard` | 팝업 대시보드 |
| `/admin/popups/list` | `popup.admin.items.index` | 팝업 목록 |
| `/admin/popups/create` | `popup.admin.items.create` | 팝업 등록 |
| `/admin/popups/{popup}` | `popup.admin.items.show` | 팝업 상세 |
| `/admin/popups/{popup}/edit` | `popup.admin.items.edit` | 팝업 수정 |
| `/admin/popups/{popup}/preview` | `popup.admin.items.preview` | 팝업 미리보기 |

## 공개 페이지에 삽입

클라이언트 공개 페이지에는 공통 레이아웃의 `</body>` 가까이에 렌더링 코드를 추가합니다.

```blade
@include('laravel-popup::public.render')
```

또는 package component를 사용할 수 있습니다.

```blade
<x-laravel-popup::render />
```

예시:

```blade
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? config('app.name') }}</title>
</head>
<body>
    {{ $slot ?? '' }}

    @include('laravel-popup::public.render')
</body>
</html>
```

렌더링 대상은 서버에서 먼저 필터링됩니다.

- 상태가 `active`인 항목
- 현재 시각이 `starts_at`, `ends_at` 범위 안에 있는 항목
- 현재 요청 path가 include/exclude 조건에 맞는 항목
- 현재 디바이스 조건에 맞는 항목
- 타입별 최대 노출 개수 안에 들어오는 항목

닫기 상태는 브라우저 기준으로 처리됩니다. 기본 저장 방식은 `localStorage`이며, 설정으로 cookie 저장을 선택할 수 있습니다.

## 관리자에서 항목 등록

관리자 화면에서 새 항목을 등록할 때 기본 흐름은 다음과 같습니다.

1. `/admin/popups/create`로 이동합니다.
2. 타입을 `팝업`, `배너`, `공지 바` 중 선택합니다.
3. 상태를 `활성`으로 설정합니다.
4. 표시 제목, 본문 HTML, 이미지 경로, 링크 URL을 입력합니다.
5. 노출 기간, 디바이스, 위치, path 조건을 설정합니다.
6. 닫기 정책을 선택합니다.
7. 저장 후 `미리보기`에서 공개 렌더링을 확인합니다.

전체 페이지에 노출하려면 `노출 path`를 비워둡니다. 관리자 화면에는 보통 노출하지 않으므로 `제외 path`에는 `/admin/*`를 넣는 것을 권장합니다.

## 설정

설정 파일은 `config/laravel-popup.php`로 publish됩니다.

```bash
php artisan vendor:publish --tag=laravel-popup-config
```

주요 설정:

```php
return [
    'admin' => [
        'route_prefix' => 'popups',
        'middleware' => null,
    ],

    'public' => [
        'enabled' => true,
        'max_items_per_type' => [
            'popup' => 3,
            'banner' => 5,
            'notice_bar' => 1,
        ],
    ],

    'close_state' => [
        'driver' => 'localStorage',
        'cookie_name' => 'laravel_popup_closed',
        'cookie_minutes' => 1440,
    ],
];
```

환경 변수로 바꿀 수 있는 값:

```env
LARAVEL_POPUP_ADMIN_PREFIX=popups
LARAVEL_POPUP_PUBLIC_ENABLED=true
LARAVEL_POPUP_CLOSE_DRIVER=localStorage
```

## View 커스터마이징

기본 공개 렌더링 view는 Tailwind CSS class를 사용합니다. 클라이언트 사이트가 Tailwind를 쓰지 않거나 디자인을 맞춰야 한다면 view를 publish해서 수정합니다.

```bash
php artisan vendor:publish --tag=laravel-popup-views
```

수정 대상:

```txt
resources/views/vendor/laravel-popup/public/render.blade.php
resources/views/vendor/laravel-popup/components/render.blade.php
resources/views/vendor/laravel-popup/admin/...
```

## Publish Tags

```bash
php artisan vendor:publish --tag=laravel-popup-config
php artisan vendor:publish --tag=laravel-popup-migrations
php artisan vendor:publish --tag=laravel-popup-views
php artisan vendor:publish --tag=laravel-popup-seeders
```

## 로컬 개발

패키지 루트:

```bash
cd packages/ssh521/laravel-popup
```

PHPUnit:

```bash
composer test
```

패키지 자체에 `vendor/`가 없고 `adminTest` 워크벤치 vendor를 사용할 때:

```bash
php -d memory_limit=512M ../../../adminTest/vendor/bin/phpunit --configuration phpunit.xml.dist
```

문법 확인:

```bash
find . -path './vendor' -prune -o -name '*.php' -print | xargs -n 1 php -l
```

## 현재 구현 범위

v1 구현 범위:

- `popups` 테이블
- `Popup` 모델
- 공개 요청 기준 조회 서비스 `PopupManager`
- 관리자 대시보드와 CRUD
- 미리보기 화면
- 공개 Blade 렌더링
- localStorage/cookie 기반 닫기 처리
- 메뉴/권한 seeder
- Testbench 기반 등록/조회 테스트

후속 확장 후보:

- 노출 수/클릭 수 통계
- 사용자 그룹 또는 로그인 상태 기반 타겟팅
- 다국어 콘텐츠
- 캠페인 그룹
- A/B 테스트
