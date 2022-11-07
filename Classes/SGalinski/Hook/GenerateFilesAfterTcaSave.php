<?php

namespace RKW\RkwPrivacy\SGalinski\Hook;

use SGalinski\SgCookieOptin\Service\ExtensionSettingsService;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class GenerateFilesAfterTcaSave
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwPrivacy
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('sg_cookie_optin')) {

    class GenerateFilesAfterTcaSave extends \SGalinski\SgCookieOptin\Hook\GenerateFilesAfterTcaSave
    {

        /**
         * Generates the files out of the TCA data.
         *
         * @param DataHandler $dataHandler
         * @return void
         * @throws \TYPO3\CMS\Core\Error\Http\PageNotFoundException
         * @throws \TYPO3\CMS\Core\Error\Http\ServiceUnavailableException
         * @throws \TYPO3\CMS\Core\Http\ImmediateResponseException
         * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
         */
        public function processDatamap_afterAllOperations(DataHandler $dataHandler)
        {

            parent::processDatamap_afterAllOperations($dataHandler);

            $folder = ExtensionSettingsService::getSetting(ExtensionSettingsService::SETTING_FOLDER);
            if (!$folder) {
                return;
            }

            $path = Environment::getPublicPath() . '/'  . $folder;
            if (is_dir($path)) {

                $dir = new \DirectoryIterator($path);
                foreach ($dir as $info) {
                    if (
                        (!$info->isDot())
                        && ($info->isDir()))
                    {
                        $subDir = new \DirectoryIterator($info->getPathname());
                        foreach ($subDir as $subInfo) {
                            if (!$subInfo->isDot()) {

                                if (
                                    (strpos($subInfo->getFilename(), 'cookieOptin') === 0)
                                    && ($subInfo->getExtension() == 'js')
                                ) {
                                    $this->replaceFooterLinks($subInfo->getPath() . DIRECTORY_SEPARATOR . $subInfo->getFilename());
                                }
                            }
                        }
                    }
                }

                GeneralUtility::fixPermissions($path, true);
            }
        }


        /**
         * Replaces footer-links variable in JS
         *
         * @param string $file
         */
        protected function replaceFooterLinks(string $file): void
        {
            if (
                (file_exists($file))
                && ($contents = file_get_contents($file))
            ) {
                $contents = preg_replace('#var FOOTER_LINKS[^;]+;#', 'var FOOTER_LINKS = [{"url": rkwPrivacy.imprint.url + "?disableOptIn=1" ,"name": rkwPrivacy.imprint.name},{"url": rkwPrivacy.declaration.url + "?disableOptIn=1","name": rkwPrivacy.declaration.name }];', $contents);
                file_put_contents($file, $contents);
            }
        }
    }
}
