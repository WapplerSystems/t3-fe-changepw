<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'fe change password',
    'description' => 'Change password form for frontend users based on form',
    'category' => 'plugin',
    'author' => 'Sven Wappler',
    'author_email' => '',
    'author_company' => 'WapplerSystems',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '12.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'form' => '12.4.0-12.4.99',
            'form_extended' => '12.0.0',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
