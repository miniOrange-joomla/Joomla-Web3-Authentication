<?php
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
/*
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2023 miniOrange. All Rights Reserved.
*/

HTMLHelper::_('jquery.framework');
$document = Factory::getApplication()->getDocument();
$document->addScript(Uri::base() . 'components/com_miniorange_web3/assets/js/web3_utility.js');
$document->addScript(Uri::base() . 'components/com_miniorange_web3/assets/js/bootstrap-select-min.js');
$document->addStyleSheet(Uri::base() . 'components/com_miniorange_web3/assets/css/miniorange_boot.css');
$document->addStyleSheet(Uri::base() . 'components/com_miniorange_web3/assets/css/mo_web3_style.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');


$root_url = Uri::root();
?>
    <input type="hidden" id='mo_root_url' value='<?php echo $root_url; ?>'/>
<?php

if (!Mo_Web3_Local_Util::is_curl_installed()) {
    ?>
    <div id="help_curl_warning_title" class="mo_saml_title_panel">
        <p><a class="mo_web3_cursor" target="_blank"><span class="mo_web3_red"><?php echo Text::_('COM_MINIORANGE_WEB3_WARNING'); ?></span>
                <span class="mo_web3_blue"<?php echo Text::_('COM_MINIORANGE_WEB3_CLICK_HERE'); ?>></span> <?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_A'); ?></font></a></p>
    </div>
    <div hidden="" id="help_curl_warning_desc" class="mo_saml_help_desc">
        <ul>
            <li><?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_STEP1'); ?></li>
            <li><?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_STEP2'); ?> <strong><?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_STEP3'); ?></strong></li>
            <li><?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_STEP4'); ?>(<strong>;</strong>) <?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_STEP5'); ?></li>
            <li><?php echo Text::_('COM_MINIORANGE_WEB3_WARNING_STEP6'); ?></li>
        </ul>
        <?php echo Text::_('COM_MINIORANGE_WEB3_FOR_QUERY'); ?> <a href="mailto:joomlasupport@xecurify.com"><?php echo Text::_('COM_MINIORANGE_WEB3_CONTACT_US'); ?></a>.
    </div>
    <?php
}
$tab = "overview";
$get = Factory::getApplication()->input->get->getArray();
$test_config = isset($get['test-config']) ? true : false;
if (isset($get['tab']) && !empty($get['tab'])) {
    $tab = $get['tab'];
}
$session = Factory::getSession();
$session->set('show_test_config', false);
if($test_config)
{
    $session->set('show_test_config', true);
}
?>

<div class="container-fluid m-0 p-0">
    <div class="mo_boot_row m-0 p-0">
        <div class="mo_boot_col-sm-12 mo_boot_p-0 mo_customapi_navbar">

                <a id="overviewtab" class="mo_nav-tab <?php echo $tab == 'overview' ? 'mo_nav_tab_active' : ''; ?>"
                   href="#overview_plugin"
                   onclick="add_css_tab('#overviewtab');"
                   data-toggle="tab"><i class=" fa fa-rectangle-list"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW'); ?></span>

                </a>
                <a id="idptab" class="mo_nav-tab <?php echo $tab == 'config_settings' ? 'mo_nav_tab_active' : ''; ?>"
                   href="#config_settings"
                   onclick="add_css_tab('#idptab');"
                   data-toggle="tab"><i class=" fa fa-database"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_SETTINGS'); ?></span>
                </a>

                <a id="contact_address_config_tab"
                   class="mo_nav-tab <?php echo $tab == 'contact_address_config' ? 'mo_nav_tab_active' : ''; ?>"
                   href="#contact_address_config"
                   onclick="add_css_tab('#contact_address_config_tab');"
                   data-toggle="tab"><i class="fa fa-gear"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_CONTRACT_ADDRESS_CONFIG'); ?></span>
                </a>
                <a id="conent_restriction_tab"
                   class="mo_nav-tab <?php echo $tab == 'content_restriction' ? 'mo_nav_tab_active' : ''; ?>"
                   href="#content_restriction"
                   onclick="add_css_tab('#conent_restriction_tab');"
                   data-toggle="tab"><i class="fa fa-circle-exclamation"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_NFT_CONTENT_RESTRICTION'); ?></span>
                </a>

                <a id="groupmappingtab"
                   class="mo_nav-tab <?php echo $tab == 'group_mapping' ? 'mo_nav_tab_active' : ''; ?>"
                   href="#group-mapping"
                   onclick="add_css_tab('#groupmappingtab');"
                   data-toggle="tab"><i class="fa fa-map"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING'); ?></span>
                </a>
            
                <a id="licensingtab" class="mo_nav-tab <?php echo $tab == 'licensing' ? 'mo_nav_tab_active' : ''; ?>"
                   href="#licensing-plans"
                   onclick="add_css_tab('#licensingtab');"
                   data-toggle="tab"><i class="fa fa-arrow-up"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_LICENSING'); ?></span>
                </a>

                <a id="supporttab" class="mo_nav-tab <?php echo $tab == 'support' ? 'mo_nav_tab_active' : ''; ?>"
                     href="#support"
                     onclick="add_css_tab('#supporttab'); mo_web3_local_support();"
                     data-toggle="tab"><i class="fa fa-solid fa-headset"></i><span class="tab-label"><?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_TAB'); ?></span></a>
            </div>
        </div>
    </div>


    <div class="mo_boot_col-sm-12 m-0 p-0">
            <div class="tab-content" id="myTabContent">
                <div id="overview_plugin" class="tab-pane <?php if ($tab == 'overview') echo 'active'; ?> ">
                    <?php common_classes('show_plugin_overview'); ?>
                </div>

                <div id="contact_address_config" class="tab-pane <?php if ($tab == 'contact_address_config') echo 'active'; ?> ">
                    <?php contact_address_config(); ?>
                </div>


                <div id="content_restriction" class="tab-pane <?php if ($tab == 'content_restriction') echo 'active'; ?> ">
                    <?php content_restriction(); ?>
                </div>
                
                <div id="config_settings" class="tab-pane <?php if ($tab == 'config_settings') echo 'active'; ?>">
                    <?php common_classes('select_your_wallet'); ?>
                </div>

                <div id="group-mapping" class="tab-pane <?php if ($tab == 'group_mapping') echo 'active'; ?>">
                    <?php common_classes_for_UI('group_mapping','mo_web3_adv_loginaudit'); ?>
                </div>

                <div id="request-demo" class="tab-pane <?php if ($tab == 'request_demo') echo 'active'; ?>">
                    <?php common_classes('requestfordemo'); ?>
                </div>
                <div id="support" class="tab-pane <?php if ($tab == 'support') echo 'active'; ?>">
                    <?php common_classes('mo_web3_local_support'); ?>
                </div>
                <div id="licensing-plans" class="tab-pane <?php if ($tab == 'licensing') echo 'active'; ?>">
                    <div class="row-fluid">
                        <table >
                            <caption></caption>
                            <tr>
                                <th id="s"></th>
                            </tr>
                            <tr>
                                <td class="configurationForm mo_web3_config_form">
                                    <?php Licensing_page(); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>             
            </div>
    </div>

