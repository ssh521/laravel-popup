<?php

namespace Ssh521\LaravelPopup\Services;

use Illuminate\Http\Request;

class DeviceDetector
{
    public function detect(Request $request): string
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if (str_contains($userAgent, 'ipad') || str_contains($userAgent, 'tablet')) {
            return 'tablet';
        }

        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'iphone') || str_contains($userAgent, 'android')) {
            return 'mobile';
        }

        return 'desktop';
    }
}
