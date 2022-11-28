<?php

namespace mradang\LaravelOptions;

use Illuminate\Support\Arr;

trait SettingServiceTrait
{
    abstract protected static function optionPrefix(): string;

    abstract protected static function settings(): array;

    private static function addPrefix(array $data): array
    {
        $ret = [];
        foreach ($data as $key => $value) {
            $ret[self::optionPrefix() . $key] = $value;
        }
        return $ret;
    }

    private static function removePrefix(array $data): array
    {
        $ret = [];
        foreach ($data as $key => $value) {
            $ret[str($key)->after(self::optionPrefix())->value] = $value;
        }
        return $ret;
    }

    public static function get(array|string $keys)
    {
        $settings = Arr::only(self::settings(), Arr::wrap($keys));

        $default = [];
        foreach ($settings as $key => $item) {
            $default[$key] = $item['default'];
        }

        $default = self::addPrefix($default);

        $result = Option::get(array_keys($default), $default);

        $result = self::removePrefix($result);

        return is_string($keys) ? head($result) : $result;
    }

    public static function set(array $data)
    {
        $settings = self::settings();

        $kv = Arr::only($data, array_keys($settings));

        $rule = [];
        foreach ($kv as $key => $value) {
            $rule[$key] = Arr::get($settings, "$key.rule");
        }

        $validatedData = validator($kv, $rule)->validate();

        $store = self::addPrefix($validatedData);

        return Option::set($store);
    }
}
