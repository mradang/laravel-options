<?php

namespace mradang\LaravelOptions\Test;

use mradang\LaravelOptions\Controllers\OptionsController;
use mradang\LaravelOptions\Facade;

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

        $this->assertSame(Facade::get('example'), [
            'level' => 5,
            'enabled' => true,
            'arr' => [],
        ]);

        Facade::set('foo', 'bar');
        $this->assertSame(Facade::get('foo'), 'bar');

        Facade::set(['foo' => 'bar', 'bar' => 'baz']);
        $this->assertSame(Facade::get(['foo', 'bar']), [
            'bar' => 'baz',
            'foo' => 'bar',
        ]);

        Facade::set('arr', [1, 3, 5]);

        $this->assertSame(Facade::get('arr'), [1, 3, 5]);

        $this->assertSame(Facade::get('baz', 'abc'), 'abc');

        $this->assertTrue(Facade::has('bar'));
        $this->assertNotTrue(Facade::has('baz'));

        Facade::remove(['foo', 'bar']);
        $this->assertNotTrue(Facade::has('foo'));
        $this->assertNotTrue(Facade::has('bar'));

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
