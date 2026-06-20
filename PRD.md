# Laravel Popup PRD

## 1. 제품 개요

`ssh521/laravel-popup`은 Laravel 애플리케이션에 사이트 팝업, 배너, 공지 바를 빠르게 추가하고 운영할 수 있게 하는 Composer 패키지다.

이 패키지는 독립형 마케팅 자동화 도구가 아니라 `ssh521/laravel-admin` 기반 관리자 화면과 공개 사이트 Blade 렌더링에 자연스럽게 붙는 운영 도구를 목표로 한다. 운영자는 관리자 화면에서 노출 기간, 위치, 디바이스, 페이지 조건, 닫기 정책을 설정하고, 방문자는 사이트 접속 시 조건에 맞는 팝업 또는 배너를 확인할 수 있어야 한다.

## 2. 문제 정의

Laravel 프로젝트에서 팝업과 배너를 매번 직접 구현하면 다음 비용이 반복된다.

- 이벤트, 점검, 공지, 프로모션마다 임시 Blade와 JavaScript를 작성해야 하는 문제
- 운영자가 노출 기간이나 문구를 바꾸기 위해 개발자 배포를 기다려야 하는 문제
- 모바일/데스크톱별 노출, 위치, 페이지 조건, 닫기 정책을 매 프로젝트마다 새로 구현해야 하는 문제
- 여러 팝업이 동시에 존재할 때 우선순위와 정렬을 관리하기 어려운 문제
- "오늘 하루 닫기" 같은 방문자별 닫기 상태를 안정적으로 처리하기 어려운 문제
- 관리자 메뉴, 권한, 목록/작성/수정 화면을 반복 구현해야 하는 문제

`laravel-popup`은 이 반복 비용을 줄이고, 패키지 설치 후 설정과 마이그레이션만으로 운영 가능한 팝업/배너 관리 경험을 제공해야 한다.

## 3. 목표

- Laravel 13 이상 프로젝트에서 Composer 설치, 설정 publish, migrate 후 바로 사용할 수 있는 팝업/배너 관리 패키지를 제공한다.
- 운영자가 관리자 화면에서 팝업, 배너, 공지 바를 생성, 수정, 비활성화할 수 있게 한다.
- 노출 기간, 위치, 디바이스, 페이지 조건, 우선순위, 닫기 정책을 설정할 수 있게 한다.
- 공개 사이트에서는 현재 요청에 맞는 활성 항목만 렌더링한다.
- "오늘 하루 닫기"와 일반 닫기를 기본 제공한다.
- 호스트 애플리케이션이 공개 view, 관리자 view, 닫기 저장 방식, 이미지 선택 방식을 커스터마이징할 수 있게 한다.
- `ssh521/laravel-admin`의 메뉴/권한 체계와 통합한다.

## 4. 비목표

- A/B 테스트, 고급 세그먼트, 개인화 추천, 마케팅 자동화 전체 기능을 v1 목표로 하지 않는다.
- 실시간 통계 대시보드와 대량 분석 기능은 v1 범위에 포함하지 않는다.
- 외부 광고 플랫폼, CRM, 마케팅 플랫폼 연동을 기본 제공하지 않는다.
- 복잡한 노코드 디자인 빌더를 자체 개발하지 않는다.
- 호스트 애플리케이션의 인증 시스템이나 프론트엔드 전체 레이아웃을 대체하지 않는다.

## 5. 대상 사용자

### 5.1 Laravel 개발자

- 프로젝트마다 반복되는 팝업/배너 기능을 빠르게 붙이고 싶은 개발자
- 기본 view를 publish한 뒤 프로젝트 디자인에 맞게 수정하려는 개발자
- 닫기 저장 방식, 이미지 선택, 공개 렌더링 위치를 프로젝트별로 바꾸려는 개발자

### 5.2 사이트 운영자