<?php 

function common_classes_for_UI($tab_func, $add_func) { ?>
    <div class="mo_boot_row">
        <div class="mo_boot_col-sm-12"><?php $tab_func(); ?></div>
        <div class="mo_boot_col-sm-12 py-3" id="mo_saml_support2"><?php $add_func(); ?></div>
    </div>
<?php }

function common_classes($tab_func) { ?>
    <div class="mo_boot_row ">
        <div class="mo_boot_col-sm-12"><?php $tab_func(); ?></div>
    </div>
<?php } ?>
<?php

function show_plugin_overview()
{
    ?>
    <div class="mo_boot_row mo_boot_p-0 mo_boot_m-0 mo_web3_page ">
        <div class="mo_boot_col-12 mt-3">
            <h3><?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW'); ?></h3>
        </div>
        <div class="mo_boot_col-12"><br>
            <p><strong><?php echo Text::_('COM_MINIORANGE_WEB3_MINIORANGE_WEB3'); ?></strong> <?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC'); ?><br>
            <p class="mo_web3_overview_desc"><?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC_A'); ?></p>
            <?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC_B'); ?>
            <strong><?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC_C'); ?>&nbsp;</strong><?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC_D'); ?><a href="#request-demo" data-toggle='tab'><?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC_E'); ?></a>                                      <?php echo Text::_('COM_MINIORANGE_WEB3_OVERVIEW_DESC_F'); ?>
            </p>
        </div>
    </div>
    <?php
}

function contact_address_config()
{
    $edit_url = Route::_('index.php?option=com_miniorange_web3&tab=contact_address_config&canedit=').'null';

    ?>
    <div class="mo_boot_row mo_boot_p-2 mo_boot_m-0 mo_web3_page ">
        <div class="mo_boot_col-sm-12 mo_boot_mt-1">
            <div class="mo_boot_row">
                <div class="mo_boot_col-lg-10 mo_boot_mt-1 mo_web3_add_config_head">
                    <h3><?php echo Text::_('COM_MINIORANGE_WEB3_CONTRACT_CONF'); ?>
                        <a type="button" class="mo_boot_btn mo_boot_btn-success mo_web3_placeholder" data-toggle="modal"  disabled="disabled"><span>                                              <?php echo Text::_('COM_MINIORANGE_WEB3_ADD_TOKEN_DETAILS'); ?></span></a>
                    </h3>
                </div>

              
                <div class="table-responsive mo_boot_p-0 mo_boot_m-0">
  <table id="moWeb3TokenDetailsTable" class="mo_boot_table mo_boot_table-striped mo_boot_table-hover">
    <thead>
      <tr>
        <th><?php echo Text::_('COM_MINIORANGE_WEB3_CONTRACT_NAME'); ?><span class="mo_web3_red">*<span></th>
        <th><?php echo Text::_('COM_MINIORANGE_WEB3_TOKENS_ID'); ?><span class="mo_web3_red">*<span></th>
        <th><?php echo Text::_('COM_MINIORANGE_WEB3_MIN_TOKENS'); ?></th>
        <th><?php echo Text::_('COM_MINIORANGE_WEB3_TOKENS_ID'); ?>&nbsp;<?php  ?></th>
        <th><?php echo Text::_('COM_MINIORANGE_WEB3_BLOCKCHAIN'); ?><span class="mo_web3_red">*<span></th>
        <th><?php echo Text::_('COM_MINIORANGE_WEB3_ACTIONS'); ?></th>
      </tr>
    </thead>
    <tbody id="moWeb3TokenDetailsManager">
      <tr>
        <td align='center' colspan='6'>  
          <?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC'); ?>
          <a href='#' class='premium mo_web3_con_desc' onclick="moWeb3Upgrade();">
            <strong><?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC_A'); ?></strong>
          </a>
          <?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC_B'); ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
            </div>
            <hr>
        </div>
    </div>

    <?php
}

function content_restriction()
{
    $edit_url = Route::_('index.php?option=com_miniorange_web3&tab=content_restriction&cga_id=').'null';

    ?>
    <div class="mo_boot_row mo_boot_p-2 mo_boot_m-0 mo_web3_page ">
        <div class="mo_boot_col-sm-12 mo_boot_mt-1">
            <div class="mo_boot_row">
                <div class="mo_boot_col-lg-10 mo_boot_mt-1 mo_web3_add_config_head">
                    <h3><?php echo Text::_('COM_MINIORANGE_WEB3_CONTENT_GATINF_CONF'); ?>
                        <a type="button" class="mo_boot_btn mo_boot_btn-success mo_web3_placeholder" data-toggle="modal" disabled="disabled"><span><?php echo Text::_('COM_MINIORANGE_WEB3_ADD_CON_DETAILS'); ?></span></a>
                    </h3>
                </div>

                <table id="moWeb3TokenDetailsTable" class="mo_boot_table mo_boot_table-striped mo_boot_table-hover">
                    <thead>
                    <tr>
                        <th><?php echo Text::_('COM_MINIORANGE_WEB3_PAGE_URL'); ?><span class="mo_web3_red">*<span></th>
                        <th><?php echo Text::_('COM_MINIORANGE_WEB3_CON_ADD_NAME'); ?><span class="mo_web3_red">*<span></th>
                        <th><?php echo Text::_('COM_MINIORANGE_WEB3_ERROR_URL'); ?></th>
                        <th><?php echo Text::_('COM_MINIORANGE_WEB3_ACTION'); ?></th>
                    </tr>
                    </thead>
                    <tbody id="moWeb3TokenDetailsManager">
                    <tr>
                        <td align='center' colspan='6'><?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC'); ?><a href='#' class='premium mo_web3_con_desc' onclick="moWeb3Upgrade();"><strong>&nbsp;<?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC_A'); ?></strong></a>&nbsp;<?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC_B'); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr>
        </div>
    </div>


    <?php
}

