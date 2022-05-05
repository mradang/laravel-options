<?php

namespace mradang\LaravelOptions\Test;

use mradang\LaravelOptions\Controllers\OptionsController;
use mradang\LaravelOptions\Option;

class FeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testBasicFeatures()
    {
        $user = User::create(['name' => '张三']);
        $this->assertSame(1, $user->id);

        // 默认值
        $this->assertSame(Option::get('example_level'), 5);
        $this->assertSame(Option::get('example_level', 6), 6);

        $this->assertSame(
            Option::get(['example_level', 'user_arr']),
            [
                'example_level' => 5,
                'user_arr' => [],
            ],
        );
        $this->assertSame(Option::get(
            ['example_level', 'user_arr'],
            [
                'example_level' => 6,
                'user_arr' => ['a', 'b'],
            ],
        ), [
            'example_level' => 6,
            'user_arr' => ['a', 'b'],
        ]);

        // 路由
        $this->app['router']->post('set', [OptionsController::class, 'set']);
        $this->app['router']->post('get', [OptionsController::class, 'get']);

        $this->json('POST', 'set', [
            'example_level' => 8,
            'user_arr' => ['foo' => 'bar'],
        ])->assertStatus(200);
        $this->assertSame(Option::get('example_level'), 8);
        $this->assertSame(Option::get('user_arr'), ['foo' => 'bar']);

        $this->json('POST', 'set', [
            'example_level' => 9,
        ])->assertStatus(200);
        $this->assertSame(Option::get('example_level'), 9);

        $this->json('POST', 'set', [
            'other_level' => 2,
        ])->assertStatus(200);
        $this->assertSame(Option::get('other_level'), null);

        Option::remove('example_level');
        $this->assertSame(Option::get('example_level'), 5);

        $this->assertDatabaseHas('options', [
            'key' => 'user_arr',
        ]);

        $res = $this->json('POST', 'get', [
            'example_level',
            'user_arr',
        ]);
        $res->assertJson([
            'example_level' => 5,
            'user_arr' => ['foo' => 'bar'],
        ]);


        Option::set('foo', 'bar');
        $this->assertSame(Option::get('foo'), 'bar');
        $this->assertSame(Option::get('foo', 'eee'), 'bar');

        Option::set(['foo' => 'bar', 'bar' => 'baz']);
        $this->assertSame(Option::get(['foo', 'bar']), [
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        Option::set('arr', [1, 3, 5]);
        $this->assertSame(Option::get('arr'), [1, 3, 5]);

        $this->assertSame(Option::get('baz'), null);
        $this->assertSame(Option::get('baz', 'abc'), 'abc');

        $this->assertTrue(Option::has('bar'));
        $this->assertNotTrue(Option::has('baz'));

        Option::remove(['foo', 'bar']);
        $this->assertNotTrue(Option::has('foo'));
        $this->assertNotTrue(Option::has('bar'));
    }
}