- 이벤트, 점검, 긴급 공지, 프로모션 배너를 직접 등록하려는 운영자
- 노출 기간과 디바이스 조건을 코드 수정 없이 관리하려는 운영자
- 여러 팝업의 우선순위와 활성 상태를 관리하려는 운영자

### 5.3 공개 사이트 방문자

- 현재 접속한 페이지와 디바이스에 맞는 공지 또는 배너를 보는 사용자
- 필요 시 팝업을 닫거나 오늘 하루 보지 않도록 설정하는 사용자

## 6. 핵심 사용자 시나리오

### 6.1 패키지 설치

1. 개발자는 Composer로 `ssh521/laravel-popup`을 설치한다.
2. 개발자는 설정 파일과 migration을 publish한다.
3. 개발자는 migration을 실행한다.
4. 개발자는 공개 layout 또는 공통 Blade에 팝업 렌더링 지점을 추가한다.
5. 개발자는 관리자 메뉴에서 팝업 관리 화면을 확인한다.

### 6.2 사이트 팝업 생성

1. 운영자는 관리자 화면에서 새 항목을 생성한다.
2. 운영자는 표시 타입을 `popup`으로 선택한다.
3. 운영자는 제목, 내용, 이미지, 링크, 노출 위치, 노출 기간을 입력한다.
4. 운영자는 대상 디바이스와 페이지 조건을 설정한다.
5. 저장 후 조건에 맞는 방문자에게 팝업이 노출된다.

### 6.3 공지 바 생성

1. 운영자는 표시 타입을 `notice_bar`로 선택한다.
2. 운영자는 짧은 공지 문구와 링크를 입력한다.
3. 운영자는 위치를 상단 또는 하단으로 설정한다.
4. 공개 사이트에서는 레이아웃을 밀거나 overlay 방식으로 공지 바를 렌더링한다.

### 6.4 오늘 하루 닫기

1. 방문자는 팝업에서 "오늘 하루 보지 않기"를 선택한다.
2. 브라우저에는 해당 팝업 ID와 날짜 기준 닫기 상태가 저장된다.
3. 같은 날 같은 브라우저에서는 해당 팝업이 다시 노출되지 않는다.
4. 다음 날 또는 저장 만료 후에는 조건에 맞을 때 다시 노출될 수 있다.

### 6.5 조건별 노출 관리

1. 운영자는 팝업을 모바일 전용으로 설정한다.
2. 운영자는 `/products/*`에서는 노출하고 `/admin/*`에서는 제외하도록 조건을 설정한다.
3. 방문자의 요청 path와 디바이스가 조건에 맞을 때만 항목이 렌더링된다.

## 7. 기능 요구사항

### 7.1 패키지 설치와 설정

- Composer 패키지로 설치할 수 있어야 한다.
- Laravel service provider를 통해 config, migration, route, view를 등록해야 한다.
- 설정 파일 publish를 지원해야 한다.
- 관리자 route prefix는 `laravel-admin.route_prefix`와 패키지 설정을 조합해 결정해야 한다.
- 관리자 middleware는 패키지 설정이 없으면 `laravel-admin.middleware`를 사용해야 한다.
- 공개 렌더링 view와 관리자 view publish를 지원해야 한다.
- 닫기 상태 저장 방식은 `cookie`와 `localStorage` 중 설정할 수 있어야 한다.
- 공개 렌더링은 Blade include, Blade component, view composer 중 최소 하나의 명확한 방식을 제공해야 한다.

### 7.2 표시 타입

- 기본 표시 타입은 `popup`, `banner`, `notice_bar`로 한다.
- `popup`은 화면 위에 떠 있는 모달 또는 floating layer로 렌더링한다.
- `banner`는 지정 위치의 이미지/콘텐츠 블록으로 렌더링한다.
- `notice_bar`는 상단 또는 하단의 얇은 공지 영역으로 렌더링한다.
- 개발자는 config에서 표시 타입을 추가하거나 비활성화할 수 있어야 한다.
- 존재하지 않는 표시 타입은 저장할 수 없어야 한다.

