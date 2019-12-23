<?php

class Pootlepress_Freemius_Shortcodes_Tables {

	protected function register_table_shortcodes() {
		add_shortcode( 'fs_table_ppbpro', [ $this, 'render_ppbpro_table' ] );
		add_shortcode( 'fs_table_18tp', [ $this, 'render_18tp_table' ] );
		add_shortcode( 'fs_table_sfpro', [ $this, 'render_sfpro_table' ] );
		add_shortcode( 'fs_table_sfblocks', [ $this, 'render_sfblocks_table' ] );
		add_shortcode( 'fs_table_woobuilder_blocks', [ $this, 'render_woobuilder_blocks_table' ] );
	}

	protected function render_table_styles( $license_css ) {
		return
			'<style>' .
			'.accent {background: var( --accent );color: var( --accent-text, #fff );}' .
			'.accent-bg {background: var( --accent );}' .
			'.accent-clr {color: var( --accent );}' .
			'.accent2 {background: var( --accent2 );color: var( --accent2-text, #fff );}' .
			'.accent2-clr {background: var( --accent2 );}' .
			'.accent2-tx {color: var( --accent2 );}' .
			'.accent2-callout {position: relative;}' .
			'.accent2-callout:before {content: "";display: block;border: 4px solid transparent;' .
			'border-bottom-color: var( --accent2 );position: absolute;bottom: 100%;left: calc( 50% - 2px );}' .
			$license_css . '</style>';
	}

	protected function render_table_annual( $args, $lic_data ) {
		$id = $args['id'];

		$label = $striked_price = '';
		$price = $lic_data['lifetime'];

		if ( ! empty( $args['discount'] ) ) {
			$striked_price = "<div class='f3 o-70 strike'>$price</div>";
			$price = $price * ( 100 - $args['discount'] ) / 100;
			if ( ! empty( $args['label'] ) ) {
				$label = "<div class='ph3' style='background: #0cf;color: #fff;'>$args[label]</div>";
			}
		}

		return "<div class='w-40 flex flex-column justify-center items-center shadow-2' style='--accent:#000;--accent2:#499fc1;'>
			<header class='br2 br--top w-100 accent pa3 f4 nt1'>
				Annual license
			</header>
	
			<section class='br2 br--bottom pa4 flex flex-column items-center' style='background:#fff;'>

				$striked_price
				<div class='f1'>$price</div>
				$label
	
				<ul class='w-100 tl mv3'>
					<li>$lic_data[label]</li>
					<li>1 year support and updates</li>
					<li>14-day Money Back Guarantee</li>
				</ul>
	
					<div class='f5 w-90 mt3' style='min-height: 7em;'>
						You can cancel your subscription at any time and the plugin will still be active, however you won't get updates or support in the following year.
				</div>
				<button data-sites='$lic_data[sites]' class='fs-$id-buy-annual'class='br2 accent'>Buy now</button>
				$args[trial]
			</section>
		</div>";
	}

	protected function render_table_lifetime( $args, $lic_data ) {
		$id = $args['id'];

		$label = $striked_price = '';
		$price = $lic_data['lifetime'];

		if ( ! empty( $args['discount'] ) ) {
			$striked_price = "<div class='f3 o-70 strike'>$price</div>";
			$price = $price * ( 100 - $args['discount'] ) / 100;
			if ( ! empty( $args['label'] ) ) {
				$label = "<div class='ph3' style='background: #0cf;color: #fff;'>$args[label]</div>";
			}
		}

		return "<div class='w-40 flex flex-column justify-center items-center shadow-2' style='--accent:#ef4832;--accent2:#499fc1;'>
			<div class='br2 br--top w-50 nt4 accent' style='background:#c02812;'>BEST DEAL</div>
			<header class='br2 br--top w-100 accent pa3 f4'>
				Lifetime license
			</header>
	
			<section class='br2 br--bottom pa4 flex flex-column items-center' style='background:#fff;'>

				$striked_price
				<div class='f1'>$price</div>
				$label
	
<!--
				<div class='f5 mv3'>&nbsp;</div>
-->
	
				<ul class='w-100 tl mv3'>
					<li>$lic_data[label]</li>
					<li>Lifetime support and updates</li>
					<li>14-day Money Back Guarantee</li>
				</ul>
	
					<div class='f5 w-90 mt3' style='min-height: 7em;'>
						A lifetime license entitles you to support and updates for the lifetime of the product.
				</div>
				<button data-sites='$lic_data[sites]' class='fs-$id-buy-lifetime'class='br2 accent'>Buy now</button>
				$args[trial]
			</section>
		</div>";
	}

	function render_table_script( $args ) {
		$id = $args['id'];

		return "
		<script src='https://checkout.freemius.com/checkout.min.js'></script>
		<script>
			( function ( $ ) {
				$( '.fs-table-$id-license' ).change( function() {
					$(this).parent().attr( 'data-license-active', this.value );
				} );
				var fsHandler  = FS.Checkout.configure( " . json_encode( $args['fs_co_conf'] ) . " ),
						fsOpenArgs = {
							name    : '$args[name]',
							success : function ( response ) {
								// Success : Maybe do something someday
							},
							licenses: 1
						};
				$( '.fs-$id-buy-annual' ).on( 'click', function ( e ) {
					e.preventDefault();
					fsOpenArgs.licenses = $( this ).data( 'sites' );
					fsOpenArgs.billing_cycle = 'annual';
					fsHandler.open( fsOpenArgs );
				} );
				$( '.fs-$id-buy-lifetime' ).on( 'click', function ( e ) {
					e.preventDefault();
					fsOpenArgs.licenses = $( this ).data( 'sites' );
					fsOpenArgs.billing_cycle = 'lifetime';
					fsHandler.open( fsOpenArgs );
				} );
			} )( jQuery );
		</script>";
	}

