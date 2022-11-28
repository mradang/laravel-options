<?php

namespace mradang\LaravelOptions\Test;

use mradang\LaravelOptions\Option;

class FeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testBasicFeatures()
    {
        // 不存在
        $this->assertNotTrue(Option::has('foo'));

        // 默认值
        $this->assertSame(Option::get('foo'), null);
        $this->assertSame(Option::get('foo', 6), 6);

        // 单写
        Option::set('foo', 'bar');
        $this->assertTrue(Option::has('foo'));
        $this->assertSame(Option::get('foo'), 'bar');

        // 改写
        Option::set('foo', 'baz');
        $this->assertSame(Option::get('foo'), 'baz');
        $this->assertSame(Option::get('foo', 'eee'), 'baz');

        // 单删
        Option::remove('foo');
        // 不存在
        $this->assertNotTrue(Option::has('foo'));

        // 多写
        Option::set(['foo' => 'bar', 'bar' => [
            'item1' => 'baz1',
            'item2' => 'baz2',
        ]]);
        $this->assertSame(Option::get('foo'), 'bar');
        $this->assertSame(Option::get('bar'), [
            'item1' => 'baz1',
            'item2' => 'baz2',
        ]);

        // 多读
        $this->assertSame(Option::get(['foo', 'bar']), [
            'foo' => 'bar',
            'bar' => [
                'item1' => 'baz1',
                'item2' => 'baz2',
            ],
        ]);

        // 多删
        Option::remove(['foo', 'bar']);
        $this->assertDatabaseMissing('options', [
            'key' => 'foo',
        ]);
        $this->assertNotTrue(Option::has('bar'));

        // setting 服务默认值
        $this->assertDatabaseMissing('options', [
            'key' => 'user_setting_default_title_level',
        ]);
        $this->assertEquals(UserSettingService::get('default_title_level'), 3);

        // setting 服务改写
        UserSettingService::set(['default_title_level' => 5]);
        $this->assertEquals(UserSettingService::get('default_title_level'), 5);
        $this->assertDatabaseHas('options', [
            'key' => 'user_setting_default_title_level',
        ]);

        // setting 服务验证
        try {
            UserSettingService::set(['default_title_level' => 'test']);
        } catch (\Exception $e) {
            $this->assertEquals($e->status, 422);
        }
    }
}
