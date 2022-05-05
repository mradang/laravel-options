<?php

namespace mradang\LaravelOptions;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use mradang\LaravelOptions\Models\Option;

class OptionsManager
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function set($key, $value = null)
    {
        if (\is_array($key)) {
            $this->multiSet($key);
        } else {
            $this->save($key, $value);
        }
    }

    private function multiSet(array $options)
    {
        foreach ($options as $key => $value) {
            $this->save($key, $value);
        }
    }

    private function save(string $key, $value)
    {
        Option::updateOrCreate(\compact('key'), \compact('value'));
    }

    public function get($key, $default = null)
    {
        if (\is_array($key)) {
            return $this->multiGet($key, $default);
        }
        return $this->oneGet($key, $default);
    }

    private function multiGet(array $keys, $default): array
    {
        $records = Option::whereIn('key', $keys)->get();
        $ret = [];
        foreach ($keys as $key) {
            $ret[$key] = Arr::get(
                $records->firstWhere('key', $key),
                'value',
                Arr::get($default, $key) ?? $this->getOptionsDefault($key),
            );
        }
        return $ret;
    }

    private function oneGet(string $key, $default)
    {
        return Option::firstOrNew(\compact('key'), [
            'value' => $default ?? $this->getOptionsDefault($key),
        ])->value;
    }

    private function getOptionsDefault($key)
    {
        return Arr::get(config("options.{$key}"), 'default');
    }

    public function remove($key)
    {
        if (\is_array($key)) {
            Option::whereIn('key', $key)->delete();
        } else {
            Option::where('key', $key)->delete();
        }
    }

    public function has(string $key): bool
    {
        return Option::where('key', $key)->exists();
    }
}
