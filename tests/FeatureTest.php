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

        $this->assertSame(Option::get('example'), [
            'level' => 5,
            'enabled' => true,
            'arr' => [],
        ]);

        Option::set('foo', 'bar');
        $this->assertSame(Option::get('foo'), 'bar');

        Option::set(['foo' => 'bar', 'bar' => 'baz']);
        $this->assertSame(Option::get(['foo', 'bar']), [
            'bar' => 'baz',
            'foo' => 'bar',
        ]);

        Option::set('arr', [1, 3, 5]);

        $this->assertSame(Option::get('arr'), [1, 3, 5]);

        $this->assertSame(Option::get('baz', 'abc'), 'abc');

        $this->assertTrue(Option::has('bar'));
        $this->assertNotTrue(Option::has('baz'));

        Option::remove(['foo', 'bar']);
        $this->assertNotTrue(Option::has('foo'));
        $this->assertNotTrue(Option::has('bar'));

        $this->app['router']->post('setFooOptions', [OptionsController::class, 'setFooOptions']);
        $this->json('POST', 'setFooOptions')->assertStatus(400);

        $this->app['router']->post('setExampleOptions', [OptionsController::class, 'setExampleOptions']);
        $this->app['router']->post('getExampleOptions', [OptionsController::class, 'getExampleOptions']);
        $res = $this->json('POST', 'setExampleOptions', [
            'level' => 6,
            'enabled' => false,
            'arr' => [2, 3],
        ]);
        $res->assertStatus(200);

        $this->assertDatabaseHas('options', [
            'key' => 'example',
        ]);
        $this->json('POST', 'getExampleOptions')->assertJson([
            'level' => 6,
            'enabled' => false,
            'arr' => [2, 3],
        ]);
    }
}