### 7.3 콘텐츠

- 항목은 제목, 내부 관리용 설명, 표시 제목, 본문, 이미지, 링크를 가질 수 있어야 한다.
- 본문은 일반 텍스트와 제한된 HTML을 지원해야 한다.
- 이미지에는 alt text를 저장할 수 있어야 한다.
- 링크는 URL, 새 창 열기 여부, rel 속성 옵션을 저장할 수 있어야 한다.
- 이미지 선택은 `ssh521/laravel-file` 연동을 우선 전략으로 둔다.
- MVP에서 자체 업로드 UI는 필수로 제공하지 않는다.

### 7.4 노출 기간과 상태

- 항목은 `draft`, `active`, `inactive` 상태를 가져야 한다.
- 노출 시작 시각과 종료 시각을 설정할 수 있어야 한다.
- 시작 시각이 비어 있으면 즉시 노출 가능한 것으로 처리한다.
- 종료 시각이 비어 있으면 종료일 없이 노출 가능한 것으로 처리한다.
- 공개 사이트에서는 `active` 상태이고 현재 시각이 노출 기간 안에 있는 항목만 렌더링해야 한다.

### 7.5 위치와 레이아웃

- 기본 위치는 `top`, `bottom`, `center`, `top_left`, `top_right`, `bottom_left`, `bottom_right`를 제공한다.
- `notice_bar`는 기본적으로 `top`과 `bottom` 위치를 사용한다.
- `popup`은 기본적으로 `center`, `bottom_left`, `bottom_right` 위치를 사용할 수 있다.
- 위치별 Blade partial 또는 CSS class를 교체할 수 있어야 한다.
- 항목별 width, max width, z-index, 배경색, 텍스트색 같은 표시 설정은 JSON settings로 저장할 수 있어야 한다.

### 7.6 디바이스 조건

- 디바이스 조건은 `all`, `desktop`, `tablet`, `mobile`을 지원해야 한다.
- 디바이스 판별은 서버 요청의 user agent 또는 클라이언트 viewport 기준 중 패키지 기본 전략을 정해야 한다.
- MVP 기본 전략은 서버 user agent 판별로 하며, viewport 기반 세밀 제어는 공개 view의 CSS와 JavaScript에서 보완한다.
- 디바이스 조건이 맞지 않는 항목은 렌더링 대상에서 제외해야 한다.

### 7.7 페이지 조건

- 항목은 전체 페이지 노출을 기본값으로 한다.
- include path와 exclude path 패턴을 설정할 수 있어야 한다.
- path 패턴은 `/products/*`, `/event/summer`, `/` 같은 문자열 패턴을 지원해야 한다.
- exclude 조건은 include 조건보다 우선해야 한다.
- 관리자 route와 인증 route는 기본 exclude 후보로 문서화해야 한다.

### 7.8 우선순위와 정렬

- 항목은 priority와 sort_order를 가져야 한다.
- 공개 렌더링은 priority 내림차순, sort_order 오름차순, id 내림차순을 기본 정렬로 한다.
- 설정에서 타입별 최대 노출 개수를 지정할 수 있어야 한다.
- 같은 위치에 여러 항목이 있을 때 순서가 예측 가능해야 한다.

### 7.9 닫기 정책

- 닫기 정책은 `none`, `close`, `hide_today`, `hide_period`를 지원한다.
- `none`은 닫기 버튼을 제공하지 않는다.
- `close`는 현재 페이지 세션에서만 닫는다.
- `hide_today`는 오늘 하루 닫기 상태를 저장한다.
- `hide_period`는 설정된 시간 또는 일수 동안 닫기 상태를 저장한다.
- 닫기 상태 저장 key는 패키지 prefix, 항목 ID, 닫기 정책을 포함해 충돌을 줄여야 한다.
- 닫기 상태는 방문자 브라우저 기준이며 서버 사용자 계정 상태와 동기화하지 않는다.

