<?php

class Pootlepress_Freemius_Shortcodes_Tables {

	protected $url;

	protected function register_table_shortcodes() {
		add_shortcode( 'fs_table_ppbpro', [ $this, 'render_ppbpro_table' ] );
		add_shortcode( 'fs_table_18tp', [ $this, 'render_18tp_table' ] );
		add_shortcode( 'fs_table_sfpro', [ $this, 'render_sfpro_table' ] );
		add_shortcode( 'fs_table_sfblocks', [ $this, 'render_sfblocks_table' ] );
		add_shortcode( 'fs_table_woobuilder_blocks', [ $this, 'render_woobuilder_blocks_table' ] );
		add_shortcode( 'fs_table2_sfblocks', [ $this, 'render_sfblocks_table2' ] );
		add_shortcode( 'fs_table2_woobuilder_blocks', [ $this, 'render_woobuilder_blocks_table2' ] );
	}

	protected function check_icon() {
		return '<img src="' . $this->url . '/check.svg" width="1.25rem">';
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
			$price         = $price * ( 100 - $args['discount'] ) / 100;
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
			$price         = $price * ( 100 - $args['discount'] ) / 100;
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
				'label' => 'Buy now',
			)
		);

		$id          = $args['id'];
		$license_css = '[data-license-active] [data-license] {display: none}';

		$license_options = '';
		$first_license   = '';

		ob_start();

		foreach ( $args['licenses'] as $sites => $lic_data ) {
			if ( ! $first_license ) {
				$first_license = $sites;
			}
			$license_options .= "<option value='$sites'>$lic_data[label]</option>";
			$license_css     .= "[data-license-active='$sites'] .flex[data-license='$sites'] {display: flex}";
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

			if ( ! empty( $lic_data['annual'] ) ) {
				echo $this->render_table_annual( $args, $lic_data );
			}

			if ( ! empty( $lic_data['lifetime'] ) ) {
				echo $this->render_table_lifetime( $args, $lic_data );
			}

			echo '</div>';
		}

		wp_enqueue_script( 'jquery' );
		add_action( 'wp_print_footer_scripts', function () use ( $args ) {
			echo $this->render_table_script( $args );
		}, 999 );

		echo "</div>";

		return ob_get_clean();
	}

	/**
	 * Pootle Page Builder Pro short code
	 *
	 * @param $args
	 *
	 * @return string
	 */
	function render_ppbpro_table( $args ) {
		return $this->render_table(
			wp_parse_args(
				$args,
				[
					'id'         => 'ppbpro',
					'name'       => 'pootle page builder',
					'trial'      => true,
					'licenses'   => [
						'1'         => [
							'label'    => 'Single site license',
							'annual'   => '$99',
							'lifetime' => '$299',
						],
						'5'         => [
							'label'    => '5 sites license',
							'annual'   => '$135',
							'lifetime' => '$395',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites license',
							'annual'   => '$149',
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
	 *
	 * @param $args
	 *
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
							'label'    => 'Single site license',
							'annual'   => '$99',
							'lifetime' => '$199',
						],
						'5'         => [
							'label'    => '5 sites license',
							'annual'   => '$135',
							'lifetime' => '$395',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites license',
							'annual'   => '$149',
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
	 *
	 * @param $args
	 *
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
							'label'    => 'Single site license',
							'annual'   => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label'    => '5 sites license',
							'annual'   => '$75',
							'lifetime' => '$265',
						],
						'25'        => [
							'label'    => '25 sites license',
							'annual'   => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites license',
							'annual'   => '$199',
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
	 *
	 * @param $args
	 *
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
							'label'    => 'Single site license',
							'annual'   => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label'    => '5 sites license',
							'annual'   => '$75',
							'lifetime' => '$265',
						],
						'25'        => [
							'label'    => '25 sites license',
							'annual'   => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites license',
							'annual'   => '$199',
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
	 *
	 * @param $args
	 *
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
							'label'    => 'Single site license',
							'annual'   => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label'    => '5 sites license',
							'annual'   => '$75',
							'lifetime' => '$265',
						],
						'25'        => [
							'label'    => '25 sites license',
							'annual'   => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites license',
							'annual'   => '$199',
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

	function render_table2_styles() {
		?>
		<style>
			.ppfs-price-table {
				width: 100%;
				max-width: 1200px;
				display: flex;
				margin: 4em auto 0;
			}

			.ppfs-table {
				flex: 0 0 47.5%;
				display: flex;
				flex-direction: column;
				box-shadow: 0px 0px 17px #0000006b;
				margin: 0;
				border-radius: 5px;
				text-align: center;
				background: #fff;
				color: #464646;
			}

			.ppfs-table + .ppfs-table {
				margin-left: 5%;
			}

			.ppfs-title, .features, .cta {
				padding: 0 2em;
			}

			.features {
				padding-top: 1rem;
			}

			/* TITLE */

			.ppfs-title {
				text-align: center;
				background: #2d2d2d;
				margin-top: 0;
				padding: 1em 0;
				border-radius: 5px 5px 0 0;
				color: #dadada;
			}

			.ppfs-title h2 {
				color: inherit;
				font-size: 1.6em;
				font-weight: 600;
				margin: 0;
			}

			.ppfs-title.pootle {
				background: #ee4833;
				position: relative;
				color: white;
			}


			.ppfs-title.pootle:before {
				border-radius: 5px 5px 0 0;
				content: 'BEST DEAL';
				font-weight: 600;
				display: block;
				position: absolute;
				bottom: 100%;
				left: calc( 50% - 80px );
				width: 160px;
				background: #d74330;
				padding: .3em .7em .2em;
			}

			/* PRICE */

			.ppfs-price {
				color: #6d6d6d;
				font-size: 1.8em;
				font-weight: 500;
				text-align: center;
				margin-top: .7rem;
				line-height: 1;
			}

			.ppfs-price span {
				font-weight: 600;
				font-size: 1.4em;
			}

			.ppfs-opri-stri:after {
				content: '';
				display: block;
				border-top: 2px solid;
				width: 100%;
				margin: -1em 0 1em;
			}

			.ppfs-price.ppfs-opri {
				display: inline-block;
				margin: .7rem 0 0;
				font-size: 1.2em;
				opacity: 0.6;
			}

			.ppfs-price.ppfs-opri + .ppfs-price {
				margin-top: .7rem;
			}

			/* FEATURE LIST */

			.list {
				text-align: left;
				line-height: 1.8;
				text-transform: capitalize;
				list-style-type: none;
				margin: 2rem 0 0;
				padding-left: 0;
			}

			.list img {
				display:inline-block;
				width:1.15rem;
				margin:0 0 .1rem .1rem;
				vertical-align:middle;
				opacity: .25;
			}

			/* BOTTOM */

			.cta {
				margin-top: auto;
				margin-bottom: .652rem;
				display: flex;
				flex-direction: column;
			}

			.license-options {
				-webkit-appearance: none;
				color: #6d6d6d;
				font-size: 1.1em;
				margin: 1.25rem 0 0;
				width: 100%;
				height: 3.75rem;
				border: 1px solid #ddd;
				padding: 0 1.25em;
				border-radius: 5px;
				background: calc( 100% - 1em ) 50% / .8em no-repeat #f1f1f1;
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' role='img' viewBox='0 0 320 512'%3E%3Cpath fill='currentColor' d='M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z'/%3E%3C/svg%3E");
			}

			.license-options:focus {
				outline: none;
			}

			.buy-btn {
				border-radius: 5px;
				color: #fff;
				font-size: 1.25em;
				text-decoration: none;
				background: #2d2d2d;
				padding: .8em;
				display: inline-block;
				margin: 1.25rem 0 .7em;
				opacity: .9;
			}

			.buy-btn:hover {
				opacity: 1;
			}

			.buy-btn i {
				margin-right: 10px;
				font-size: 25px;
			}

			.buy-btn.pootle {
				background: #ee4833;
				color: #fff;
				margin: 6.25rem 0 .625rem;
			}

			.cta p {
				color: #6d6d6d;
				font-size: 1.2em;
				margin: 0;
			}

			.cta p a {
				color: inherit;
				border-bottom: 1px solid;
			}

			iframe[src*='checkout.freemius'] {
				max-height: none !important;
			}

			@media (max-width: 1024px) {
				.ppfs-price-table {
					flex-wrap: wrap;
					justify-content: center;
				}

				.ppfs-table {
					flex: 0 0 100%;
					width: 100%;
					max-width: 500px;
					margin: 0;
				}

				.ppfs-table + .ppfs-table {
					margin: 0;
				}

				.ppfs-table + .ppfs-table {
					margin-top: 4em;
				}
			}
		</style>
		<?php
	}

	private function fs_conf( $args ) {
		return json_encode( [
			"plugin_id"  => $args["plugin_id"],
			"plan_id"    => $args["plan_id"],
			"public_key" => $args["public_key"],
			"subtitle"   => $args["subtitle"],
		] );
	}

	function render_table2_script( $args ) {
		$id = $args['id'];
		?>
		<script src='https://checkout.freemius.com/checkout.min.js'></script>
		<script>
			( function ( $ ) {
				var id = '<?php echo $id ?>';
				$( '.fs-table-<?php echo $id ?>-license' ).change( function() {
					$(this).parent().attr( 'data-license-active', this.value );
				} );
				var licensesConf = <?php echo json_encode( $args['licenses'] ) ?>;

				var fsHandler = function ( args ) {
					args = $.extend( {
						name         : '<?php echo $args['name'] ?>',
						success      : function ( response ) {},
						billing_cycle: 'annual',
						licenses     : 1
					}, args || {} );
					var handler = FS.Checkout.configure( <?php echo $this->fs_conf( $args['fs_co_conf'] ) ?> );
					handler.open( args )
				};
				var fsBundleHandler = function ( args ) {
					args = $.extend( {
						name    : '<?php echo $args['bundle']['subtitle'] ?>',
						success : function ( response ) {},
						licenses: 1
					}, args );
					var handler = FS.Checkout.configure( <?php echo $this->fs_conf( $args['bundle'] ) ?> );
					handler.open( args )
				};
				var $sitesCount = $( '<?php echo ".ppfs-sites-$id" ?>' );
				var $price = $( '.ppfs-price-<?php echo $id ?>' );
				var priceSuffix = {
					annual: ' / year',
					lifetime: ' once',
				};

				var $supportTerm = $( '<?php echo ".ppfs-support-term-$id" ?>' );
				var supportTerm = {
					annual: '1 year ',
					lifetime: 'Lifetime ',
				};

				var $licSelect = $( '<?php echo ".fs-table-$id-license" ?>' );
				var $productBuy = $( '<?php echo ".fs-$id-buy-subs" ?>' );
				var $productTrial = $( '<?php echo ".fs-$id-trial" ?>' );

				var $bundleBuy = $( '<?php echo ".fs-$id-buy-bundle" ?>' );
				var $bundleTrial = $( '<?php echo ".fs-$id-trial-bundle" ?>' );

				$productBuy.on( 'click', function ( e ) {
					e.preventDefault();
					var selectedOptions = $licSelect[0].value.split( '|' );
					fsHandler( {
						billing_cycle: selectedOptions[1] || 'annual',
						licenses: selectedOptions[0] || '1',
					} )
				} );

				$productTrial.on( 'click', function ( e ) {
					e.preventDefault();
					var selectedOptions = $licSelect[0].value.split( '|' );
					fsHandler( {
						billing_cycle: selectedOptions[1] || 'annual',
						licenses: selectedOptions[0] || '1',
						trial: true,
					} );
				} );

				$bundleBuy.on( 'click', function ( e ) {
					e.preventDefault();
					fsBundleHandler();
				} );

				$bundleTrial.on( 'click', function ( e ) {
					e.preventDefault();
					fsBundleHandler( { trial: true } );
				} );
				$licSelect.on( 'change', function ( e ) {
					var sel = this.value.split( '|' );
					var licConf = licensesConf[sel[0]];
					if ( licConf ) {
						$sitesCount.html( licConf.label );
						$price.html( '<span>' + licConf[sel[1]] + '</span> ' + priceSuffix[sel[1]] );
						$supportTerm.html( supportTerm[sel[1]] );
					}
				} );
			} )( jQuery );
		</script>
		<?php
	}

	function render_table2( $args ) {
		if ( empty($args['bundle'] ) ) {
			$args['bundle'] = [
				'plugin_id'  => '1887',
				'plan_id'    => '2788',
				'public_key' => 'pk_e8c600d38f23c090bcdce65c8fab5',
				'subtitle'   => 'Ecommerce bundle',
				'reg_price'      => '$246',
				'price'      => '$125',
			];
		}

		$id = $args['id'];
		$first_license = null;
		$license_options = "<optgroup label='Annual'>";
		foreach ( $args['licenses'] as $sites => $lic_data ) {
			if ( ! $first_license ) {
				$first_license = $lic_data;
			}
			$license_options .= "<option value='$sites|annual'>$lic_data[label] &mdash; Annual</option>";
		}
		$license_options .= "</optgroup><optgroup label='Lifetime'>";
		foreach ( $args['licenses'] as $sites => $lic_data ) {
			if ( ! empty( $lic_data['lifetime'] ) ) {
				$license_options .= "<option value='$sites|lifetime'>$lic_data[label] &mdash; Lifetime</option>";
			}
		}
		$license_options .= "</optgroup>";
		ob_start();
		$this->render_table2_styles( $args );
		echo "<div class='ppfs-price-table fs-$id-wrap' data-license-active='$first_license'>";
		?>
			<div class="ppfs-table">
				<header class="ppfs-title">
					<h2><?php echo $args['name'] ?></h2>
				</header>

				<section class="features">
					<h4 class="ppfs-price ppfs-opri">
						&nbsp;
					</h4>
					<h3 class="ppfs-price ppfs-price-<?php echo $id ?>">
						<span><?php echo $first_license['annual']?></span> / year
					</h3>
					<ul class="list">
						<li><?php echo $this->check_icon(); ?></i> <span class="ppfs-sites-<?php echo $id ?>">1 site</span> license</li>
						<li><?php echo $this->check_icon(); ?></i> <span class="ppfs-support-term-<?php echo $id ?>">1 year</span> support and updates</li>
						<li><?php echo $this->check_icon(); ?></i> 14-day money back guarantee</li>
					</ul>
				</section>

				<footer class="cta">
					<?php
					if ( $args['trial'] ) {
						$args['trial'] = "<p>Or <a href='#' class='fs-$id-trial'>start 14-day free trial</a></p>";
					}
					echo "<select class='fs-table-$id-license license-options db mha mt3 mb4 f4'>$license_options</select>";
					?>
					<a href="#" class="<?php echo "fs-$id-buy-subs"; ?> buy-btn">
						Buy Now
					</a>
					<?php echo $args['trial'] ?>
				</footer>
			</div>

			<div class="ppfs-table">
				<header class="ppfs-title pootle">
					<h2><?php echo $args['bundle']['subtitle'] ?></h2>
				</header>

				<section class="features">
					<h4 class="ppfs-price ppfs-opri ppfs-opri-stri">
						<span><?php echo $args['bundle']['reg_price'] ?></span> / year
					</h4>
					<h3 class="ppfs-price">
						<span><?php echo $args['bundle']['price'] ?></span> / year
					</h3>
					<ul class="list">
						<li><?php echo $this->check_icon(); ?></i> Single site</li>
						<li><?php echo $this->check_icon(); ?></i> Storefront Blocks</li>
						<li><?php echo $this->check_icon(); ?></i> WooBuilder Blocks</li>
						<li><?php echo $this->check_icon(); ?></i> Pootle Pagebuilder pro</li>
						<li><?php echo $this->check_icon(); ?></i> Storefront pro</li>
					</ul>
				</section>

				<footer class="cta">
					<a href="#" class="<?php echo "fs-$id-buy-bundle"; ?> buy-btn pootle">
						Buy Now
					</a>
					<p>Or <a href='https://jamiemarsland.staging.wpengine.com/pootle-bundles/'>find out more</a></p>
				</footer>
			</div>
		<?php
		echo '</div>';

		wp_enqueue_script( 'jquery' );
		add_action( 'wp_print_footer_scripts', function () use ( $args ) {
			$this->render_table2_script( $args );
		}, 999 );
		return ob_get_clean();
	}

	public function render_sfblocks_table2( $args ) {

		return $this->render_table2(
			wp_parse_args(
				$args,
				[
					'id'         => 'sfbk',
					'name'       => 'Storefront blocks',
					'trial'   => true,
					'licenses'   => [
						'1'         => [
							'label'    => '1 site',
							'annual'   => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label'    => '5 sites',
							'annual'   => '$75',
							'lifetime' => '$265',
						],
						'25'        => [
							'label'    => '25 sites',
							'annual'   => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites',
							'annual'   => '$199',
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

	public function render_woobuilder_blocks_table2( $args ) {

		return $this->render_table2(
			wp_parse_args(
				$args,
				[
					'id'         => 'woobk',
					'name'       => 'WooBuilder blocks',
					'trial'   => true,
					'licenses'   => [
						'1'         => [
							'label'    => '1 site',
							'annual'   => '$49',
							'lifetime' => '$199',
						],
						'5'         => [
							'label'    => '5 sites',
							'annual'   => '$75',
							'lifetime' => '$265',
						],
						'25'        => [
							'label'    => '25 sites',
							'annual'   => '$99',
							'lifetime' => '$595',
						],
						'unlimited' => [
							'label'    => 'Unlimited sites',
							'annual'   => '$199',
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
