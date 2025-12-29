<?php
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

/*
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2023 miniOrange. All Rights Reserved.
*/

function mo_web3_local_support(){
	$strJsonFileContents = file_get_contents(__DIR__ . '/../assets/json/timezones.json'); 
	$timezoneJsonArray = json_decode($strJsonFileContents, true);
    
    $current_user = Factory::getUser();
    $result       = (new Mo_Web3_Local_Util)->_load_db_values('#__miniorange_web3_customer_details');
    $admin_email  = isset($result['email']) ? $result['email'] : '';
    $admin_phone  = isset($result['admin_phone']) ? $result['admin_phone'] : '';
	if($admin_email == '')
		$admin_email = $current_user->email;
	?>
	<div id="sp_support_saml">
		<div class="mo_boot_row mo_boot_p-3 mo_boot_m-0 mo_web3_page ">
			<div class="mo_boot_col-sm-12 mo_boot_row">
                    <div class="mo_boot_col-sm-12 p-2">
                        <h3 style="font-size: 16px;"><?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_'); ?></h3>
                    </div>
			</div>		<hr>
			<div class="mo_boot_col-sm-12 mo_boot_p-0 mo_boot_m-0">
				<form  name="f" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_web3&task=myaccount.contactUs');?>">
                    <div class="mo_boot_col-sm-12 mo_boot_p-0 mo_boot_m-0">	
                            <div>
                                <br><img src="<?php echo Uri::base();?>/components/com_miniorange_web3/assets/images/phone.svg" style="margin-left: 23px;margin-right:10px;" width="27" height="27"  alt="Phone Image">
                                <p><strong><?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_DESC'); ?> <span style="color:red">+1 978 658 9387</span></strong></p><br>
                            </div>
                    </div>
                    <p><?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_DESC_A'); ?></p>
                    <div class="mo_boot_row text-center">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <input type="email" class="mo_saml_table_textbox mo_boot_form-control mo_web3_placeholder" name="query_email" value="<?php echo $admin_email; ?>" placeholder="<?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_PLACEHOLDER_B'); ?>" required />
                        </div>
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <input type="text" class="mo_saml_table_textbox mo_boot_form-control mo_web3_placeholder" name="query_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" value="<?php echo $admin_phone; ?>" placeholder="<?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_PLACEHOLDER'); ?>"/>
                        </div>
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <textarea  name="mo_saml_query_support" class="mo_saml_table_textbox mo_boot_form-control mo_web3_assistance" cols="52" rows="7" required placeholder="<?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_PLACEHOLDER_A'); ?>"></textarea>
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_text-center mo_boot_mt-3">
                        <div class="mo_boot_col-sm-12">
                            <input type="hidden" name="option1" value="mo_saml_login_send_query"/>
                            <input type="submit" name="send_query" value="<?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_SUBMIT_QUERY'); ?>" class="mo_boot_btn mo_boot_btn-primary mo_web3_btns" />
                        </div>
                    </div>
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-12">
                            <p><br><?php echo Text::_('COM_MINIORANGE_WEB3_SUPPORT_DESC_B'); ?> <a style="word-wrap:break-word!important;" href="mailto:joomlasupport@xecurify.com"> joomlasupport@xecurify.com</a> </p>
                        </div>
                    </div>
			    </form>
			</div>
		</div>
	</div>
<?php
}

function mo_web3_advertise(){
	?>
	<div id="sp_advertise" class="mo_web3_supp_pg">
		<div class="mo_boot_row mo_boot_p-3" >
			<div class="mo_boot_col-sm-12">
				<div class="mo_boot_row">
                    <div class="mo_boot_col-sm-2">
                        <img src="<?php echo Uri::base();?>/components/com_miniorange_web3/assets/images/miniorange_i.ico" alt="miniorange">
                    </div>
                    <div class="mo_boot_col-sm-10">
                        <h4><?php echo Text::_('COM_MINIORANGE_SCIM_AD'); ?></h4>
                    </div>
				</div><hr>
			</div>
			<div class="mo_boot_col-sm-12">
               <div class="mo_boot_px-3  mo_boot_text-center">
                     <img src="<?php echo Uri::base();?>components/com_miniorange_web3/assets/images/scim-icon.png" style="margin-left: -25px;padding: 14px;margin-top: 31px;" width="100" height="100" alt="SCIM">
                </div>
               <p><br><br>
               <?php echo Text::_('COM_MINIORANGE_SCIM_AD_DESC'); ?>
               </p>
               <div class="mo_boot_row mo_boot_text-center mo_boot_mt-5">
                   <div class="mo_boot_col-sm-12">
                        <a href="https://prod-marketing-site.s3.amazonaws.com/plugins/joomla/scim-user-provisioning-for-joomla.zip" target="_blank" class="mo_boot_btn mo_boot_btn-saml" style="margin-right: 25px; margin-left: 25px;"></a>
                        <a href="https://plugins.miniorange.com/joomla-scim-user-provisioning" target="_blank" class="mo_boot_btn mo_boot_btn-primary mo_web3_btns" style="margin-right: 25px; margin-left: 25px;"></a>
                    </div>
               </div>
			</div>
		</div>
	</div>
<?php
}

function mo_web3_adv_loginaudit(){
    ?>
    <div id="sp_advertise">
        <div class="mo_boot_row mo_boot_p-0 mo_boot_m-0 mo_web3_page  mo_web3_supp_pg" >
            <div class="mo_boot_col-sm-12">
                <div class="mo_boot_row">
                    <div class="mo_boot_col-sm-1">
                        <img src="<?php echo Uri::base();?>/components/com_miniorange_web3/assets/images/miniorange_i.ico" alt="miniorange">
                    </div>
                    <div class="mo_boot_col-sm-10 mo_boot_mt-2">
                        <strong><?php echo Text::_('COM_MINIORANGE_LOGIN_AUDIT_AD'); ?></strong>
                    </div>
                </div><hr>
                </div>
                <div class="mo_boot_col-sm-12 ">
                    <p><br><?php echo Text::_('COM_MINIORANGE_LOGIN_AUDIT_AD_DESC'); ?>
                    </P>
                   <div class="mo_boot_row mo_boot_text-center">
                       <div class="mo_boot_col-sm-12">
                        <a href="https://plugins.miniorange.com/joomla-login-audit-login-activity-report" target="_blank" class="mo_boot_btn-primary mo_boot_btn-success mo_web3_upgrade_btn mo_boot_m-1"><?php echo Text::_('COM_MINIORANGE_KNOW_MORE'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

?>