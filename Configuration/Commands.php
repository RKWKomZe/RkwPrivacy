<?php

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('sg_cookie_optin')) {
    return [
        'rkw_privacy:generateStaticFiles' => [
            'class' => \RKW\RkwPrivacy\Command\GenerateStaticFilesCommand::class,
            'schedulable' => true,
        ],
    ];
} else {
    return [];
}
