<?php
/**
 * Register plugin specific admin menu
 */

function pmlc_admin_menu() {
	global $menu, $submenu;
	
	if (current_user_can('manage_options')) { // admin management options
		
		add_menu_page(__('WP Dynamic Links', 'pmlc_plugin'), __('Dynamic Links', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-links', array(PMLC_Plugin::getInstance(), 'adminDispatcher'), PMLC_Plugin::ROOT_URL . '/static/img/wizard-icon.png');
		// workaround to rename 1st option to `Home`
		if (current_user_can('manage_options')) {
			$submenu['pmlc-admin-links'] = array();
		}

		add_submenu_page('pmlc-admin-links', __('Create Link', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Dynamic Links', 'pmlc_plugin'), __('Create Link', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-add', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-links', __('Manage Links', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Dynamic Links', 'pmlc_plugin'), __('Manage Links', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-links', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));

		add_submenu_page('pmlc-admin-links', __('Statistics', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Dynamic Links', 'pmlc_plugin'), __('Statistics', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-statistics', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-links', __('Settings', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Dynamic Links', 'pmlc_plugin'), __('Settings', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-settings', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		
		add_submenu_page('empty-parent', __('Edit Link', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Dynamic Links', 'pmlc_plugin'), __('Edit Link', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-edit', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('empty-parent', __('TinyMCE', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Dynamic Links', 'pmlc_plugin'), __('TinyMCE', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-tinymce', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		
	}	
}