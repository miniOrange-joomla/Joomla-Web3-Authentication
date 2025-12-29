function mo_login_page() {
    jQuery('#customer_login_form').submit();
}

function close_wallet_modal() {
    jQuery("#my_wallet_Modal").css("display", "none");
    location.reload();
}

function show_wallet_modal() {
    jQuery("#my_wallet_Modal").css("display", "block");
}

function close_test_config_modals() {
    jQuery("#moweb3_test_modal").css("display", "none");
    location.reload();
}
function moWeb3Upgrade() {
    jQuery('a[href="#licensing-plans"]').click();
    add_css_tab("#licensingtab");
}
function close_test_config_modal(){
    jQuery("#my_TC_Modal").css("display","none");
    location.reload();
}
function show_test_config_modal(){
    jQuery("#my_TC_Modal").css("display","block");
}
function add_css_tab(element) {
    jQuery(".mo_nav_tab_active ").removeClass("mo_nav_tab_active").removeClass("active");
    jQuery(element).addClass("mo_nav_tab_active");
}

function moWeb3Back() {
    jQuery('#mo_web3_cancel_form').submit();
}

function moWeb3CancelForm() {
    jQuery('#cancel_form').submit();
}

function showmodal(){
    jQuery('#myModal').css("display","block");
}
function hidemodal(){
    jQuery('#myModal').css("display","none");
}

function show_import_export() {
    jQuery("#import_export_form").show();
    jQuery("#idpdata").hide();
    jQuery("#tabhead").hide();
}

function hide_import_export_form() {
    jQuery("#import_export_form").hide();
    jQuery("#idpdata").show();
    jQuery("#tabhead").show();
}

