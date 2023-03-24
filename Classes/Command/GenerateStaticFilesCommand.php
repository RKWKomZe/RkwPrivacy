<?php
namespace RKW\RkwPrivacy\Command;

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

use SGalinski\SgCookieOptin\Service\StaticFileGenerationService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Madj2k\CoreExtended\Utility\FrontendSimulatorUtility;

/**
 * Class GenerateStaticFilesCommand
 *
 *  Execute on CLI with: 'vendor/bin/typo3 rkw_privacy:generateStaticFiles'
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwPrivacy
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class GenerateStaticFilesCommand extends \SGalinski\SgCookieOptin\Command\GenerateStaticFilesCommand {

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setHelp('Generates the necessary JavaScript, JSON and CSS files.' . LF . 'If you want to get more detailed information, use the --verbose option.');
        $this->setDescription('Generates the necessary JavaScript, JSON and CSS files for ext:sg_cookie_option - including inheritance.');
    }


	/**
	 * Executes the command for showing sys_log entries
	 *
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int error code
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->io = new SymfonyStyle($input, $output);
            $this->io->title($this->getDescription());
            Bootstrap::initializeBackendAuthentication();

            $navigationOverride = '';
            foreach ($this->getAllRootpageUids() as $siteRootId) {

                FrontendSimulatorUtility::simulateFrontendEnvironment($siteRootId);
                $settings = $this->getSettings();
                FrontendSimulatorUtility::resetFrontendEnvironment();

                $storagePid = $siteRootId;
                if ($settings['sgCookieOptIn']['storagePid']) {
                    $storagePid = $settings['sgCookieOptIn']['storagePid'];
                }

                if ($settings['dataProtectionPid'] && $settings['imprintPid']) {
                    $navigationOverride = implode(',',[intval($settings['dataProtectionPid']), intval($settings['imprintPid'])]);
                }

                $originalRecord = $this->getOriginalRecord($storagePid);

                if ($navigationOverride) {
                    $originalRecord['navigation'] = $navigationOverride;
                }

                $service = GeneralUtility::makeInstance(StaticFileGenerationService::class);
                $service->generateFiles($siteRootId, $originalRecord);
            }

        } catch (\Exception $exception) {
            $this->io->writeln('Error!');
            $this->io->writeln($exception->getMessage());
            return 1;
        }

        $this->io->writeln('Your files have been generated successfully');
        return 0;
    }


    /**
     * Get all rootpage-uid
     * @return array
     */
    protected function getAllRootpageUids(): array
    {

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = $objectManager->get(\TYPO3\CMS\Core\Site\SiteFinder::class);

        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $uidArray = [];
        foreach ($siteFinder->getAllSites() as $site) {
            if ($uid = $site->getRootPageId()) {
                $uidArray[] = $uid;
            }
        }
        return $uidArray;
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(string $which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS): array
    {
        return \Madj2k\CoreExtended\Utility\GeneralUtility::getTypoScriptConfiguration('rkwPrivacy', $which);
    }

}
