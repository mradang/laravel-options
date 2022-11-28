<?php

namespace mradang\LaravelOptions\Test;

use mradang\LaravelOptions\SettingServiceTrait;

class UserSettingService
{
    use SettingServiceTrait;

    private static function optionPrefix(): string
    {
        return 'user_setting_';
    }

    public static function settings(): array
    {
        return [
            // 职称等级字典
            'title_levels' => [
                'rule' => 'nullable|array',
                'default' => [],
            ],
            // 默认职称等级
            'default_title_level' => [
                'rule' => 'nullable|integer',
                'default' => 3,
            ],
        ];
    }
}
