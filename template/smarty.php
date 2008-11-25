<?php
require_once('smarty/Smarty.class.php');

function template_initialize($settings) {
    $module = import('jackpot.template.smarty');
    $module->smarty = new Smarty();
    $module->smarty->template_dir = $settings->TEMPLATE_DIR;
    $module->smarty->compile_dir = $settings->SMARTY_COMPILE_DIR;
    $module->smarty->cache_dir = $settings->SMARTY_CACHE_DIR;
    $module->smarty->config_dir = $settings->SMARTY_CONFIG_DIR;
    $module->smarty->plugins_dir = $settings->SMARTY_PLUGINS_DIR;
}

function template_assign($name, $var) {
    $module = import('jackpot.template.smarty');
    $module->smarty->assign($name, $var);
}

function template_fetch($file) {
    $module = import('jackpot.template.smarty');
    $module->smarty->fetch($file);
}

function template_display($file) {
    $module = import('jackpot.template.smarty');
    $module->smarty->display($file);
}

function template_set_dir($directory) {
    $module = import('jackpot.template.smarty');
    $module->smarty->template_dir = $directory;
}

function template_valid ($file) {
    $module = import('jackpot.template.smarty');
    return $module->smarty->template_exists($file);
}

?>
