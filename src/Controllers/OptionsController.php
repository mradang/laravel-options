<?php

namespace mradang\LaravelOptions\Controllers;

use mradang\LaravelOptions\Option;

class OptionsController extends Controller
{
    public function __call($name, $arguments)
    {
        if (preg_match('/^get([A-Za-z]+)Options$/', $name, $matches)) {
            return $this->get($matches[1]);
        } else if (preg_match('/^set([A-Za-z]+)Options$/', $name, $matches)) {
            return $this->set($matches[1], request()->all());
        }
    }

    private function get($key)
    {
        return Option::get($key);
    }

    private function set(string $key, array $data)
    {
        $key = \strtolower($key);
        $options = config("options.{$key}");

        abort_if($options === null, 400, '无效的选项名');

        $rule = [];
        foreach ($options as $item => $params) {
            $rule[$item] = $params['rule'];
        }
        $validatedData = validator($data, $rule)->validate();

        Option::set($key, $validatedData);
    }
}