	function render_table( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'label'   => 'Buy now',
			)
		);

		$id = $args['id'];
		$license_css = '[data-license-active] [data-license] {display: none}';

		$license_options = '';
		$first_license = '';

		ob_start();

		foreach ( $args['licenses'] as $sites => $lic_data ) {
			if ( ! $first_license ) {
				$first_license = $sites;
			}
			$license_options .= "<option value='$sites'>$lic_data[label]</option>";
			$license_css .= "[data-license-active='$sites'] .flex[data-license='$sites'] {display: flex}";
		}


		if ( $args['trial'] ) {
			$args['trial'] = "<div class='f5 pt3'>Or <a href='#'>start 14-day free trial</a></div>";
		}

		echo "<div class='fs-$id-wrap' data-license-active='$first_license'>";

		echo $this->render_table_styles( $license_css );

		echo "<select class='fs-table-$id-license db mha mt3 mb4 f4'>$license_options</select>";

		foreach ( $args['licenses'] as $sites => $lic_data ) {
			$lic_data['sites'] = $sites;
			echo "<div class='flex items-center justify-around tc pv4' data-license='$sites'>";

			if ( ! empty( $lic_data['annual'] )) {
				echo $this->render_table_annual( $args, $lic_data );
			}

			if ( ! empty( $lic_data['lifetime'] )) {
				echo $this->render_table_lifetime( $args, $lic_data );
			}

			echo '</div>';
		}

		echo $this->render_table_script( $args );

		echo "</div>";

		return ob_get_clean();
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
					'trial'			 => true,
					'licenses'    => [
						'1' => [
							'label' => 'Single site license',
							'annual' => '$99',
							'lifetime' => '$299',
						],
						'5' => [
							'label' => 'Five sites license',
							'annual' => '$135',
							'lifetime' => '$395',
						],
						'unlimited' => [
							'label' => 'Unlimited sites license',
							'annual' => '$149',
							'lifetime' => '',
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
	function render_18tp_table( $args ) {

		return $this->render_table(
			wp_parse_args(
				$args,
				[
					'id'         => '18tp',
					'name'       => 'Eighteen tags pro',
					'licenses'   => [
						'1'         => [
							'label' => 'Single site license',
							'annual' => '$99',
							'lifetime' => '$199',
						],
						'5'         => [
							'label' => 'Five sites license',
							'annual' => '$135',
							'lifetime' => '$395',
						],
						'unlimited' => [
							'label' => 'Unlimited sites license',
							'annual' => '$149',
							'lifetime' => '',
						],
					],
					'fs_co_conf' => [
						'plugin_id'  => '648',
						'plan_id'    => '917',
						'public_key' => 'pk_3b97a222f67e150b78be694f9b239',
						'image'      => 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
					]
				]
			)
		);
	}

	/**
	 * Storefront Pro short code
	 * @param $args
	 * @return string
	 */
	function render_sfpro_table( $args ) {
		return $this->render_table(
			wp_parse_args(
				$args,
				[
					'id'         => 'sfp',
					'name'       => 'Storefront pro',
					'licenses'   => [
						'1'         => [
							'label' => 'Single site license',
							'annual' => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label' => 'Five sites license',
							'annual' => '$75',
							'lifetime' => '$265',
						],
						'25'         => [
							'label' => '25 sites license',
							'annual' => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label' => 'Unlimited sites license',
							'annual' => '$199',
							'lifetime' => '',
						],
					],
					'fs_co_conf' => [
						'plugin_id'  => '553',
						'plan_id'    => '784',
						'public_key' => 'pk_4626a94d653f306db2491e3b43d1c',
						'image'      => 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
					]
				]
			)
		);
	}

	/**
	 * Storefront Blocks short code
	 * @param $args
	 * @return string
	 */
	function render_sfblocks_table( $args ) {
		return $this->render_table(
			wp_parse_args(
				$args,
				[
					'id'         => 'sfbk',
					'name'       => 'Storefront blocks',
					'licenses'   => [
						'1'         => [
							'label' => 'Single site license',
							'annual' => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label' => 'Five sites license',
							'annual' => '$75',
							'lifetime' => '$265',
						],
						'25'         => [
							'label' => '25 sites license',
							'annual' => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label' => 'Unlimited sites license',
							'annual' => '$199',
							'lifetime' => '',
						],
					],
					'fs_co_conf' => [
						'plugin_id'  => '2380',
						'plan_id'    => '4051',
						'public_key' => 'pk_efd8794cafe3f672e71163b8ce2e1',
						'image'      => 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
					]
				]
			)
		);
	}

	/**
	 * Storefront Blocks short code
	 * @param $args
	 * @return string
	 */
	function render_woobuilder_blocks_table( $args ) {

		return $this->render_table(
			wp_parse_args(
				$args,
				[
					'id'         => 'woobk',
					'name'       => 'WooBuilder blocks',
					'licenses'   => [
						'1'         => [
							'label' => 'Single site license',
							'annual' => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label' => 'Five sites license',
							'annual' => '$75',
							'lifetime' => '$265',
						],
						'25'         => [
							'label' => '25 sites license',
							'annual' => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label' => 'Unlimited sites license',
							'annual' => '$199',
							'lifetime' => '',
						],
					],
					'fs_co_conf' => [
						'plugin_id'  => '3514',
						'plan_id'    => '5685',
						'public_key' => 'pk_c52effbb9158dc8c4098e44429e4a',
						'image'      => 'https://ps.w.org/pootle-page-builder/assets/icon-128x128.png?rev=1412533'
					]
				]
			)
		);
	}

}