<?php 

function pmlc_admin_notices() {
	// notify user if GeoIPCountry database is empty
	$geoip = new PMLC_GeoIPCountry_List();
	if (0 == $geoip->countBy()) {
		?>
		<div class="error"><p>
			<?php printf(
					__('<b>%s Plugin</b>: GeoIPCountry database is empty. Please reactivate the plugin and if the error still appears, you should manually import it from %s into `%s` database table.', 'pmlc_plugin'),
					PMLC_Plugin::getInstance()->getName(),
					'<a href="http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip">GeoIPCountryCSV.zip</a>',
					PMLC_Plugin::getInstance()->getTablePrefix() . 'geoipcountry'
			) ?>
		</p></div>
		<?php
	}
	
	$input = new PMLC_Input();
	$messages = $input->get('pmlc_nt', array());
	if ($messages) {
		is_array($messages) or $messages = array($messages);
		foreach ($messages as $type => $m) {
			in_array((string)$type, array('updated', 'error')) or $type = 'updated';
			?>
			<div class="<?php echo $type ?>"><p><?php echo $m ?></p></div>
			<?php 
		}
	}
	
}