<?php 
/* @var $this PMLC_Admin_Edit */
/* @var $item PMLC_Link_Record */
?>

<div class="wrap">
	
	<form name="link" method="post" class="destination-mode-<?php echo PMLC_Plugin::getInstance()->getOption('destination_mode') ?>">
	<input type="hidden" name="id" value="<?php echo esc_attr($id) ?>" />
	
	<?php $presets = new PMLC_Link_List() ?>
	<div class="load-preset">
		<select name="load_preset">
			<option value=""><?php _e('Load Preset...', 'pmlc_plugin') ?></option>
			<?php foreach ($presets->getBy(array('preset NOT IN' => array('', '_temp'), 'id !=' => $id))->convertRecords() as $p): ?>
				<option value="<?php echo $p->id ?>"><?php echo $p->preset ?></option>
			<?php endforeach ?>
		</select><a href="#help" class="help" title="<?php _e('Select a <b>Preset</b> from the dropdown and the settings on this page will be auto-filled with the settings from that <b>Preset</b> - non-empty fields will not be overwritten unless the checkbox is checked.', 'pmlc_plugin') ?>">?</a>
		<br />
		<input id="load-preset-rewrite" type="checkbox" name="load_preset_rewrite" /> <label for="load-preset-rewrite"><?php _e('Rewrite not empty values', 'pmlc_plugin') ?></label>
	</div>
	
	<h2>
		<?php if ( ! $this->input->get('action')): ?>
			<?php _e('Create Cloaked Link', 'pmlc_plugin') ?>
		<?php elseif ('' == $item->preset or '_temp' == $item->preset): ?>
			<?php _e('Edit Cloaked Link', 'pmlc_plugin') ?>
		<?php else: ?>
			<?php _e('Edit Link Preset', 'pmlc_plugin') ?>
		<?php endif ?>
	</h2>
	<hr class="clear" />
	
	<?php if ($this->errors->get_error_codes()): ?>
		<?php $this->error() ?>
	<?php endif ?>
	
	<h3><?php _e('Link Name &amp; Slug', 'pmlc_plugin') ?> <a href="#help" class="help" title="<?php _e('Enter a short, easy-to-remember <b>Link Name</b> so you can identify the link on the <b>Manage Links</b> page. The <b>Link Slug</b> appears at the end of the cloaked URL.', 'pmlc_plugin') ?>">?</a></h3>
	<table class="form-table link">
		<tr class="form-field">
			<th scope="row"><?php _e('Link Name', 'pmlc_plugin') ?></th>
			<td><input type="text" name="name" value="<?php echo esc_attr($post['name']) ?>" /></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php echo PMLC_Plugin::getInstance()->isPermalinks() ? site_url('/') : site_url('/?cloaked=') ?></th>
			<td><input type="text" name="slug" value="<?php echo esc_attr($post['slug']) ?>" /></td>
		</tr>
	</table>
	
	<h3><?php _e('Link Destination', 'pmlc_plugin') ?> <a href="#help" class="help" title="<?php _e('Specify a <b>Link Destination</b>, or if <b>Advanced Mode</b> is enabled on the <b>Settings</b> page, a <b>Destination Set</b>. Click the <b>Help</b> link in the top-right corner of the page for additional details.', 'pmlc_plugin') ?>">?</a></h3>
	<div class="destination-type-container">
		<input id="destination_type_1" type="radio" name="destination_type" value="ONE_SET" <?php echo 'ONE_SET' == $post['destination_type'] ? 'checked="checked"' : '' ?>/>
		<label for="destination_type_1"><?php _e('Identical destination for everyone') ?></label>
		
		<div class="destination-set-container">
			<table class="form-table dest">
				<tr class="form-field">
					<th><?php $item->getRule('ONE_SET')->set(array('link_id' => $id, 'type' => 'ONE_SET'))->render($rules['destination_url']['ONE_SET'][0]) ?></th>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="destination-type-container">
		<input id="destination_type_2" type="radio" name="destination_type" value="BY_COUNTRY" <?php echo 'BY_COUNTRY' == $post['destination_type'] ? 'checked="checked"' : '' ?>/>
		<label for="destination_type_2"><?php _e("Based on visitor's geographical location") ?></label>
		<div class="destination-set-container">
			<?php $countries_config = PMLC_Config::createFromFile(PMLC_Plugin::ROOT_DIR . '/config/countries.php') ?>
			<table class="form-table dest by-country">
				<?php foreach ($rules['country'] as $i => $r_country): ?>
					<?php if ('*' != $r_country) : ?>
						<tr class="form-field">
							<th>
								<select class="country" name="country[]">
									<option value=""></option>
									<?php foreach ($countries_config as $code => $country): ?>
										<option value="<?php echo $code ?>" <?php echo $r_country == $code ? 'selected="selected"' : ''?>><?php echo $country ?></option>
									<?php endforeach ?>
								</select>
							</th>
							<td class="destination-set">
								<input type="hidden" name="destination_by_country[]" value="<?php echo $rules['destination_by_country'][$i] ?>" />
								<?php $rule = new PMLC_Rule_Record(); $rule->set(array('id' => $rules['destination_by_country'][$i], 'link_id' => $id, 'type' => 'BY_COUNTRY'))->render($rules['destination_url']['BY_COUNTRY'][$i]) ?>
							</td>
							<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
						</tr>
					<?php endif ?>
				<?php endforeach ?>
				<?php if (empty($rules['country']) or 1 == count($rules['country']) and in_array('*', $rules['country'])): ?>
					<tr class="form-field">
						<th>
							<select class="country" name="country[]">
								<option value=""></option>
								<?php foreach ($countries_config as $code => $country): ?>
									<option value="<?php echo $code ?>" <?php echo 'US' == $code ? 'selected="selected"' : '' ?>><?php echo $country ?></option>
								<?php endforeach ?>
							</select>
						</th>
						<td class="destination-set">
							<input type="hidden" name="destination_by_country[]" value="" />
							<?php $rule = new PMLC_Rule_Record() and $rule->set(array('link_id' => $id, 'type' => 'BY_COUNTRY'))->render('') ?>
						</td>
						<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
					</tr>
				<?php endif?>
				<tr class="form-field template">
					<th>
						<select class="country" name="country[]">
							<option value=""></option>
							<?php foreach ($countries_config as $code => $country): ?>
								<option value="<?php echo $code ?>"><?php echo $country ?></option>
							<?php endforeach ?>
						</select>
					</th>
					<td class="destination-set">
						<input type="hidden" name="destination_by_country[]" value="" />
						<?php $rule = new PMLC_Rule_Record() and $rule->set(array('link_id' => $id, 'type' => 'BY_COUNTRY'))->render('') ?>
					</td>
					<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
				</tr>
				<tr>
					<th colspan="3">
						<a href="#add" title="<?php _e('add', 'pmlc_plugin')?>" class="action"><?php _e('Add Another Location', 'pmlc_plugin') ?></a>
						<hr />
					</th>
				</tr>
				<tr class="form-field">
					<th>
						<?php _e('All Other Locations')?>
						<input type="hidden" name="country[]" value="*" />
					</th>
					<td class="destination-set">
						<?php $rule = $item->getRule('BY_COUNTRY', '*'); ! $rule->isEmpty() or $rule->set(array('id' => '', 'link_id' => $id, 'type' => 'BY_COUNTRY')) ?>
						<input type="hidden" name="destination_by_country[]" value="<?php echo $rule->id ?>" />
						<?php $ruleIndx = array_search('*', $rules['country']); $rule->render(FALSE !== $ruleIndx ? $rules['destination_url']['BY_COUNTRY'][$ruleIndx] : '') ?>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</div> 
	
	<div class="destination-type-container">
		<input id="destination_type_3" type="radio" name="destination_type" value="BY_RULE" <?php echo 'BY_RULE' == $post['destination_type'] ? 'checked="checked"' : '' ?>/>
		<label for="destination_type_3"><?php _e("Based on visitor's properties") ?> <em>(advanced)</em></label> 
		<a href="#help" class="help" title="<?php _e('Click the <b>Help</b> link in the top-right corner of the page for details on rules and syntax.', 'pmlc_plugin') ?>">?</a>
		
		<div class="destination-set-container">
			<?php $rules_config = PMLC_Config::createFromFile(PMLC_Plugin::ROOT_DIR . '/config/rules.php') ?>
			<table class="form-table dest by-rule">
				<tr>
					<th colspan="4"><?php _e('Note: The first matching rule will be used.', 'pmlc_plugin') ?></th>
				</tr>
				<?php foreach ($rules['rule_name'] as $i => $r_name): ?>
					<?php if ('*' != $r_name) : ?>
						<tr class="form-field">
							<th>
								<select class="rule" name="rule_name[]">
									<option value=""></option>
									<?php foreach ($rules_config as $code => $name): ?>
										<option value="<?php echo $code ?>" <?php echo $r_name == $code ? 'selected="selected"' : ''?>><?php echo $name ?></option>
									<?php endforeach ?>
								</select>
							</th>
							<td class="pattern">
								<input type="text" class="smaller-text" name="rule_pattern[]" value="<?php echo esc_attr($rules['rule_pattern'][$i]) ?>" />
							</td>
							<td class="destination-set">
								<input type="hidden" name="destination_by_rule[]" value="<?php echo $rules['destination_by_rule'][$i] ?>" />
								<?php $rule = new PMLC_Rule_Record(); $rule->set(array('id' => $rules['destination_by_rule'][$i], 'link_id' => $id, 'type' => 'BY_RULE'))->render($rules['destination_url']['BY_RULE'][$i]) ?>
							</td>
							<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
						</tr>
					<?php endif ?>
				<?php endforeach ?>
				<?php if (empty($rules['rule_name']) or 1 == count($rules['rule_name']) and in_array('*', $rules['rule_name'])): ?>
					<tr class="form-field">
						<th>
							<select class="rule" name="rule_name[]">
								<option value=""></option>
								<?php foreach ($rules_config as $code => $name): ?>
									<option value="<?php echo $code ?>" <?php echo 'REMOTE_ADDR' == $code ? 'selected="selected"' : '' ?>><?php echo $name ?></option>
								<?php endforeach ?>
							</select>
						</th>
						<td class="pattern">
							<input type="text" class="smaller-text" name="rule_pattern[]" value="127.0.0.1" />
						</td>
						<td class="destination-set">
							<input type="hidden" name="destination_by_rule[]" value="" />
							<?php $rule = new PMLC_Rule_Record() and $rule->set(array('link_id' => $id, 'type' => 'BY_RULE'))->render('') ?>
						</td>
						<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
					</tr>
				<?php endif ?>
				<tr class="form-field template">
					<th>
						<select class="rule" name="rule_name[]">
							<option value=""></option>
							<?php foreach ($rules_config as $code => $name): ?>
								<option value="<?php echo $code ?>"><?php echo $name ?></option>
							<?php endforeach ?>
						</select>
					</th>
					<td class="pattern">
						<input type="text" class="smaller-text" name="rule_pattern[]" value="" />
					</td>
					<td class="destination-set">
						<input type="hidden" name="destination_by_rule[]" value="" />
						<?php $rule = new PMLC_Rule_Record() and $rule->set(array('link_id' => $id, 'type' => 'BY_RULE'))->render('') ?>
					</td>
					<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
				</tr>
				<tr>
					<th colspan="4">
						<a href="#add" title="<?php _e('add', 'pmlc_plugin')?>" class="action"><?php _e('Add Another Rule', 'pmlc_plugin') ?></a>
						<hr />
					</th>
				</tr>
				<tr class="form-field">
					<th>
						<?php _e('All Other Visitors')?>
						<input type="hidden" name="rule_name[]" value="*" />
						<input type="hidden" name="rule_pattern[]" value="*" />
					</th>
					<td></td>
					<td class="destination-set">
						<?php $rule = $item->getRule('BY_RULE', '*:*'); ! $rule->isEmpty() or $rule->set(array('id' => '', 'link_id' => $id, 'type' => 'BY_RULE')) ?>
						<input type="hidden" name="destination_by_rule[]" value="<?php echo $rule->id ?>" />
						<?php $ruleIndx = array_search('*', $rules['rule_name']); $rule->render(FALSE !== $ruleIndx ? $rules['destination_url']['BY_RULE'][$ruleIndx] : '') ?>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</div> 
	
	<?php $toggled = $this->input->post('toggler-target-advanced_options', '0') ?>
	<h2 id="advanced_options" class="toggler <?php echo $toggled ? 'toggled' : '' ?>">
		<span class="indicator"><?php echo $toggled ? '[-]' : '[+]' ?></span> <?php _e('Advanced Options', 'pmlc_plugin') ?>
	</h2>
	<hr class="clear" />
	<input type="hidden" name="toggler-target-advanced_options" value="<?php echo $toggled ?>" />
	<div class="toggler-target-advanced_options" style="<?php echo $toggled ? '' : 'display:none' ?>">
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Redirect Type', 'pmlc_plugin') ?> <a href="#help" class="help" title="<?php _e('Choose <b>301</b> for a standard redirect. Choose <b>ReferrerMask</b> to ensure no referrer information is sent. Choose <b>Meta Refresh</b>, <b>IFrame</b>, or <b>JavaScript</b> to specify 3rd party tracking code. Click <b>Help</b> in the top-right corner of the page for additional details.', 'pmlc_plugin') ?>">?</a></th>
				<td>
					<select name="redirect_type" style='width: 160px;'>
						<option value="301" <?php echo '301' == $post['redirect_type'] ? 'selected="selected"' : ''?>>301 HTTP Header</option>
						<option value="302" <?php echo '302' == $post['redirect_type'] ? 'selected="selected"' : ''?>>302 HTTP Header</option>
						<option value="307" <?php echo '307' == $post['redirect_type'] ? 'selected="selected"' : ''?>>307 HTTP Header</option>
						<option value="META_REFRESH" <?php echo 'META_REFRESH' == $post['redirect_type'] ? 'selected="selected"' : ''?>>Meta Refresh</option>
						<option value="FRAME" <?php echo 'FRAME' == $post['redirect_type'] ? 'selected="selected"' : ''?>>IFrame</option>
						<option value="JAVASCRIPT" <?php echo 'JAVASCRIPT' == $post['redirect_type'] ? 'selected="selected"' : ''?>>JavaScript</option>
					</select>
				</td>
			</tr>

			<?php if (PMLC_Plugin::getInstance()->getOption('forward_url_params')): ?>
			<tr>
				<th colspan="2">
					<input type="hidden" name="forward_url_params" value="0" />
					<input type="checkbox" id="forward_url_params" name="forward_url_params" value="1" <?php echo $post['forward_url_params'] ? 'checked="checked"' : '' ?>" />
					<label for="forward_url_params"><?php _e('Forward Query String To Destination URL?', 'pmlc_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('<b>subid</b>, <b>cloaked</b> and <b>__a</b> parameters are never forwarded since they are reserved by plugin for its own needs.', 'pmlc_plugin')?>">?</a>
				</th>
			</tr>
			<?php endif ?>
		</table>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<input type="hidden" name="is_link_expires" value="0" />
					<input type="checkbox" id="is_link_expires" name="is_link_expires" <?php echo '0000-00-00' != $post['expire_on'] && '' != $post['expire_on'] ? 'checked="checked"' : '' ?>" class="switcher" />
					<label for="is_link_expires"><?php _e('Link Expires?', 'pmlc_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Make this Link stop working after a certain date. Useful for limited time offers. ', 'pmlc_plugin') ?>">?</a>
				</th>
				<td>
					<input type="text" name="expire_on" class="datepicker switcher-target-is_link_expires" maxlength="12" value="<?php echo esc_attr('0000-00-00' == $post['expire_on'] ? '' : $post['expire_on']) ?>" />
				</td>
			</tr>
			<tr class="destination-set-container switcher-target-is_link_expires">
				<th class="sub-row" colspan="2">
					<?php _e('When expired ...', 'pmlc_plugin') ?>
					<div class="sub-options">
						<input type="radio" id="is_expired_show_404_1" name="is_expired_show_404" value="1" <?php echo '' == $rules['destination_url']['EXPIRED'][0] ? 'checked="checked"' : '' ?> />
						<label for="is_expired_show_404_1"><?php _e('... show 404 page') ?></label>
						<br />
						<input type="radio" id="is_expired_show_404_2" name="is_expired_show_404" value="0" <?php echo '' != $rules['destination_url']['EXPIRED'][0] ? 'checked="checked"' : '' ?> />
						<label for="is_expired_show_404_2"><?php _e('... redirect to') ?></label>
						<?php $item->getRule('EXPIRED')->set(array('link_id' => $id, 'type' => 'EXPIRED'))->render($rules['destination_url']['EXPIRED'][0]) ?>
					</div>
				</th>
			</tr>
		</table>
	
		<table class="form-table">
			<tr>
				<th colspan="2">
					<?php _e('Auto-Match these URLs', 'pmlc_plugin') ?>
					<a href="#help" class="help" title="<?php _e('Automatically replace the following URLs in all <b>WordPress Pages & Posts</b> with the Link you are creating. Can be undone by removing the URLs specified here. Useful for automatically cloaking & tracking uncloaked URLs in your existing content.', 'pmlc_plugin') ?>">?</a>
					<table class="form-table dest auto-matches">
						<?php foreach ($automatches ? $automatches : array('') as $url): ?>
							<tr class="form-field">
								<td class="url"><input type="text" name="automatches[]" maxlength="255" value="<?php echo esc_attr('' == $url ? 'http://' : $url) ?>" /></td>
								<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
							</tr>
						<?php endforeach ?>
						<tr class="form-field template">
							<td class="url"><input type="text" name="automatches[]" maxlength="255" value="http://" /></td>
							<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
						</tr>
						<tr>
							<td><a href="#add" title="<?php _e('add', 'pmlc_plugin')?>" class="action"><?php _e('Add New URL', 'pmlc_plugin') ?></a></td>
							<td></td>
						</tr>
					</table>
				</th>
			</tr>
		</table>
		
		<table class="form-table options">
			<tr class="tracking-code">
				<th colspan="2">
					<input type="hidden" name="no_global_tracking_code" value="0" />
					<input type="checkbox" id="no_global_tracking_code" name="no_global_tracking_code" value="1" <?php echo $post['no_global_tracking_code'] ? 'checked="checked"' : '' ?>" />
					<label for="no_global_tracking_code"><?php _e('Disable Sitewide Header/Footer Tracking Code', 'pmlc_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Specify Sitewide Tracking Code on <b>Settings</b> page.', 'pmlc_plugin') ?>">?</a>
				</th>
			</tr>
			<tr class="tracking-code">
				<th colspan="2">
					<input type="hidden" name="is_header_tracking_code" value="0" />
					<input type="checkbox" id="is_header_tracking_code" name="is_header_tracking_code" <?php echo $post['header_tracking_code'] ? 'checked="checked"' : '' ?>" class="switcher" />
					<label for="is_header_tracking_code"><?php _e('Header Tracking Code', 'pmlc_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Track clicks on this Link with Google Analytics or other 3rd party web analytics software. Paste header tracking code here and it will be appear between <code>head</code> tags.', 'pmlc_plugin') ?>">?</a>
					<div class="textarea-container switcher-target-is_header_tracking_code">
						<textarea name="header_tracking_code" class="regular-text code" rows="4" wrap="off"><?php echo esc_html($post['header_tracking_code']) ?></textarea>
					</div>
				</th>
			</tr>
			<tr class="tracking-code">
				<th colspan="2">
				<input type="hidden" name="is_footer_tracking_code" value="0" />
					<input type="checkbox" id="is_footer_tracking_code" name="is_footer_tracking_code" <?php echo $post['footer_tracking_code'] ? 'checked="checked"' : '' ?>" class="switcher" />
					<label for="is_footer_tracking_code"><?php _e('Footer Tracking Code', 'pmlc_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Track clicks on this Link with Google Analytics or other 3rd party web analytics software. Paste footer tracking code here and it will be appear before <code>/body</code> tag.', 'pmlc_plugin') ?>">?</a>
					<div class="textarea-container switcher-target-is_footer_tracking_code">
						<textarea name="footer_tracking_code" class="regular-text code" rows="4" wrap="off"><?php echo esc_html($post['footer_tracking_code']) ?></textarea>
					</div>
				</th>
			</tr>
		</table>
		<hr />
		
	</div>
	
	<div class="submit-buttons">
		<div class="submit-options">
			<?php $preset = $item->preset != '_temp' ? $item->preset : '' ?>
			<input type="radio" id="is_save_as_preset_1" name="is_save_as_preset" value="0" <?php echo '' == $preset && ! $this->input->post('is_save_as_preset') ? 'checked="checked"' : '' ?> />
			<label for="is_save_as_preset_1"><?php _e('Save as Link') ?></label>
			<br />
			<input type="radio" id="is_save_as_preset_2" name="is_save_as_preset" value="1" <?php echo '' != $preset || $this->input->post('is_save_as_preset') ? 'checked="checked"' : '' ?> />
			<label for="is_save_as_preset_2"><?php _e('Save as Preset') ?></label>
			<input type="text" name="preset" value="<?php echo esc_attr($preset) ?>" />
		</div>
		<?php wp_nonce_field('edit-link', '_wpnonce_edit-link') ?>
		<input type="hidden" name="is_submitted" value="1" />
		<input type="submit" class="button button-primary button-hero load-customize hide-if-no-customize" value="<?php _e('Save Link', 'pmlc_plugin') ?>"/>
		<div class="clear"></div>
	</div>
	</form>
</div>