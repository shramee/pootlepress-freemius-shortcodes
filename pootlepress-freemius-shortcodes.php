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

class Pootlepress_Freemius_Shortcodes {
	/** @var self Instance */
	private static $_instance;

	public function __construct() {
		add_shortcode( 'fs_table_ppbpro', [ $this, 'render_ppbpro_table' ] );
		add_shortcode( 'fs_buy_ppbpro', [ $this, 'render_ppbpro' ] );
		add_shortcode( 'fs_buy_18tp', [ $this, 'render_18tp' ] );
		add_shortcode( 'fs_buy_sfpro', [ $this, 'render_sfpro' ] );
		add_shortcode( 'fs_buy_sfblocks', [ $this, 'render_sfblocks' ] );
		add_shortcode( 'fs_buy_woobuilder_blocks', [ $this, 'render_woobuilder_blocks' ] );
	}

	/**
	 * Returns instance of current calss
	 * @return self Instance
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function render_table( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'label'   => 'Buy now',
			)
		);

		$id = $args['id'];
		$license_options = '';
		foreach ( $args['licenses'] as $sites => $lic_data ) {
			$license_options .= "<option value='$sites'>$lic_data[label] site license ($lic_data[annual])</option>";
		}

		return "
<div class='fs-$id-wrap'>
	<style>
	.accent {
		background: var( --accent );
		color: var( --accent-text, #fff );
	}
	
	.accent-bg {
		background: var( --accent );
	}
	
	.accent-clr {
		color: var( --accent );
	}
	
	.accent2 {
		background: var( --accent2 );
		color: var( --accent2-text, #fff );
	}
	
	.accent2-clr {
		background: var( --accent2 );
	}
	
	.accent2-tx {
		color: var( --accent2 );
	}
	
	.accent2-callout {
		position: relative;
	}
	
	.accent2-callout:before {
		content: '';
		display: block;
		border: 4px solid transparent;
		border-bottom-color: var( --accent2 );
		position: absolute;
		bottom: 100%;
		left: calc( 50% - 2px );
	}
	</style>
	<select id='fs-$id-license' class='db mha mt3 mb4 f4'>$license_options</select>

	<div class='flex items-center justify-around tc pv4'>
		<div class='w-40 flex flex-column justify-center items-center shadow-2' style='--accent:#000;--accent2:#499fc1;'>
			<header class='br2 br--top w-100 accent pa3 f4 nt1'>
				Annual license
			</header>
	
			<section class='br2 br--bottom pa4 flex flex-column items-center' style='background:#fff;'>
				<div class='f3 o-70 strike'>$99.00</div>
				<div class='f1'>$59.40</div>
				<div class='accent2 accent2-callout br2 pt1 ph2'>SAVE $39.60</div>
	
				<div class='f5 mv3'>Then $99<span class='o-70 f6'> / year</span></div>
	
				<ul class='w-100 tl mv3'>
					<li>Single site license</li>
					<li>1 year support and updates</li>
					<li>30-day Money Back Guarantee</li>
				</ul>
	
					<div class='f5 w-90 mt3' style='min-height: 7em;'>
						After today's payment you subscription will renew automatically each year at the normal price of $99 until cancelled.
				</div>
				<button id='fs-$id-buy-annual'class='br2 accent'>Buy now</button>
				<div class='f5 pt3'>Or <a href='#'>start 14-day free trial</a></div>
			</section>
		</div>
		<div class='w-40 flex flex-column justify-center items-center shadow-2' style='--accent:#ef4832;--accent2:#499fc1;'>
			<div class='br2 br--top w-50 nt4 accent' style='background:#c02812;'>BEST DEAL</div>
			<header class='br2 br--top w-100 accent pa3 f4'>
				Lifetime license
			</header>
	
			<section class='br2 br--bottom pa4 flex flex-column items-center' style='background:#fff;'>
				<div class='f3 o-70 strike'>$299.00</div>
				<div class='f1'>$239.20</div>
				<div class='accent2 accent2-callout br2 pt1 ph2'>SAVE $59.20</div>
	
				<div class='f5 mv3'>&nbsp;</div>
	
				<ul class='w-100 tl mv3'>
					<li>Single site license</li>
					<li>Lifetime support and updates</li>
					<li>30-day Money Back Guarantee</li>
				</ul>
	
					<div class='f5 w-90 mt3' style='min-height: 7em;'>
						A lifetime license entitles you to support and updates for the lifetime of the product.
				</div>
				<button id='fs-$id-buy-lifetime'class='br2 accent'>Buy now</button>
				<div class='f5 pt3'>Or <a href='#'>start 14-day free trial</a></div>
			</section>
		</div>
	</div>
		<script src='https://checkout.freemius.com/checkout.min.js'></script>
		<script>
			( function ( $ ) {
				var fsHandler  = FS.Checkout.configure( " . json_encode( $args['fs_co_conf'] ) . " ),
						fsOpenArgs = {
							name    : '$args[name]',
							success : function ( response ) {
								// Success : Maybe do something someday
							},
							licenses: 1
						};
				$( '#fs-$id-buy-annual' ).on( 'click', function ( e ) {
					e.preventDefault();
					fsOpenArgs.licenses = $( '#fs-$id-license' ).val();
					fsOpenArgs.billing_cycle = 'annual';
					fsHandler.open( fsOpenArgs );
				} );
				$( '#fs-$id-buy-lifetime' ).on( 'click', function ( e ) {
					e.preventDefault();
					fsOpenArgs.licenses = $( '#fs-$id-license' ).val();
					fsOpenArgs.billing_cycle = 'lifetime';
					fsHandler.open( fsOpenArgs );
				} );
			} )( jQuery );
		</script>
</div>";
	}

	/**
	 * Pootle Page Builder Pro short code
	 * @param $args
	 * @return string
	 */
	function render_ppbpro_table( $args ) {
		return $this->render_table(
			wp_parse_args(
				$args,
				[
					'id'         => 'ppbpro',
					'name'       => 'pootle page builder',
					'licenses'    => [
						'1' => [
							'label' => 'Single',
							'annual' => '$99',
							'lifetime' => '$99',
						],
						'5' => [
							'label' => '5',
							'annual' => '$135',
							'lifetime' => '$135',
						],
						'unlimited' => [
							'label' => 'Unlimited',
							'annual' => '$149',
							'lifetime' => '$149',
						],
					],
					'fs_co_conf' => [
						'plugin_id'  => '269',
						'plan_id'    => '394',
						'public_key' => 'pk_cb4e7b7932169240ac86c3fb01dd5',
						'image'      => 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
					]
				]
			)
		);

	}

