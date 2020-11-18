<?php

namespace mradang\LaravelOptions;

use Illuminate\Contracts\Foundation\Application;
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
            $query = Option::query();
            return $query
                ->when(!empty($key), function ($query) use ($key) {
                    $query->whereIn('key', $key);
                })
                ->pluck('value', 'key')
                ->toArray();
        } else {
            $key = \strtolower($key);
            $options = config("options.{$key}", []);

            if ($default === null && count($options)) {
                $default = [];
                foreach ($options as $item => $params) {
                    $default[$item] = $params['default'];
                }
            }

            return Option::firstOrNew(\compact('key'), ['value' => $default])->value;
        }
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
