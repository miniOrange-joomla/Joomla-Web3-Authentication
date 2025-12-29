<?php
/*
* @package    miniOrange
* @subpackage Plugins
* @license    GNU/GPLv3
* @copyright  Copyright 2023 miniOrange. All Rights Reserved.
*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;

/**
 * Script file of miniorange_web3_system_plugin.
 *
 * The name of this class is dependent on the component being installed.
 * The class name should have the component's name, directly followed by
 * the text InstallerScript (ex:. com_helloWorldInstallerScript).
 *
 * This class will be called by Joomla!'s installer, if specified in your component's
 * manifest file, and is used for custom automation actions in its installation process.
 *
 * In order to use this automation script, you should reference it in your component's
 * manifest file as follows:
 * <scriptfile>script.php</scriptfile>
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class pkg_MiniorangeWeb3InstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent)
    {
        require_once JPATH_ADMINISTRATOR . '/components/com_miniorange_web3/helpers/mo-web3-utility.php';
        $siteName = $_SERVER['SERVER_NAME'];
        $email = Factory::getConfig()->get('mailfrom'); 
        $moPluginVersion = Mo_Web3_Local_Util::GetPluginVersion();
        $jCmsVersion = Mo_Web3_Local_Util::getJoomlaCmsVersion();
        $phpVersion = phpversion();
        $currentUser = Factory::getUser();
        $currentUserEmail = $currentUser->email;
        $query1 = '[Plugin ' . $moPluginVersion . ' | PHP ' . $phpVersion .' | Joomla Version '. $jCmsVersion .']';
        $content = '<div>
            Hello,<br><br>
            Plugin has been successfully installed on the following site.<br><br>
            <strong>Company:</strong> <a href="http://' . $siteName . '" target="_blank">' . $siteName . '</a><br>
            <strong>Admin Email:</strong> <a href="mailto:' . $currentUserEmail . '">' . $currentUserEmail . '</a><br>
            <strong>System Information:</strong> ' . $query1 . '<br><br>
        </div>';
        Mo_Web3_Local_Util::send_web3_test_mail($currentUserEmail, $content);
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent)
    {

    }

    /**
     * This method is called after a component is updated.
     *
     * @param \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent)
    {

    }

    /**
     * Runs just before any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param string $type - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param string $type - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    function postflight($type, $parent)
    {
        if ($type == 'uninstall') {
            return true;
        }
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__extensions'))
            ->set($db->quoteName('enabled') . ' = 1')
            ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('miniorangeweb3'))
            ->where($db->quoteName('folder') . ' = ' . $db->quote('system'));
        $db->setQuery($query);
        $db->execute();
        $this->showInstallMessage('');
    }

    protected function showInstallMessage($messages = array())
    {
        jimport('miniorangeweb3plugin.utility.Web3_Utilities');
        $PluginVersion = Web3_Utilities::GetPluginVersion();
        ?>


        <style>

            .mo-row {
                width: 100%;
                display: block;
                margin-bottom: 2%;
            }

            .mo-row:after {
                clear: both;
                display: block;
                content: "";
            }

            .mo-column-2 {
                width: 19%;
                margin-right: 1%;
                float: left;
            }

            .mo-column-10 {
                width: 80%;
                float: left;
            }

            .mo_boot_btn {
                display: inline-block;
                font-weight: 400;
                text-align: center;
                vertical-align: middle;
                user-select: none;
                background-color: transparent;
                border: 1px solid transparent;
                padding: 4px 12px;
                font-size: 0.85rem;
                line-height: 1.5;
                border-radius: 0.25rem;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .mo_boot_btn-saml {
                color: white!important;
                background-color: #001b4c;
                border-color: #226a8b;
            }

            .mo_boot_btn-saml:hover {
                color: white!important;
                background-color: #001b4c;
            }

            .mo_boot_btn-saml:focus, .mo_boot_btn-saml.mo_boot_focus {
                box-shadow: 0 0 0 0.2rem #163c4e;
            }

            .mo_boot_btn-saml.mo_boot_disabled, .mo_boot_btn-saml:disabled {
                color: #fff;
                background-color: #163c4e;
                border-color: #163c4e;
            }

        </style>
        <p>Plugin package for miniOrange Web3 Login in Joomla</p>
        <p>Our plugin is compatible with Joomla 3,4 and 5.</p>
        <h4>What this plugin does?</h4>
        <p>The Joomla Web3 authentication plugin allows for Secure Login with a secure decentralized system into your
            Joomla website using blockchain wallets such as MetaMask, Trust Wallet, Wallet Connect QR code, Phantom,
            Pera, Ledger, and Trezor or any EVM Compatible Wallet and restricts access to content based on non-fungible
            tokens (NFTs)</p>        
        <div class="mo-row">
            <a class="mo_boot_btn mo_boot_btn-saml" onClick="window.location.reload();"
               href="index.php?option=com_miniorange_web3&tab=overview">Get Started!</a>
            <a class="mo_boot_btn mo_boot_btn-saml" href="https://plugins.miniorange.com/web3-wallet-login-nft-token-gating-for-joomla"
               target="_blank">Configuration Guide!</a>
            <a class="mo_boot_btn mo_boot_btn-saml" href="https://www.miniorange.com/contact" target="_blank">Get
                Support!</a>
        </div>
        <?php
    }

}