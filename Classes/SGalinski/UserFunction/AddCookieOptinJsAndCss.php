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

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class AddCookieOptinJsAndCss
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
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
        protected function getStoragePid(): int
        {

            try {

                /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
                $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);

                /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
                $configurationManager = $objectManager->get(ConfigurationManager::class);
                $settings = $configurationManager->getConfiguration(
                    ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
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
         * Returns always the first page within the rootline
         *
         * @return int
         */
        protected function getRootPageId(): int
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
