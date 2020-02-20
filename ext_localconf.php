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

        //=================================================================
        // Override some classes in sg_cookie_optin
        //=================================================================
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('sg_cookie_optin')) {


            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['SGalinski\\SgCookieOptin\\UserFunction\\AddCookieOptinJsAndCss'] = array(
                'className' => 'RKW\\RkwPrivacy\\SGalinski\\UserFunction\\AddCookieOptinJsAndCss'
            );


            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['SGalinski\\SgCookieOptin\\Hook\\GenerateFilesAfterTcaSave'] = array(
                'className' => 'RKW\\RkwPrivacy\\SGalinski\\Hook\\GenerateFilesAfterTcaSave'
            );
        }

    },
    $_EXTKEY
);
