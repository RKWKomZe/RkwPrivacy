<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey) {

        //=================================================================
        // Configure Plugins
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Header',
            [
                'Header' => 'show'
            ],
            // non-cacheable actions
           [
               'Header' => 'show'
           ]
        );
    },
    'rkw_privacy'
);
