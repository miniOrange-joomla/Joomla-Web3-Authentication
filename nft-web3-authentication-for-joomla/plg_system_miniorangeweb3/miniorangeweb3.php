<?php

/**
 * @package     Joomla.System
 * @subpackage  plg_system_miniorangeweb3
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Uri\Uri;

jimport( 'joomla.plugin.plugin' );
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_web3' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo-web3-utility.php';


class plgSystemminiorangeweb3 extends CMSPlugin
{
 public function onAfterInitialise()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $post = $input->post->getArray();

        if (!$this->isMoWeb3TablesExist()) {
            return;
        }

        if (isset($post['mojsp_feedback']) || isset($post['mojspfree_skip_feedback'])) {
            $deactivate_plugin = $post['deactivate_plugin'] ?? 'Skipped';
            $query_feedback = $post['query_feedback'] ?? 'N/A';
            $feedback_email = $post['feedback_email'] ?? 'Not Provided';

            $configTable = '#__miniorange_web3_config_settings';
            $customerTable = '#__miniorange_web3_customer_details';

            $util = new Mo_Web3_Local_Util();
            $util->generic_update_query($configTable, ['uninstall_feedback' => 1]);
            $customerData = $util->_load_db_values($customerTable);

            $jConfig = new JConfig();
            $fallbackEmail = $jConfig->mailfrom;
            $admin_email = $customerData['admin_email'] ?? $fallbackEmail;
            $admin_phone = $customerData['admin_phone'] ?? '';
            $data = $deactivate_plugin . ' : ' . $query_feedback;

            $helperPath = JPATH_ADMINISTRATOR . '/components/com_miniorange_web3/helpers/mo-web3-utility.php';
            if (file_exists($helperPath)) {
                include_once $helperPath;
                Mo_Web3_Local_Util::web3_submit_feedback_form($data, $feedback_email);
            }

            include_once JPATH_SITE . '/libraries/src/Installer/Installer.php';

            if (isset($post['result']) && is_array($post['result'])) {
                foreach ($post['result'] as $fbkey) {
                    $types = Mo_Web3_Local_Util::load_value_db('#__extensions', 'loadColumn', 'type', 'extension_id', $fbkey);
                    foreach ($types as $type) {
                        if ($type) {
                            $installer = new Installer();
                            $installer->uninstall($type, $fbkey, 0);
                        }
                    }
                }
            }
        }
    } 

function isMoWeb3TablesExist()
{
    $db = Factory::getDbo();
    $tablesToCheck = [
        $db->replacePrefix('#__miniorange_web3_config_settings'),
        $db->replacePrefix('#__miniorange_web3_customer_details')
    ];

    foreach ($tablesToCheck as $table) {
        $query = "SHOW TABLES LIKE " . $db->quote($table);
        $db->setQuery($query);
        if ($db->loadResult()) {
            return true;
        }
    }

    return false;
}
 
 function onExtensionBeforeUninstall($id)
    {        
        $post = Factory::getApplication()->input->post->getArray();
        $tables = Factory::getDbo()->getTableList();
        $results = Mo_Web3_Local_Util::load_value_db('#__extensions', 'loadColumn', 'extension_id', 'element', 'com_miniorange_web3');
        $tables = Factory::getDbo()->getTableList();
        $tab = 0;
        foreach ($tables as $table) {
            if (strpos($table, "miniorange_web3_config"))
                $tab = $table;
        }

        if ($tab) {
            $fid = new Mo_Web3_Local_Util();
            $fid = $fid->_load_db_values('#__miniorange_web3_config_settings');
            $fid = $fid['uninstall_feedback'];
            $tpostData = $post;
            if ($fid == 0) {
                foreach ($results as $result) {
                    if ($result == $id) {?>
                        <link rel="stylesheet" type="text/css" href="<?php echo Uri::base();?>/components/com_miniorange_web3/assets/css/mo_web3_style.css" />
                        <div class="form-style-6 " style="width:35% !important; margin-left:33%; margin-top: 4%;">
                            <h1> Feedback form for Joomla Web3 SP</h1>
                            <form name="f" method="post" action="" id="mojsp_feedback" style="background: #f3f1f1; padding: 10px;">
                                <h3>What Happened? </h3>
                                <input type="hidden" name="mojsp_feedback" value="mojsp_feedback"/>
                                <div>
                                    <p style="margin-left:2%">
                                        <?php
                                        $deactivate_reasons = array(
                                            "Facing issues During Registration",
                                            "Does not have the features I'm looking for",
                                            "Not able to Configure",
                                            "I found a better plugin",
                                            "It's a temporary deactivation",
                                            "The plugin didn't working",
                                            "Other Reasons:"
                                        );
                                        foreach ($deactivate_reasons as $deactivate_reasons) { ?>
                                    <div class="radio" style="padding:1px;margin-left:2%">
                                        <label style="font-weight:normal;font-size:14.6px;font-family: cursive;" for="<?php echo $deactivate_reasons; ?>">
                                            <input type="radio" name="deactivate_plugin" value="<?php echo $deactivate_reasons; ?>" required>
                                            <?php echo $deactivate_reasons; ?></label>
                                    </div>

                                    <?php } ?>
                                    <br>

                                    <textarea id="query_feedback" name="query_feedback" rows="4" style="margin-left:3%;width: 91%" cols="50" placeholder="Write your query here"></textarea><br><br><br>
                                    <tr>
                                        <td width="20%"><strong>Email<span style="color: #ff0000;">*</span>:</strong></td>
                                        <td><input type="email" name="feedback_email" required placeholder="Enter email to contact." style="width:55%"/></td>
                                    </tr>

                                    <?php
                                    foreach ($tpostData['cid'] as $key) { ?>
                                        <input type="hidden" name="result[]" value=<?php echo $key ?>>
                                    <?php } ?>
                                    <br><br>
                                    <div class="mojsp_modal-footer">
                                        <input style="cursor: pointer;font-size: large;" type="submit" name="miniorange_feedback_submit" class="mo_boot_button mo_boot_button-primary mo_boot_button-large" value="Submit"/>
                                    </div>
                                </div>
                            </form>
                            <form name="f" method="post" action="" id="mojspfree_feedback_form_close">
                                <input type="hidden" name="mojspfree_skip_feedback" value="mojspfree_skip_feedback"/>
                                <div style="text-align:center">
                                    <a href="#" onClick="skipWeb3Form()">Skip Feedback</a>
                                </div>
                                <?php
                                    foreach ($tpostData['cid'] as $key) { ?>
                                        <input type="hidden" name="result[]" value=<?php echo $key ?>>
                                    <?php }
                                ?>
                            </form>
                        </div>
                        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
                        <script>
                            jQuery('input:radio[name="deactivate_plugin"]').click(function () {
                                var reason = jQuery(this).val();
                                jQuery('#query_feedback').removeAttr('required')

                                if (reason === 'Facing issues During Registration') {
                                    jQuery('#query_feedback').attr("placeholder", "Can you please describe the issue in detail?");
                                } else if (reason === "Does not have the features I'm looking for") {
                                    jQuery('#query_feedback').attr("placeholder", "Let us know what feature are you looking for");
                                } else if (reason === "I found a better plugin"){
                                    jQuery('#query_feedback').attr("placeholder", "Can you please name that plugin which one you feel better.");
                                }else if (reason === "The plugin didn't working"){
                                    jQuery('#query_feedback').attr("placeholder", "Can you please let us know which plugin part you find not working.");
                                } else if (reason === "Other Reasons:" || reason === "It's a temporary deactivation" ) {
                                    jQuery('#query_feedback').attr("placeholder", "Can you let us know the reason for deactivation");
                                    jQuery('#query_feedback').prop('required', true);
                                } else if (reason === "Not able to Configure") {
                                    jQuery('#query_feedback').attr("placeholder", "Not able to Configure? let us know so that we can improve the interface");
                                }
                            });

                            function skipWeb3Form(){
                                jQuery('#mojspfree_feedback_form_close').submit();
                            }
                        </script>
                        <?php
                        exit;
                    }
                }
            }
        }
    }
		
}

