<?php 
/* @var $this PMLC_Admin_Settings */
?>


<div class="wrap">
	<form name="settings" method="post" action="<?php echo $this->baseUrl ?>">
	<div class="load-preset">
		<a href="<?php echo esc_url(wp_nonce_url(add_query_arg('action', 'reset', $this->baseUrl), 'edit-settings')) ?>"><?php _e('Reset to defaults', 'pmlc_plugin') ?></a>
	</div>
	<h2>
		<?php _e('WP Dynamic Links Advanced Settings', 'pmlc_plugin') ?>
	</h2>
	<hr />
	
	<?php if ($this->errors->get_error_codes()): ?>
		<?php $this->error() ?>
	<?php endif ?>
	
	<h3><?php _e('Links', 'pmlc_plugin') ?></h3>
	<table class="form-table link">
		<tr>
			<td>
				<?php printf(__('For links with Meta Refresh or ReferrerMask redirect type, wait %s seconds before redirecting to target URL.', 'pmlc_plugin'),
					'<label class="meter"><input type="text" class="small-text" name="meta_redirect_delay" value="' . esc_attr($post['meta_redirect_delay']) . '" /></label>') ?><br />
			</td>
		</tr>
		<tr>
			<th>
				<input type="hidden" name="forward_url_params" value="0" />
				<input type="checkbox" id="forward_url_params" name="forward_url_params" value="1" <?php echo $post['forward_url_params'] ? 'checked="checked"' : '' ?>" />
				<label for="forward_url_params"><?php _e('Forward query string to destination URL', 'pmlc_plugin') ?></label>
				<a href="#help" class="help" title="<?php _e('<b>subid</b>, <b>cloaked</b> and <b>__a</b> parameters are never forwarded since they are reserved by plugin for its own needs', 'pmlc_plugin') ?>">?</a>
			</th>
		</tr>
		<tr>
			<th style="height:32px">
				<input type="checkbox" class="switcher" id="is_url_prefix" name="is_url_prefix" <?php echo $post['is_url_prefix'] ? 'checked="checked"' : '' ?> />
				<input type="hidden" name="url_prefix" value="" />
				<label for="is_url_prefix"><?php _e('URL Prefix', 'pmlc_plugin') ?></label>
				<a href="#help" class="help" title="<?php _e('Create Links like <b>http://www.yoursite.com/PREFIX/slug</b> instead of <b>http://www.yoursite.com/slug</b>', 'pmlc_plugin') ?>">?</a>
				&nbsp;
				<input type="text" name="url_prefix" class="smaller-text switcher-target-is_url_prefix clear-on-switch" value="<?php echo esc_attr($post['url_prefix']) ?>" />
				<a href="#help" class="help switcher-target-is_url_prefix " title="<?php _e("Prefix to use for cloaked URLs. <b style='color:red'>When changed, old static links will no longer work.</b> Shortcodes will be updated automatically.", 'pmlc_plugin') ?>">?</a>
			</th>
		</tr>
		<tr>
			<th>
				<input type="hidden" name="destination_mode" value="simple" />
				<input type="checkbox" id="destination_mode_advanced" name="destination_mode" value="advanced" <?php echo 'advanced' == $post['destination_mode'] ? 'checked="checked"' : '' ?> />
				<label for="destination_mode_advanced"><?php _e('Show advanced destination controls', 'pmlc_plugin') ?></label>
				<a href="#help" class="help" title="<?php _e('When creating links, allow multiple URLs to be set and randomly rotated.', 'pmlc_plugin') ?>">?</a>
			</th>
		</tr>
	</table>
	
	<h3>3rd Party Tracking</h3>
	<table class="form-table link">
		<tr>
			<th>
				<?php _e('Header Tracking Code', 'pmlc_plugin') ?>
				<a href="#help" class="help" title="<?php _e('This setting is only applicable for links with <b>Meta Refresh</b>, <b>JavaScript</b> or <b>IFrame</b> redirect type. Code is placed in HTML head tag to facilitate 3rd party tracking of clicks.', 'pmlc_plugin') ?>">?</a>
			</th>
		</tr>
		<tr>
			<th>
				<textarea name="header_tracking_code" class="regular-text code" rows="4" wrap="off"><?php echo esc_html($post['header_tracking_code']) ?></textarea>
			</th>
		</tr>
		<tr>
			<th>
				<?php _e('Footer Tracking Code', 'pmlc_plugin') ?>
				<a href="#help" class="help" title="<?php _e('This setting is only applicable for links with <b>Meta Refresh</b>, <b>JavaScript</b> or <b>IFrame</b> redirect type. Code is placed in HTML closing body tag to facilitate 3rd party tracking of clicks.', 'pmlc_plugin') ?>">?</a>
			</th>
		</tr>
		<tr>
			<th>
				<textarea name="footer_tracking_code" class="regular-text code" rows="4" wrap="off"><?php echo esc_html($post['footer_tracking_code']) ?></textarea>
			</th>
		</tr>
	</table>
	
	<h3>History &amp; Statistics Logging</h3>
	<table class="form-table link">
		<tr>
			<th><?php printf(__('Store maximum of %s of the most recent clicks. 0 = unlimited', 'pmlc_plugin'), '<input class="small-text" type="text" name="history_link_count" value="' . esc_attr($post['history_link_count']) . '" />') ?></th>
		</tr>
		<tr>
			<th><?php printf(__('Store click data for a maximum of %s of days. 0 = unlimited', 'pmlc_plugin'), '<input class="small-text" type="text" name="history_link_age" value="' . esc_attr($post['history_link_age']) . '" />') ?></th>
		</tr>
	</table>

	<hr />
	<p class="submit-buttons">
		<?php wp_nonce_field('edit-settings', '_wpnonce_edit-settings') ?>
		<input type="hidden" name="is_submitted" value="1" />
		<input type="submit" value="Save Settings" class="button button-primary button-hero load-customize hide-if-no-customize"/>
		<br class="clear" />
	</p>
	</form>
</div>