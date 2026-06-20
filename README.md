# Laravel Popup

`ssh521/laravel-popup`은 `ssh521/laravel-admin`에 붙는 사이트 팝업, 배너, 공지 바 관리 패키지입니다. 운영자는 관리자 화면에서 노출 기간, 위치, 디바이스, 페이지 조건, 닫기 정책을 설정하고 방문자에게 필요한 공지를 노출할 수 있습니다.

## 요구 사항

- PHP `^8.3`
- Laravel `^13.0`
- `ssh521/laravel-admin` `^1.0`
- `ssh521/laravel-file` `^1.0`

## 주요 기능

- 표시 타입: `popup`, `banner`, `notice_bar`
- 공개 상태: `draft`, `active`, `inactive`
- 노출 기간: 시작/종료 시각
- 위치: 상단, 하단, 중앙, 좌상단, 우상단, 좌하단, 우하단
- 디바이스 조건: 전체, 데스크톱, 태블릿, 모바일
- 페이지 조건: include/exclude path 패턴
- 닫기 정책: 닫기 없음, 일반 닫기, 오늘 하루 닫기, 기간 닫기
- 관리자 CRUD, 복제, 미리보기
- `laravel-admin` 메뉴/권한 seeder
- Blade include 또는 package component 기반 공개 렌더링

## 설치

```bash
composer require ssh521/laravel-popup
```

로컬 개발 path repository를 사용할 때는 앱의 `composer.json`에 저장소를 추가합니다.

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
php artisan vendor:publish --tag=laravel-popup-config
php artisan migrate
```

## 공개 렌더링

호스트 앱의 공통 공개 layout에서 `</body>` 가까이에 추가합니다.

```blade
@include('laravel-popup::public.render')
```

또는 package component를 사용할 수 있습니다.

```blade
<x-laravel-popup::render />
```

## 관리자 URL

관리자 prefix는 `config('laravel-admin.route_prefix', 'admin')`와 `config('laravel-popup.admin.route_prefix', 'popups')`를 조합합니다.

| 경로 | Route name | 설명 |
|------|------------|------|
| `/admin/popups` | `popup.admin.dashboard` | 팝업 대시보드 |
| `/admin/popups/list` | `popup.admin.items.index` | 팝업 목록 |
| `/admin/popups/create` | `popup.admin.items.create` | 팝업 등록 |
| `/admin/popups/{popup}` | `popup.admin.items.show` | 팝업 상세 |
| `/admin/popups/{popup}/edit` | `popup.admin.items.edit` | 팝업 수정 |
| `/admin/popups/{popup}/preview` | `popup.admin.items.preview` | 팝업 미리보기 |

## 권한

| 권한 | 설명 |
|------|------|
| `laravel-popup-dashboard-access` | 팝업 대시보드 접근 |
| `laravel-popup-items-view` | 팝업 항목 조회 |
| `laravel-popup-items-create` | 팝업 항목 생성 |
| `laravel-popup-items-update` | 팝업 항목 수정 |
| `laravel-popup-items-delete` | 팝업 항목 삭제 |
| `laravel-popup-items-publish` | 팝업 항목 활성화 |

메뉴와 권한을 등록하려면 다음 seeder를 실행합니다.

```bash
php artisan db:seed --class="Ssh521\\LaravelPopup\\Database\\Seeders\\LaravelPopupAdminMenuSeeder"
```

## Publish Tags

```bash
php artisan vendor:publish --tag=laravel-popup-config
php artisan vendor:publish --tag=laravel-popup-migrations
php artisan vendor:publish --tag=laravel-popup-views
php artisan vendor:publish --tag=laravel-popup-seeders
```

## 테스트

```bash
composer test
```
