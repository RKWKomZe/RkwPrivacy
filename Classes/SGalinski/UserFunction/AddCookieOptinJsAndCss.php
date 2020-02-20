<?php

namespace RKW\RkwPrivacy\SGalinski\UserFunction;

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
 * Class AddCookieOptinJsAndCss
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwPrivacy
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('sg_cookie_optin')) {

    class AddCookieOptinJsAndCss extends \SGalinski\SgCookieOptin\UserFunction\AddCookieOptinJsAndCss
    {

        /**
         * Gets configured storage pid
         *
         * @return int
         */
        protected function getStoragePid()
        {

            try {

                /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
                $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

                /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
                $configurationManager = $objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class);
                $settings = $configurationManager->getConfiguration(
                    \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                    'rkwPrivacy'
                );

                if ($settings['sgCookieOptIn']['storagePid']) {
                    if ($pid = intval($settings['sgCookieOptIn']['storagePid'])) {
                        return $pid;
                    }
                }

            } catch (\Exception $e) {
                // do nothing
            }

            return 0;

        }

        /**
         * Returns true, if a configuration is on the given page id.
         *
         * @param int $pageUid
         *
         * @return boolean
         */
        protected function isConfigurationOnPage($pageUid)
        {
            $pageUid = (int)$pageUid;
            if ($pageUid <= 0) {
                return false;
            }

            if ($confPid = $this->getStoragePid()) {
                $pageUid = $confPid;
            }

            return parent::isConfigurationOnPage($pageUid);
        }


        /**
         * Returns always the first page within the rootline
         *
         * @return int
         */
        protected function getRootPageId()
        {
            if ($this->rootpage === null) {

                if ($siteRootId = $this->getStoragePid()) {
                    $this->rootpage = $siteRootId;
                }
            }

            return parent::getRootPageId();
        }
    }
}