### 7.10 공개 렌더링

- 공개 렌더링은 server-rendered Blade를 기본으로 한다.
- 닫기 버튼, 오늘 하루 닫기, 표시 애니메이션에는 최소 JavaScript를 사용할 수 있다.
- 패키지는 기본 CSS와 JS asset을 publish할 수 있어야 한다.
- 호스트 앱이 Vite 또는 자체 asset pipeline으로 스타일을 교체할 수 있어야 한다.
- 렌더링 대상 항목은 현재 request path, 디바이스, 기간, 활성 상태, 닫기 상태를 기준으로 결정해야 한다.
- 닫기 상태는 클라이언트에 있으므로 서버 렌더링 후 클라이언트 JS에서 숨김 처리할 수 있다.

### 7.11 관리자 화면

- 관리자 화면은 다른 `ssh521/*` 기능 패키지와 동일하게 controller + Blade view를 기본 구현 방식으로 한다.
- 관리자는 팝업/배너 목록을 확인할 수 있어야 한다.
- 관리자는 제목, 타입, 상태, 디바이스, 기간으로 검색/필터링할 수 있어야 한다.
- 관리자는 항목을 생성, 수정, 복제, 삭제할 수 있어야 한다.
- 관리자는 항목을 활성화/비활성화할 수 있어야 한다.
- 관리자는 공개 렌더링 미리보기를 볼 수 있어야 한다.
- 목록에서는 현재 노출 가능 여부를 명확하게 보여줘야 한다.

### 7.12 관리자 메뉴와 권한

- `ssh521/laravel-admin`이 설치되어 있으면 관리자 메뉴에 팝업 관리 항목을 등록해야 한다.
- 메뉴 기본 위치는 콘텐츠 관리 또는 사이트 운영 그룹 하위로 한다.
- 관리자 route는 관리자 인증 middleware로 보호되어야 한다.
- 향후 권한 패키지와 연동할 수 있도록 view/create/update/delete/publish 권한 키를 정의한다.

권한 키 예시:

```txt
laravel-popup-dashboard-access
laravel-popup-items-view
laravel-popup-items-create
laravel-popup-items-update
laravel-popup-items-delete
laravel-popup-items-publish
```

## 8. 데이터 모델

### 8.1 `popups`

- `id`
- `title`
- `description`
- `type`
- `status`
- `display_title`
- `body`
- `image_disk`
- `image_path`
- `image_alt`
- `link_url`
- `link_target`
- `link_rel`
- `position`
- `device`
- `starts_at`
- `ends_at`
- `include_paths`
- `exclude_paths`
- `close_policy`
- `close_duration`
- `priority`
- `sort_order`
- `settings`
- `created_by`
- `updated_by`
- `created_at`
- `updated_at`
- `deleted_at`

### 8.2 `popup_impressions`

이 테이블은 v1.1 통계 기능을 위한 후속 범위로 둔다.

- `id`
- `popup_id`
- `session_key`
- `path`
- `device`
- `user_id`
- `ip_hash`
- `user_agent_hash`
- `created_at`

### 8.3 `popup_clicks`

이 테이블은 v1.1 통계 기능을 위한 후속 범위로 둔다.

- `id`
- `popup_id`
- `session_key`
- `path`
- `device`
- `user_id`
- `ip_hash`
- `created_at`

## 9. 설정

설정 파일은 `config/laravel-popup.php`로 publish할 수 있어야 한다.

```php
return [
    'admin' => [
        'route_prefix' => 'popups',
        'route_name_prefix' => 'popups.',
        'middleware' => null,
    ],

    'public' => [
        'enabled' => true,
        'middleware' => ['web'],
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

    'device_detection' => [
        'strategy' => 'user_agent',
    ],

    'views' => [
        'public' => 'laravel-popup::public.index',
        'admin_layout' => 'laravel-admin::admin.layouts.admin',
    ],

    'media' => [
        'driver' => 'laravel-file',
    ],
];
```