function Licensing_page()
{
    $useremail = new Mo_Web3_Local_Util();
    $useremail = $useremail->_load_db_values('#__miniorange_web3_customer_details');
    if (isset($useremail)) $user_email = $useremail['email'];
    else $user_email = "xyz";
    ?>

    <div class="mo_boot_row mo_boot_p-0 mo_boot_m-0">
        <div class="tab-content mo_boot_p-0 mo_boot_m-0">
            <div class="mo_boot_col-sm-12 mo_web3_page">
                <div id="licensing_plans">
                    <div class="tab-pane active " id="license_content">
                        <div class="cd-pricing-container cd-has-margins"><br>
                            <ul class="cd-pricing-list cd-bounce-invert">
                                <li class="cd-black">
                                    <ul class="cd-pricing-wrapper">
                                        <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible cd-singlesite mo_web3_standard_plan">
                                            <header class="cd-pricing-header mo_boot_p-4">
                                                <h2><?php echo Text::_('COM_MINIORANGE_WEB3_STANDARD'); ?><br/></h2><span class="mo_saml_plan_description"><strong> <?php echo Text::_('COM_MINIORANGE_WEB3_STANDARD_DESC'); ?></strong></span><br>
                                            </header> <!-- .cd-pricing-header -->
                                            <div class="mo_web3_disp_no">
                                                <span id="plus_total_price">$99*</span><br><span class="mo_saml_note"><strong>                                 <?php echo Text::_('COM_MINIORANGE_WEB3_ONE_TIME_PAYMENT'); ?></strong></span><br/>
                                            </div>
                                            <footer class="mo_web3_center">
                                                <?php
                                                    $redirect1 = "https://login.xecurify.com/moas/login?username=" . $user_email . "&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_web3_auth_standard_plan";
                                                    echo '<a target="_blank" class="cd-select mo_web3_upgrade_btn" href="' . $redirect1 . '">' .Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW'). '</a>';
                                                ?>
                                            </footer>
                                        
                                            <div class="cd-pricing-body">
                                                <ul class="cd-pricing-features">
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_A'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_B'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_C'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_D'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_E'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_F'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_G'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_H'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_I'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_J'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_K'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_L'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_M'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_N'); ?></li>
                                                    <li class="mo_pricing_list">&#10060;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_O'); ?></li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul> <!-- .cd-pricing-wrapper -->
                                </li>
                                <li class="cd-black">
                                    <ul class="cd-pricing-wrapper">
                                        <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible">
                                            <header class="cd-pricing-header mo_boot_p-4">
                                                <h2><?php echo Text::_('COM_MINIORANGE_WEB3_CON_DESC_A'); ?><br/></h2><span class="mo_saml_plan_description"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_PREMIUM_DESC'); ?></strong></span><br/>
                                            </header> <!-- .cd-pricing-header -->
                                            <div class="mo_web3_disp_no">
                                                <span id="plus_total_price">$199*</span><br><span class="mo_saml_note"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_ONE_TIME_PAYMENT'); ?></strong></span><br/>
                                            </div>
                                            <footer class="mo_web3_center">
                                                <?php
                                                    $redirect1 = "https://login.xecurify.com/moas/login?username=" . $user_email . "&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_web3_auth_premium_plan";
                                                    echo '<a target="_blank" class="cd-select mo_web3_upgrade_btn" href="' . $redirect1 . '" >'.Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW').'</a>';
                                                ?>
                                            </footer>
                                            <div class="cd-pricing-body">
                                                <ul class="cd-pricing-features">
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_A'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_B'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_C'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_D'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_E'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_F'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_G'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_H'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_I'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_J'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_K'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_L'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_M'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_N'); ?></li>
                                                    <li class="mo_pricing_list">&#9989;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_UPGRADE_NOW_DESC_O'); ?></li>
                                                </ul>
                                            </div> <!-- .cd-pricing-body -->
                                        </li>
                                    </ul> <!-- .cd-pricing-wrapper -->
                                </li>
                            </ul> <!-- .cd-pricing-list -->
                        </div> <!-- .cd-pricing-container -->
                    </div>
                    <br>
                </div>
            </div>
            <!-- Modal -->

            <?php echo showAddonsContent(); ?>
            <div class=" mo_web3_upgrade_desc "id="upgrade-steps">
                <div class="mo_web3_upgrade_steps">
                    <h2><?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE'); ?></h2>
                </div><hr><br><br>
                <section id="section-steps">
                    <div class="mo_boot_col-sm-12 mo_boot_row ">
                        <div class=" mo_boot_col-sm-6 works-step">
                            <div class="mo_web3_step_no"><strong>1</strong></div>
                            <p>
                                <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_A'); ?> <strong><em><?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_B'); ?></em></strong> <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_C'); ?>

                                <strong><em><?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_B'); ?></em></strong> <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_D'); ?><span stye="margin-left:10px"></span> <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_E'); ?>
                            </p>
                        </div>
                        <div class="mo_boot_col-sm-6 works-step">
                            <div class="mo_web3_step_no"><strong>4</strong></div>
                            <p>
                                <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_2A'); ?> <strong><em><?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_2B'); ?></em></strong> <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_2C'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="mo_boot_col-sm-12 mo_boot_row ">
                        <div class="mo_boot_col-sm-6 works-step">
                            <div class="mo_web3_step_no"><strong>2</strong></div>
                            <p>
                                <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_3A'); ?> <em><strong><a href="https://portal.miniorange.com/login"><?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_3B'); ?></a></strong></em>
                                <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_3C'); ?>
                            </p>
                        </div>
                        <div class="mo_boot_col-sm-6 works-step">
                            <div class="mo_web3_step_no"><strong>5</strong></div>
                            <p>