	function render_select_button( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'label'   => 'Buy now',
			)
		);

		$id = $args['id'];

		$license_options = '';
		foreach ( $args['licenses'] as $sites => $lic_data ) {
			$license_options .= "<option value='$sites'>$lic_data[label] site license ($lic_data[price])</option>";
		}

		return "
<div class='fs-$id-wrap'>
	<table width='300'>
		<tr>
			<td>License</td>
			<td><select id='fs-$id-license'>$license_options</select></td>
		</tr>
	</table>
	<button id='fs-$id-buy-button'>{$args['label']}</button>
	<script src='https://checkout.freemius.com/checkout.min.js'></script>
	<script>
	(function($){
		var fsHandler = FS.Checkout.configure( " . json_encode( $args['fs_co_conf'] ) . " ),
			fsOpenArgs = {
				name: '$args[name]',
				success: function(response) {
					// Success : Maybe do something someday
				},
				licenses: 1
			};
	    $('#fs-$id-buy-button').on('click', function(e) {
		   	e.preventDefault();
		   	fsOpenArgs.licenses = $('#fs-$id-license').val();
		   	fsHandler.open( fsOpenArgs );
		});
	})(jQuery);
	</script>
</div>";
	}

	/**
	 * Pootle Page Builder Pro short code
	 * @param $args
	 * @return string
	 */
	function render_ppbpro( $args ) {
		return $this->render_select_button(
			wp_parse_args(
				$args,
				[
					'id'         => 'ppbpro',
					'name'       => 'pootle page builder',
					'licenses'    => [
						'1' => [
							'label' => 'Single',
							'price' => '$99'
						],
						'5' => [
							'label' => '5',
							'price' => '$135'
						],
						'unlimited' => [
							'label' => 'Unlimited',
							'price' => '$149'
						],
					],
					'fs_co_conf' => [
						'plugin_id'  => '269',
						'plan_id'    => '394',
						'public_key' => 'pk_cb4e7b7932169240ac86c3fb01dd5',
						'image'      => 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
					]
				]
			)
		);
	}

	/**
	 * 18 tags Pro short code
	 * @param $args
	 * @return string
	 */
	function render_18tp( $args ) {
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

	/**
	 * Storefront Pro short code
	 * @param $args
	 * @return string
	 */
	function render_sfpro( $args ) {
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

	/**
	 * Storefront Blocks short code
	 * @param $args
	 * @return string
	 */
	function render_sfblocks( $args ) {
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
				<option value="1" selected="selected">1 site ($49)</option>
				<option value="5">5 sites ($75)</option>
				<option value="25">25 sites ($99)</option>
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

	/**
	 * Storefront Blocks short code
	 * @param $args
	 * @return string
	 */
	function render_woobuilder_blocks( $args ) {
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
				<option value="1" selected="selected">1 site ($49)</option>
				<option value="5">5 sites ($75)</option>
				<option value="25">25 sites ($99)</option>
				<option value="unlimited">Unlimited sites ($199)</option>
			</select></td>
		</tr>
	</table>
	<!--<button id='fs-ppb-trial-button'>{$args['trial']}</button>-->
	<button id='fs-ppb-buy-button'>{$args['label']}</button>
	<script src='https://checkout.freemius.com/checkout.min.js'></script>
	<script>
	(function($){
		var
			fsCoSfPHandler = FS.Checkout.configure({
				plugin_id: '3514',
				plan_id: '5685',
				public_key: 'pk_c52effbb9158dc8c4098e44429e4a',
//				image: 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
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

}

Pootlepress_Freemius_Shortcodes::instance();