## 10. 공개 API

개발자는 Blade에서 현재 요청에 맞는 팝업을 렌더링할 수 있어야 한다.

```blade
@include('laravel-popup::public.render')
```

또는 Blade component 방식도 지원할 수 있다.

```blade
<x-laravel-popup::render />
```

서비스 API는 현재 요청 기준 항목 조회를 제공해야 한다.

```php
app('laravel-popup')->forRequest(request())->get();
```

## 11. 관리자 URL

관리자 prefix는 `config('laravel-admin.route_prefix', 'admin')`와 `config('laravel-popup.admin.route_prefix', 'popups')`를 조합한다.

| 경로 | Route name | 설명 |
|------|------------|------|
| `/admin/popups` | `popup.admin.items.index` | 팝업/배너 목록 |
| `/admin/popups/create` | `popup.admin.items.create` | 항목 등록 |
| `/admin/popups/{popup}` | `popup.admin.items.show` | 항목 상세 |
| `/admin/popups/{popup}/edit` | `popup.admin.items.edit` | 항목 수정 |
| `/admin/popups/{popup}/duplicate` | `popup.admin.items.duplicate` | 항목 복제 |
| `/admin/popups/{popup}/preview` | `popup.admin.items.preview` | 미리보기 |

## 12. 접근성과 UX

- 닫기 버튼은 키보드로 접근 가능해야 한다.
- `popup` 타입은 적절한 dialog role 또는 aria 속성을 제공해야 한다.
- 이미지에는 alt text를 설정할 수 있어야 한다.
- 공지 바는 본문 콘텐츠를 과도하게 가리지 않아야 한다.
- 모바일에서는 화면 폭을 넘지 않도록 기본 max width와 padding을 제공해야 한다.
- 여러 항목이 렌더링되어도 닫기 버튼과 링크가 겹치지 않아야 한다.

## 13. 테스트와 수락 기준

- 활성 상태이고 노출 기간 안에 있는 항목만 공개 렌더링 대상이 된다.
- 시작 전, 종료 후, 비활성, 초안, 삭제된 항목은 노출되지 않는다.
- 디바이스 조건이 맞지 않는 항목은 노출되지 않는다.
- include/exclude path 조건이 올바르게 적용된다.
- exclude path는 include path보다 우선한다.
- 우선순위와 정렬 순서대로 항목이 반환된다.
- 타입별 최대 노출 개수가 적용된다.
- "오늘 하루 닫기"를 선택한 항목은 같은 날 같은 브라우저에서 다시 보이지 않는다.
- `hide_period`는 설정된 기간 동안 닫기 상태를 유지한다.
- 관리자 권한이 없는 사용자는 관리 화면에 접근할 수 없다.
- 관리자는 항목을 생성, 수정, 복제, 삭제할 수 있다.
- view publish 후 호스트 앱에서 공개 렌더링 디자인을 교체할 수 있다.

## 14. 로드맵

### v1.0

- 팝업, 배너, 공지 바 CRUD
- 노출 기간, 위치, 디바이스, 페이지 조건
- 오늘 하루 닫기와 기간 닫기
- 공개 Blade 렌더링
- 관리자 메뉴와 권한 seed

### v1.1

- 노출 수와 클릭 수 기록
- 관리자 통계 요약
- 기간별 리포트
- 클릭률 표시

### v1.2

- 로그인 상태, 사용자 그룹, 권한 기반 타겟팅
- 다국어 콘텐츠 구조
- `ssh521/laravel-file` 파일 선택 UI 고도화

### v1.3

- 캠페인 그룹
- A/B 테스트
- 노출 빈도 제한
- 외부 분석 도구 이벤트 hook
