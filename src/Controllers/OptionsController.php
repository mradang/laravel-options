<?php

namespace mradang\LaravelOptions\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use mradang\LaravelOptions\Option;

class OptionsController extends Controller
{
    public function get(Request $request)
    {
        // 仅支持配置文件中预定义的选项
        $keys = array_intersect(
            $request->all(),
            array_keys(config('options')),
        );

        return Option::get($keys);
    }

    public function set(Request $request)
    {
        $keys = array_keys(config('options'));
        $kv = [];
        $rule = [];

        foreach ($request->all() as $k => $v) {
            if (in_array($k, $keys)) {
                $kv[$k] = $v;
                $rule[$k] = Arr::get(config("options.$k"), 'rule');
            }
        }

        $validatedData = validator($kv, $rule)->validate();
        Option::set($validatedData);
    }
}