<?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_4A'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="mo_boot_col-sm-12 mo_boot_row ">
                        <div class="mo_boot_col-sm-6 works-step">
                            <div class="mo_web3_step_no"><strong>3</strong></div>
                            <p>
                                <?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_5A'); ?>
                            </p>
                        </div>
                        <div class="mo_boot_col-sm-6 works-step">
                            <div class="mo_web3_step_no"><strong>6</strong></div>
                            <p><?php echo Text::_('COM_MINIORANGE_WEB3_HOW_UPGRADE_PRE_6A'); ?><br><br></p>
                        </div>
                    </div>
                </section>
            </div>
         
            <!--Don't delete below function call-->
            <div class=" mo_web3_upgrade_desc mo_web3_upgrade_steps mo_web3_page mo_boot_pb-3 ">
                <h2><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ'); ?></h2>
		        <div class="mo_boot_mx-4">
		            <div class="mo_boot_row">
			            <div class="mo_boot_col-sm-6">
				            <h3 class="mo_web3_faq_page"><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_A'); ?></h3>
				            <div class="mo_web3_faq_body">
					            <p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_A1'); ?></p>
				            </div>
				            <hr class="mo_web3_hr_line">
			            </div>
		
			            <div class="mo_boot_col-sm-6">
				            <h3 class="mo_web3_faq_page"><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_B'); ?></h3>
				            <div class="mo_web3_faq_body">
					            <p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_B1'); ?></p>
			    	        </div>
				            <hr class="mo_web3_hr_line">
			            </div>
		            </div>
		
                    <div class="mo_boot_row">
			            <div class="mo_boot_col-sm-6">
				            <h3 class="mo_web3_faq_page"><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_C'); ?></h3>
				            <div class="mo_web3_faq_body">
					            <p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_C1'); ?></p>
				            </div>
				            <hr class="mo_web3_hr_line">
			            </div>

			            <div class="mo_boot_col-sm-6">
				            <h3 class="mo_web3_faq_page"><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_D'); ?></h3>
				            <div class="mo_web3_faq_body">
					            <p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_D1'); ?></p>
				            </div>
				            <hr class="mo_web3_hr_line">
			            </div>
		            </div>
		            
                    <div class="mo_boot_row">	
			            <div class="mo_boot_col-sm-6">
				            <h3 class="mo_web3_faq_page"><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_E'); ?></h3>
				            <div class="mo_web3_faq_body">
					            <p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_E1'); ?></p>
				            </div>
				            <hr class="mo_web3_hr_line">
			            </div>
			            <div class="mo_boot_col-sm-6">
				            <h3 class="mo_web3_faq_page"><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_F'); ?></h3>
				            <div class="mo_web3_faq_body">
					            <p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_F1'); ?></p><br><p><?php echo Text::_('COM_MINIORANGE_WEB3_FAQ_F2'); ?></p>
				            </div>
				            <hr class="mo_web3_hr_line">
                        </div>
       	            </div>
				</div>
		        <script>
			        var test = document.querySelectorAll('.mo_web3_faq_page');
			            test.forEach(function(header) {
				        header.addEventListener('click', function() {
					    var body = this.nextElementSibling;
					    body.style.display = body.style.display === 'none' || body.style.display =="" ? 'block' : 'none';
  			        	});
			        });
		        </script>
            </div>

    </div>
   

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            jQuery('[data-toggle="popover"]').popover();
        });
    </script>
    <?php
}

function showAddonsContent()
{

    define("MO_ADDONS_CONTENT", serialize(array(

        "JOOMLA_ICB" => [
            'id' => 'mo_joomla_icb',
            'addonName' => Text::_('COM_MINIORANGE_WEB3_INTEGRATE_WITH_BUILDER'),
            'addonDescription' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_A'),
            'addonLink' => 'https://www.miniorange.com/contact',
        ],
        "JOOMLA_IP_RESTRICT" => [
            'id' => 'mo_joomla_ip_rest',
            'addonName' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_C'),
            'addonDescription' =>Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_D'),
            'addonLink' => 'https://plugins.miniorange.com/media-restriction-in-joomla',
        ],
        "JOOMLA_USER_SYNC_OKTA" => [
            'id' => 'mo_joomla_okta_sync',
            'addonName' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_E'),
            'addonDescription' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_F'),
            'addonLink' => 'https://plugins.miniorange.com/joomla-scim-user-provisioning',
        ],
        "JOOMLA_PAGE_RESTRICTION" => [
            'id' => 'mo_joomla_page_rest',
            'addonName' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_G'),
            'addonDescription' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_H'),
            'addonLink' => 'https://plugins.miniorange.com/page-and-article-restriction-for-joomla',
        ],
        "JOOMLA_SSO_AUDIT" => [
            'id' => 'mo_joomla_audit',
            'addonName' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_I'),
            'addonDescription' =>Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_J'),
            'addonLink' => 'https://plugins.miniorange.com/joomla-login-audit-login-activity-report',
        ],
        "JOOMLA_RBA" => [
            'id' => 'mo_joomla_rba',
            'addonName' => Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_K'),
            'addonDescription' =>Text::_('COM_MINIORANGE_WEB3_PLUGIN_ADDONS_L'),
            'addonLink' => 'https://plugins.miniorange.com/role-based-redirection-for-joomla',
        ],
    )));

    $displayMessage = "";
    $messages = unserialize(MO_ADDONS_CONTENT);


    echo '<div class="mo_web3_connectivity" id="addonContent"><h2>'.Text::_('COM_MINIORANGE_WEB3_TEST_WEB3_CONNECTIVITY').'</h2><hr><br>
        <div class="mo_otp_wrapper">';
    foreach ($messages as $messageKey) {
        $message_keys = isset($messageKey['addonName']) ? $messageKey['addonName'] : '';
        $message_description = isset($messageKey["addonDescription"]) ? $messageKey["addonDescription"] : 'Hi! I am interested in the addon, could you please tell me more about this addon?';
        echo '<div id="' . $messageKey["id"] . '">
                <h3 class="mo_web3_connect">' . $message_keys . '<br /><br /></h3>                              
                <footer class="mo_web3_text_align">
                    <a type="button" class="mo_btn btn-primary mo_web3_interest_btn " href="' . $messageKey['addonLink'] . '" target="_blank">'.Text::_('COM_MINIORANGE_WEB3_INTERESTED').'</a>  
                </footer>
                <span class="cd-pricing-body">
                    <ul class="cd-pricing-features">
                        <li class="mo_web3_connect">' . $message_description . '</li>
                    </ul>
                </span>
            </div>';
    }
    echo '</div></div>';
    return $displayMessage;
}

