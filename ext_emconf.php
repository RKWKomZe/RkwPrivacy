<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "rkw_privacy"
 *
 * Auto generated by Extension Builder 2018-12-03
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'RKW Privacy',
    'description' => 'Extension for Compliance with General Data Protection Regulation (GDPR)',
    'category' => 'plugin',
    'author' => 'Steffen Kroggel',
    'author_email' => 'developer@steffenkroggel.de',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '9.5.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'sg-cookie-optin' => '2.0.0-2.0.99'
        ],
    ],
];
