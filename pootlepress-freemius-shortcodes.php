<?php
/*
Plugin Name: PootlePress Freemius Shortcodes
Plugin URI: http://shramee.me/
Description: Shortcodes for Freemiuslicense purchasing buttons
Author: Shramee
Version: 1.0.0
Author URI: http://shramee.me/
@developer shramee <shramee.srivastav@gmail.com>
*/

/**
 * Pootle Page Builder Pro short code
 * @param $args
 * @return string
 */
function pfsc_ppbpro( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'label' => 'Buy now',
		)
	);
	return "
<div class='fs-ppb-wrap'>
	<table width='300'>
		<tr>
			<td>License</td>
			<td><select id='fs-ppb-license'><option value='1' selected='selected'>Single site license ($99)</option><option value='5'>5 site license ($135)</option><option value='unlimited'>Unlimited site license ($149)</option></select></td>
		</tr>
	</table>
	<button id='fs-ppb-buy-button'>{$args['label']}</button>
	<script src='https://checkout.freemius.com/checkout.min.js'></script>
	<script>
	(function($){
		var fsCoPbbHandler = FS.Checkout.configure({
				plugin_id: '269',
				plan_id: '394',
				public_key: 'pk_cb4e7b7932169240ac86c3fb01dd5',
				image: 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
			}),
			fsCoPbbOpenArgs = {
				name: 'pootle page builder',
				success: function(response) {
					// Success : Maybe do something someday
				},
				licenses: 1
			};
	    $('#fs-ppb-buy-button').on('click', function(e) {
		   	e.preventDefault();
		   	fsCoPbbOpenArgs.licenses = $('#fs-ppb-license').val();
		   	fsCoPbbHandler.open( fsCoPbbOpenArgs );
		});
	})(jQuery);
	</script>
</div>";
}
add_shortcode( 'fs_buy_ppbpro', 'pfsc_ppbpro' );

/**
 * 18 tags Pro short code
 * @param $args
 * @return string
 */
function pfsc_18tp( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'label' => 'Buy now',
		)
	);
	return <<<HTML
<div class='fs-ppb-wrap'>
	<table width='300'>
		<tr>
			<td>License</td>
			<td><select id='fs-ppb-license'><option value='1' selected='selected'>Single site license ($99)</option><option value='5'>5 site license ($135)</option><option value='unlimited'>Unlimited site license ($149)</option></select></td>
		</tr>
	</table>
	<button id='fs-ppb-buy-button'>{$args['label']}</button>
	<script src='https://checkout.freemius.com/checkout.min.js'></script>
	<script>
	(function($){
		var fsCo18TPHandler = FS.Checkout.configure({
				plugin_id: '648',
				plan_id: '917',
				public_key: 'pk_3b97a222f67e150b78be694f9b239',
				image: 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
			}),
			fsCo18TPOpenArgs = {
				name: 'Eighteen tags pro',
				success: function(response) {
					// Success : Maybe do something someday
				},
				licenses: 1
			};
	    $('#fs-ppb-buy-button').on( 'click', function(e) {
		   	e.preventDefault();
		   	fsCo18TPOpenArgs.licenses = $('#fs-ppb-license').val();
		   	fsCo18TPHandler.open( fsCo18TPOpenArgs );
		} );
	})(jQuery);
	</script>
</div>
HTML;
}
add_shortcode( 'fs_buy_18tp', 'pfsc_18tp' );

/**
 * Storefront Pro short code
 * @param $args
 * @return string
 */
function pfsc_sfpro( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'label' => 'Buy now',
		)
	);
	return <<<HTML
<div class='fs-ppb-wrap'>
	<table width='300'>
		<tr>
			<td>License</td>
			<td><select id='fs-ppb-license'>
				<option value="1" selected="selected">1 site ($49)</option>
				<option value="5">5 sites ($75)</option>
				<option value="25">25 sites ($99)</option>
				<option value="unlimited">Unlimited sites ($199)</option>
			</select></td>
		</tr>
	</table>
	<button id='fs-ppb-buy-button'>{$args['label']}</button>
	<script src='https://checkout.freemius.com/checkout.min.js'></script>
	<script>
	(function($){
		var fsCoSfPHandler = FS.Checkout.configure({
				plugin_id: '553',
				plan_id: '784',
				public_key: 'pk_4626a94d653f306db2491e3b43d1c',
				image: 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
			}),
			fsCoSFPOpenArgs = {
				name: 'Storefront pro',
				success: function(response) {},
				licenses: 1
			};
	    $('#fs-ppb-buy-button').on( 'click', function(e) {
		   	e.preventDefault();
		   	fsCoSFPOpenArgs.licenses = $('#fs-ppb-license').val();
		   	fsCoSfPHandler.open( fsCoSFPOpenArgs );
		} );
	})(jQuery);
	</script>
</div>
HTML;
}
add_shortcode( 'fs_buy_sfpro', 'pfsc_sfpro' );

/**
 * Storefront Blocks short code
 * @param $args
 * @return string
 */
function pfsc_sfblocks( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'trial' => 'Free trial',
			'label' => 'Buy now',
		)
	);
	return <<<HTML
<div class='fs-ppb-wrap'>
	<table width='300'>
		<tr>
			<td>License</td>
			<td><select id='fs-ppb-license'>
				<option value="1" selected="selected">1 site ($75)</option>
				<option value="5">5 sites ($125)</option>
				<option value="25">25 sites ($149)</option>
				<option value="unlimited">Unlimited sites ($199)</option>
			</select></td>
		</tr>
	</table>
	<button id='fs-ppb-trial-button'>{$args['trial']}</button>
	<button id='fs-ppb-buy-button'>{$args['label']}</button>
	<script src='https://checkout.freemius.com/checkout.min.js'></script>
	<script>
	(function($){
		var
			fsCoSfPHandler = FS.Checkout.configure({
				plugin_id: '2380',
				plan_id: '4051',
				public_key: 'pk_efd8794cafe3f672e71163b8ce2e1',
				image: 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
			}),
			fsCoSFPOpenArgs = {
				name: 'Storefront pro',
				success: function(response) {},
				licenses: 1
			};
		$('#fs-ppb-trial-button').on( 'click', function(e) {
			e.preventDefault();
			fsCoSFPOpenArgs.trial = true;
			fsCoSFPOpenArgs.licenses = $('#fs-ppb-license').val();
			fsCoSfPHandler.open( fsCoSFPOpenArgs );
		} );
		$('#fs-ppb-buy-button').on( 'click', function(e) {
			e.preventDefault();
			delete fsCoSFPOpenArgs.trial;
			fsCoSFPOpenArgs.licenses = $('#fs-ppb-license').val();
			fsCoSfPHandler.open( fsCoSFPOpenArgs );
		} );
	} )(jQuery);
	</script>
</div>
HTML;
}
add_shortcode( 'fs_buy_sfblocks', 'pfsc_sfblocks' );
