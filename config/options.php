<?php

return [

    // 选项
    'example' => [
        // 子项
        'level' => [
            // 校验规则
            'rule' => 'nullable|integer',
            // 默认值
            'default' => 5,
        ],
        'enabled' => [
            'rule' => 'nullable|boolean',
            'default' => true,
        ],
        'arr' => [
            'rule' => 'array',
            'default' => [],
        ]
    ],

];