function group_mapping()
{
    $role_mapping_key_value = array();
    $attribute = new Mo_Web3_Local_Util();
    $group_attr = '';
    $mapping_value_default = "";

    ?>
    <div class="mo_boot_row mo_boot_p-0 mo_boot_m-0 mo_web3_page ">
        <div class="mo_boot_col-sm-6  mo_boot_mt-3">
            <h3><?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING'); ?></h3>
        </div>
        <div class="mo_boot_col-sm-12  mo_boot_mt-1">
            <hr>
            <form action=""
                  name="proxy_form" method="post">
                <div class="mo_boot_row mo_boot_mt-1">
                    <div class="mo_boot_col-sm-12">
                        <input id="mo_sp_grp_enable" class="mo_saml_custom_checkbox" disabled="disabled" type="checkbox" name="enable_role_mapping" value="1">&emsp;<strong><?php echo Text::_('COM_MINIORANGE_WEB3_ENABLE_GROUP_MAPPING'); ?>                            <a href='#' class='premium' onclick="moWeb3Upgrade();"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_MAP_PREMIUM'); ?></strong></a>
                        </strong> <br>
                        <p class="mo_saml_custom_checkbox"><strong class="mo_web3_red">&emsp;&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_NOTE_A'); ?> </strong><?php echo Text::_('COM_MINIORANGE_WEB3_ENABLE_GROUP_MAPPING_NOTE'); ?></p>
                        <p class="mo_saml_custom_checkbox"><?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_DESC'); ?></p>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-3" id="mo_sp_grp_defaultgrp">

                    <div class="mo_boot_col-sm-4">
                        <p><strong><?php echo Text::_('COM_MINIORANGE_WEB3_SELECT_DEFAULT_GROUP'); ?></strong></p>
                    </div>
                    <div class="mo_boot_col-sm-4">
                        <select class="mo_boot_form-control mo_web3_width" name="mapping_value_default"
                                id="default_group_mapping">
                            <?php $noofroles = 0;

                            $db = Factory::getDbo();
                            $db->setQuery($db->getQuery(true)
                                ->select('*')
                                ->from("#__usergroups"));
                            $groups = $db->loadRowList();
                            foreach ($groups as $group) {
                                if ($group[4] != 'Super Users') {
                                    if ($mapping_value_default == $group[0]) echo '<option selected="selected" value = "' . $group[0] . '">' . $group[4] . '</option>';
                                    else echo '<option  value = "' . $group[0] . '">' . $group[4] . '</option>';
                                }
                            }
                            ?>
                        </select><br><br>
                    </div>
                    <div class="mo_boot_col-sm-4">
                        <select class="mo_web3_disp_no" id="wp_roles_list">
                            <?php
                            $db = Factory::getDbo();
                            $db->setQuery('SELECT `title`' . ' FROM `#__usergroups`');
                            $groupNames = $db->loadColumn();
                            $noofroles = count($groupNames);
                            for ($i = 0; $i < $noofroles; $i++) {
                                echo '<option  value = "' . $groupNames[$i] . '">' . $groupNames[$i] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-1 mo_boot_p-2 mo_web3_note_bg">
                    <div class="mo_boot_col-sm-12">
                        <input type="checkbox" class="mo_saml_custom_checkbox" name="disable_update_existing_users_role" value="1" disabled>&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_INSTRUCTION_A'); ?>
                        <strong>
                            <a href='#' class='premium' onclick="moWeb3Upgrade();"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_MAP_PREMIUM'); ?></strong></a>
                        </strong>
                        <br>
                        <input type="checkbox" class="mo_saml_custom_checkbox" name="disable_update_existing_users_role" value="1" disabled>&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_INSTRUCTION_B'); ?>
                        <strong> <a href='#' class='premium' onclick="moWeb3Upgrade();"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_MAP_PREMIUM'); ?></strong></a>
                        </strong><br>
                        <input type="checkbox" class="mo_saml_custom_checkbox" name="disable_create_users" value="1" disabled>&emsp;<?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_INSTRUCTION_C'); ?>
                        <strong>
                            <a href='#' class='premium' onclick="moWeb3Upgrade();"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_MAP_PREMIUM'); ?></strong></a>
                        </strong><br>
                    </div>
                </div><br>

                <div class="mo_boot_row mo_boot_mt-1 mo_web3_note_bg mo_boot_p-2">
                    <div class="mo_boot_col-sm-12">
                        <p><?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_NOTE'); ?> <a href='#' class='premium' onclick="moWeb3Upgrade();"><strong><?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_NOTE_A'); ?></strong></a> <?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_MAPPING_NOTE_B'); ?></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="mo_boot_col-sm-12 table-responsive">
            <table class="mo_saml_settings_table" id="saml_role_mapping_table">
                <caption></caption>
                <tr>
                    <th id="tt"></th>
                </tr>
                <tr>
                    <td class="mo_web3_group_name"><h3><strong><?php echo Text::_('COM_MINIORANGE_WEB3_GROUP_NAME_IN_J'); ?></strong></h3></td>
                    <td class="mo_boot_text-center mo_web3_cont_add"><h3><strong><?php echo Text::_('COM_MINIORANGE_WEB3_CONTACT_ADDRESS'); ?></strong></h3></td>
                </tr>
                <?php
                $user_role = array();
                $db = Factory::getDbo();
                $db->setQuery($db->getQuery(true)
                    ->select('*')
                    ->from("#__usergroups"));
                $groups = $db->loadRowList();
                if (empty($role_mapping_key_value)) {
                    foreach ($groups as $group) {
                        if ($group[4] != 'Super Users') {
                            echo '<tr><td><h5>' . $group[4] . '</h5></td><td><input type="text" name="saml_am_group_attr_values_' . $group[0] . '" value= "" placeholder="Semi-colon(;) separated Group/Role value for ' . $group[4] . '"  disabled class="mo_boot_form-control"' . ' /></td></tr>';
                        }
                    }
                    ?>
                    <?php
                } else {
                    $j = 1;
                    foreach ($role_mapping_key_value as $mapping_key => $mapping_value) {
                        ?>
                        <tr>
                            <td>
                                <input class="mo_saml_table_textbox mo_boot_form-control" type="text"
                                       name="mapping_key_<?php echo $j; ?>" value="<?php echo $mapping_key; ?>"
                                       placeholder="cn=group,dc=domain,dc=com"/>
                            </td>
                            <td>
                                <select name="mapping_value_<?php echo $j; ?>" id="role" class="mo_boot_form-control">
                                    <?php
                                    $db = Factory::getDbo();
                                    $db->setQuery('SELECT `title`' . ' FROM `#__usergroups`');
                                    $groupNames = $db->loadColumn();
                                    $noofroles = count($groupNames);
                                    for ($i = 0; $i < $noofroles; $i++) {
                                        if ($mapping_value == $groupNames[$i]) echo '<option selected="selected" value = "' . $groupNames[$i] . '">' . $groupNames[$i] . '</option>';
                                        else echo '<option value = "' . $groupNames[$i] . '">' . $groupNames[$i] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php $j++;
                    }
                }
                ?>
            </table>
            <div class="mo_boot_col-sm-12 mo_boot_text-center">
                <input id="mo_sp_grp_save " type="submit" class="mo_boot_btn  mo_boot_mb-3 mo_web3_btns" value="<?php echo Text::_('COM_MINIORANGE_WEB3_SAVE_SETTINGS'); ?>"
                       disabled/>
            </div>
        </div>
    </div>
    <?php
}

function requestfordemo()
{
    $current_user = Factory::getUser();
    $result = new Mo_Web3_Local_Util();
    $result = $result->_load_db_values('#__miniorange_web3_customer_details');
    $admin_email = isset($result['email']) ? $result['email'] : '';
    if ($admin_email == '') $admin_email = $current_user->email;
    ?>
    <div>
        <div class="mo_boot_row p-3 mo_web3_page">
            <div class="mo_boot_col-sm-12 mo_boot_text-center">
                <h3><?php echo Text::_('COM_MINIORANGE_WEB3_REQUEST_FOR_TRIAL'); ?></h3>
                <hr>
            </div>
            <div class="mo_boot_col-sm-12">
                <p>
                   <?php echo Text::_('COM_MINIORANGE_WEB3_REQUEST_FOR_TRIAL_A'); ?>
<br>
                    &nbsp;<?php echo Text::_('COM_MINIORANGE_WEB3_REQUEST_FOR_TRIAL_B'); ?><br>
                    &nbsp;<?php echo Text::_('COM_MINIORANGE_WEB3_REQUEST_FOR_TRIAL_C'); ?>
                    <br>
                </p>
                <strong><?php echo Text::_('COM_MINIORANGE_WEB3_NOTE_A'); ?></strong> <?php echo Text::_('COM_MINIORANGE_WEB3_DESCRIPTION_NOTE_A'); ?> <strong><?php echo Text::_('COM_MINIORANGE_WEB3_DESCRIPTION'); ?></strong> <?php echo Text::_('COM_MINIORANGE_WEB3_BELOW'); ?>
            </div>
            <div class="mo_boot_col-sm-12 mt-3">
                <form name="demo_request" method="post"
                      action="<?php echo Route::_('index.php?option=com_miniorange_web3&task=myaccount.requestForTrialPlan'); ?>">
                    <div class="mo_boot_row mt-1">
                        <div class="col-sm-4">
                            <p><span class="mo_web3_red">*</span><strong>Email: </strong></p>
                        </div>
                        <div class="col-sm-8">
                            <input type="email" class="mo_saml_table_textbox mo_boot_form-control mo_web3_placeholder" name="email" value="<?php echo $admin_email; ?>" placeholder="person@example.com" required/>
                        </div>
                        <div class="mo_boot_col-sm-12 mt-1">
                            <div class="mo_boot_row">
                                <div class="col-sm-4">
                                    <p><span class="mo_web3_red">*</span><strong><?php echo Text::_('COM_MINIORANGE_WEB3_REQUEST_FOR_TRIAL_A'); ?></strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <select class="mo_boot_form-control mo_web3_placeholder" name="plan" required>
                                        <option disabled selected class="mo_web3_select"><?php echo Text::_('COM_MINIORANGE_WEB3_SELECT'); ?></option>
                                        <option value="Joomla Web3 Standard Plugin"><?php echo Text::_('COM_MINIORANGE_WEB3_STANDARD_PLUGIN'); ?></option>
                                        <option value="Joomla Web3 Premium Plugin"><?php echo Text::_('COM_MINIORANGE_WEB3_PREMIUM_PLUGIN'); ?></option>
                                        <option value="Not Sure"><?php echo Text::_('COM_MINIORANGE_NOT_SURE'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mo_boot_col-sm-12 mt-1">
                            <div class="mo_boot_row">
                                <div class="col-sm-4">
                                    <p><span class="mo_web3_red">*</span><strong><?php echo Text::_('COM_MINIORANGE_WEB3_DESCRIPTION_A'); ?></strong></p>
                                </div>
                                <div class="col-sm-8">
                                <textarea name="description" class="form-text-control mo_web3_assistance"
                                          cols="52" rows="7" required
                                          placeholder="Need assistance? Write us about your requirement and we will suggest the relevant plan for you."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_text-center">
                        <div class="mo_boot_col-sm-12">
                            <input type="hidden" name="option1" value="mo_saml_login_send_query"/><br>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
/* End of Create Customer function */

function select_your_wallet()
{
    mo_web3_script_enqueue();
    $attribute = new Mo_Web3_Local_Util();
    $attribute = $attribute->_load_db_values('#__miniorange_web3_config_settings');
    $enable_web3_user_login = isset($attribute['enable_web3_user_login']) ? $attribute['enable_web3_user_login'] : 0;
    $enabled_crypto_wallet = json_decode($enable_web3_user_login);
    $enabled_crypto_wallet = (array)$enabled_crypto_wallet;
    $customLoginButtonText = Text::_('COM_MINIORANGE_WEB3_LOGIN_WITH_CRYPTO');

    $session = Factory::getSession();
    $current_state = $session->get('show_test_config');
    if ($current_state) {
        ?>
        <script>
            jQuery(document).ready(function () {
                var elem = document.getElementById("test-config");
                elem.scrollIntoView();
            });
        </script>
        <?php
        $session->set('show_test_config', false);
    } ?>

<div class="mo_boot_row mo_boot_mr-1 mo_boot_py-3 mo_boot_px-2 mo_web3_disp_no" id="import_export_form">

<div class="mo_boot_col-sm-12">
    <div class="mo_boot_row">
        <div class="mo_boot_col-sm-10">
            <h3><?php echo Text::_('COM_MINIORANGE_WEB3_IMPORT_EXPORT'); ?></h3></div>

        <div class="mo_boot_col-sm-2">
            <input type="button" class="mo_boot_btn mo_boot_btn-danger" value="Cancel" onclick="hide_import_export_form()"/>
        </div>
    </div><br><hr><br>
</div>

<div class="mo_boot_col-sm-12 pl-sm-4">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-6">
                    <form name="f" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_web3&task=myaccount.importexport'); ?>">
                        <h3><?php echo Text::_('COM_MINIORANGE_WEB3_EXPORT_CONFIG'); ?></h3>
                        <input id="mo_sp_exp_exportconfig" type="button" class="mo_boot_btn mo_boot_btn-primary mo_web3_btns" onclick="submit();" value="Export Configuration"/>
                    </form>
                </div>

                <div class="mo_boot_col-sm-6">
                    <h3><?php echo Text::_('COM_MINIORANGE_WEB3_IMPORT_CONFIG'); ?>
                        <sup>
                            <a href='#' class='premium' onclick="moWeb3Upgrade();"><img class="crown_img_small mo_boot_ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_web3/assets/images/crown.webp"></a>
                        </sup>
                    </h3>
                    <input type="file" class="mo_boot_form-control-file d-inline" name="configuration_file" disabled="disabled">
                </div>
            </div><br><br>
        </div>

    </div>
    <div class="mo_boot_row mo_boot_p-0 mo_boot_m-0 mo_web3_page " id="tabhead">
        <div class="mo_boot_col-sm-12">
            <form action="<?php echo Route::_('index.php?option=com_miniorange_web3&task=myaccount.saveConfig'); ?>"
                  method="post" name="adminForm" id="identity_provider_settings_form" enctype="multipart/form-data">
                <input type="hidden" name="option1" value="mo_wallet"/>
                <div class="mo_boot_row mo_boot_mt-3">
                    <div class="mo_boot_col-lg-12 mo_web3_config_heading">
                        <h3><?php echo Text::_('COM_MINIORANGE_WEB3_CONFIG_SETTING'); ?>
                        <a href="https://plugins.miniorange.com/web3-wallet-login-nft-token-gating-for-joomla" target="_blank" type="button" class="mo_boot_btn mo_boot_btn-primary mo_web3_btns"><?php echo Text::_('COM_MINIORANGE_WEB3_SETUP_GUIDE'); ?></a></h3>
                    </div>
                </div><br><hr>
                <div id="idpdata" class="ex1"></div>
                <div class="mo_boot_row mo_boot_mt-3" id="saml_login">
                    <div class="mo_boot_col-sm-8">
                        <strong><?php echo Text::_('COM_MINIORANGE_WEB3_ENABLE_WEB3'); ?><br><br></strong>
                    </div>

                    <?php

                    $multiple_crypto_wallet = Mo_Web3_Local_Util::get_multiple_crypto_wallet();
                    foreach ($multiple_crypto_wallet as $key => $value) {
                        $crypto_wallet = $value;
                        $id = $crypto_wallet['id'];
                        $function = $crypto_wallet['function'];
                        $data = $crypto_wallet['data'];
                        $name = $id;
                        $disabled = '';
                        $upgrade_license = '';
                        if ($name == 'moweb3Phantom' || $name == 'moweb3Myalgo' || $name == 'moweb3Nami' || $name == 'moweb3Typhoncip30' || $name == 'moweb3flint') {
                            $disabled = "disabled=disabled";
                            $upgrade_license = "<strong><a href='#' class='premium' onclick='moWeb3Upgrade();'><strong>" . Text::_('COM_MINIORANGE_WEB3_MAP_PREMIUM') . "</strong></a></strong>";
                        }

                        ?>
                        <div class="mo_boot_col-sm-8">
                            <input type="checkbox" name="<?php echo $name; ?>" class="mo_saml_custom_checkbox" <?php echo $disabled; ?> value="checked"
                                <?php if ($enabled_crypto_wallet && isset($enabled_crypto_wallet[$name]) && 'checked' === $enabled_crypto_wallet[$name]) {
                                    echo 'checked';
                                } ?>> <?php echo $data; ?> <?php echo $upgrade_license; ?> <br><br>
                        </div>
                        <?php
                    } ?>
                </div>

                <div class="mo_boot_row mo_boot_mt-4" id="sp_sso_url_idp">
                    <div class="mo_boot_col-sm-4">
                        <strong><span class="mo_web3_red">*</span><?php echo Text::_('COM_MINIORANGE_WEB3_CUSTOM_LOGIN_BUTTON'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-8">
                        <input class="mo_saml_table_textbox mo_boot_form-control mo_web3_placeholder" type="text"
                               placeholder="Your Custom Button Text " name="custom_login_button_text" disabled="disabled"
                               value="<?php echo $customLoginButtonText; ?>" required/>
                        <h6><strong><?php echo Text::_('COM_MINIORANGE_WEB3_NOTE_A'); ?></strong><?php echo Text::_('COM_MINIORANGE_WEB3_AVAILABLE_IN'); ?></h6>
                    </div>
                </div>

                <div class="mo_boot_row mt-4">
                    <div class="mo_boot_col-sm-12 mo_boot_text-center mo_boot_p-3">
                        <input type="submit" class="mo_boot_btn mo_boot_btn-primary mo_web3_btns" value="<?php echo Text::_('COM_MINIORANGE_WEB3_SAVE'); ?>"/>
                        <input type="button" id="test-config" <?php if (!empty($enabled_crypto_wallet)) echo "enabled";else echo "disabled"; ?> onclick="show_test_config_modal()" class="mo_boot_btn mo_web3_btns" data-target="#multipleCryptowalletModal" value="<?php echo Text::_('COM_MINIORANGE_WEB3_TEST_WEB3_CONNECTIVITY'); ?>">
                        <a href="#import_export_form" type="button" class="mo_boot_btn mo_boot_btn-primary mo_web3_btns" onclick="show_import_export()"><?php echo Text::_('COM_MINIORANGE_WEB3_IMPORT_OR_EXPORT'); ?></a>
                    </div>
                </div>
                <?php
                test_configuration();
                ?>
            </form>

            <!--Modal-->
            <div class="modal fade mo_web3_opacity" id="multipleCryptowalletModal" role="dialog"
                 aria-labelledby="multipleCryptowalletModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mo_web3_crypto" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo Text::_('COM_MINIORANGE_WEB3_CONNECT_TO_CRYPTOWALLET'); ?></h5>
                                <div id="moweb3CustomErrorMessage"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mo_boot_col-lg-1">
                <div id="my_TC_Modal" class="TC_modal">
                    <div class="TC_modal-content">
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-12 mo_boot_text-center">
                                <span ><strong><?php echo Text::_('COM_MINIORANGE_WEB3_CONNECT_TO_CRYPTOWALLET'); ?></strong></span>
                                <span class="TC_modal_close" onclick="close_test_config_modal()">&times;</span>
                            </div>
                        </div><hr>
                        <?php

                        $is_testing = 1;
                        $multiple_crypto_wallet = Mo_Web3_Local_Util::get_multiple_crypto_wallet();

                        foreach ($multiple_crypto_wallet as $key => $value) {
                            $name = $value['id'];
                            $function = $is_testing ? $value['testing_function'] : $value['function'];
                            $id = $name;
                            if ($enabled_crypto_wallet && isset($enabled_crypto_wallet[$name]) && 'checked' === $enabled_crypto_wallet[$name]) {
                                ?>
                                <div onclick="<?php echo $function; ?>" id="<?php echo $id; ?>" class="hover">
                                <div class="mo_boot_col-sm-12">
                                <div class="mo_boot_text-center">
                                            <?php
                                            $base_url = Uri::base();
                                            $wallet_images = $value['logo'];
                                            $wallet_images_count = count($wallet_images);
                                            for ($i = 0; $i < $wallet_images_count; $i++) {
                                                ?>
                                                <img width="30" height="30" class="mo_web3_float"
                                                     src="<?php echo $base_url . 'components/com_miniorange_web3/assets/images/' . $wallet_images[$i]; ?>"/>&nbsp;&nbsp;&nbsp;
                                            <?php } ?>
                                            <div>
                                                <h5><?php echo $value['data']; ?></h5>
                                            </div>

                                            <div class="mo_web3_con_wallet">
                                                <p ><?php echo Text::_('COM_MINIORANGE_WEB3_CONNECT_TO_YOUR'); ?> <?php echo $value['data']; ?></p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function test_configuration() {
    $siteUrl = Uri::base() . '/components/com_miniorange_web3/assets/images/green-check.svg';
    ?>
    <div>
        <button type="button" class="btn btn-primary" id="moweb3_display_modal" style="display:none" data-toggle="modal" data-target="#moweb3_test_modal"><?php echo Text::_('COM_MINIORANGE_WEB3_TEST_CONFIG'); ?></button>

        <div class="mo_boot_col-lg-1">
            <div id="moweb3_test_modal" class="TC_modal" style="opacity:100%;z-index:2 !important;" aria-labelledby="moweb3_test_modal_label" aria-hidden="true">
                <div class="TC_modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moweb3_test_modal_label"><?php echo Text::_('COM_MINIORANGE_WEB3_TEST_RESULT'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <div style="width:100%;color: #3c763d;background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">
                            <?php echo Text::_('COM_MINIORANGE_WEB3_TEST_SUCCESSFUL'); ?>
                            </div>
                        </div>

                        <table class="mo_boot_table mo_boot_table-striped">
                            <thead>
                            <tr>
                                <th scope="col"><?php echo Text::_('COM_MINIORANGE_WEB3_ATTRIBUTE_NAME'); ?></th>
                                <th scope="col"><?php echo Text::_('COM_MINIORANGE_WEB3_ATTRIBUTE_VALUE'); ?></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <th scope="mo_boot_row"><?php echo Text::_('COM_MINIORANGE_WEB3_WALLET_ADDRESS'); ?></th>
                                <td id="wallet_address"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                onclick="close_test_config_modals()"><?php echo Text::_('COM_MINIORANGE_WEB3_CLOSE'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Enqueue style, css & JS requrired for crypto login button
 */
function mo_web3_script_enqueue()
{
    Mo_Web3_Local_Util::enque_files("test_connection");
}
