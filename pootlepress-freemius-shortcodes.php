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

require_once 'pootlepress-freemius-shortcodes-tables.php';

class Pootlepress_Freemius_Shortcodes extends Pootlepress_Freemius_Shortcodes_Tables {
	/** @var self Instance */
	private static $_instance;

	protected $url;

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		add_action( 'wp_print_footer_scripts', [ $this, 'wp_print_footer_scripts' ] );
		$this->register_select_shortcodes();
		$this->register_table_shortcodes();
	}

	public function wp_print_footer_scripts() {
		?>
		<script>
			jQuery( function ( $ ) {
				$( 'body' ).on( 'click', '.ppfs-co-woobuilder-blocks', function ( e ) {
					e.preventDefault();
					var handler = FS.Checkout.configure( {"plugin_id": "3514", "plan_id": "5685", "public_key": "pk_c52effbb9158dc8c4098e44429e4a"} );
					handler.open( {
						name         : 'WooBuilder blocks',
						purchaseCompleted: ppFreemiusTrackingCallback( 'WooBuilder blocks' ),
						success      : function ( response ) {},
						billing_cycle: 'annual',
						licenses     : 1
					} )
				} );
			} );
		</script>
		<?php
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_script( 'freemius-checkout', '//checkout.freemius.com/checkout.min.js', [ 'jquery' ] );
		wp_add_inline_script( 'freemius-checkout', <<<JS
		function ppFreemiusTrackingCallback( productName ) {
			return function ( response ) {
				console.log( 'Handling purchase success ' + productName );
				var
					isTrial        = (
						null != response.purchase.trial_ends
					),
					isSubscription = (
						null != response.purchase.initial_amount
					),
					total          = isTrial ? 0 : isSubscription ? response.purchase.initial_amount : response.purchase.gross,
					storeUrl       = 'https://pootlepress.com',
					storeName      = 'PootlePress';

				if ( typeof fbq !== "undefined" ) {
					fbq( 'track', 'Purchase', {
						currency: response.purchase.currency.toUpperCase(),
						value   : total
					} );
				}

				if ( typeof ga !== "undefined" ) {
					ga( 'send', 'event', 'plugin', 'purchase', productName, response.purchase.initial_amount.toString() );

					ga( 'require', 'ecommerce' );

					ga( 'ecommerce:addTransaction', {
						'id'         : response.purchase.id.toString(), // Transaction ID. Required.
						'affiliation': storeName,            					  // Affiliation or store name.
						'revenue'    : total,                 		      // Grand Total.
					} );

					ga( 'ecommerce:addItem', {
						'id'      : response.purchase.id.toString(),                // Transaction ID. Required.
						'name'    : productName,                                  // Product name. Required.
						'sku'     : response.purchase.plan_id.toString(),          // SKU/code.
						'category': 'Plugin',                                 // Category or variation.
						'price'   : response.purchase.initial_amount.toString(), // Unit price.
						'quantity': '1' // Quantity.
					} );

					ga( 'ecommerce:send' );

					ga( 'send', {
						hitType : 'pageview',
						page    : '/purchase-completed/',
						location: storeUrl + '/purchase-completed/'
					} );
				}
			};
		}
JS
		);
	}

	public function init() {
		$this->url = plugin_dir_url( __FILE__ );
	}

	protected function register_select_shortcodes() {
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

		wp_enqueue_script( 'jquery' );
		add_action( 'wp_print_footer_scripts', function () use ( $args ) {
			$id = $args['id'];
			echo "<script>
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
						fsOpenArgs.purchaseCompleted = ppFreemiusTrackingCallback( '$args[name]' );
						fsHandler.open( fsOpenArgs );
					});
				})(jQuery);
			</script>";
		}, 999 );

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
	<style>
	#fs-$id-license{width: 100%;}
	.fs-$id-wrap {width: 50%;min-width: 300px;margin: auto;text-align: center;font-size: 16px;}
	#fs-$id-trial-button,#fs-$id-buy-button{color: #ffffff;padding: 0 34px;font-size: 40px;font-weight: 200;letter-spacing: 3px;line-height: 1.6;border: 2px solid #c02812;background: #ef4832;cursor: pointer;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;}</style>
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

		return $this->render_select_button(
			wp_parse_args(
				$args,
				[
					'id'         => '18tp',
					'name'       => 'Eighteen tags pro',
					'licenses'   => [
						'1'         => [
							'label' => 'Single',
							'price' => '$99'
						],
						'5'         => [
							'label' => '5',
							'price' => '$135'
						],
						'unlimited' => [
							'label' => 'Unlimited',
							'price' => '$149'
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
	function render_sfpro( $args ) {
		return $this->render_select_button(
			wp_parse_args(
				$args,
				[
					'id'         => 'sfp',
					'name'       => 'Storefront pro',
					'licenses'   => [
						'1'         => [
							'label' => 'Single',
							'price' => '$49'
						],
						'5'         => [
							'label' => '5',
							'price' => '$75'
						],
						'25'         => [
							'label' => '25',
							'price' => '$99'
						],
						'unlimited' => [
							'label' => 'Unlimited',
							'price' => '$199'
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
	function render_sfblocks( $args ) {
		return $this->render_select_button(
			wp_parse_args(
				$args,
				[
					'id'         => 'sfbk',
					'name'       => 'Storefront blocks',
					'licenses'   => [
						'1'         => [
							'label' => 'Single',
							'price' => '$49'
						],
						'5'         => [
							'label' => '5',
							'price' => '$75'
						],
						'25'         => [
							'label' => '25',
							'price' => '$99'
						],
						'unlimited' => [
							'label' => 'Unlimited',
							'price' => '$199'
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
	 * WooBuilder Blocks short code
	 * @param $args
	 * @return string
	 */
	function render_woobuilder_blocks( $args ) {

		return $this->render_select_button(
			wp_parse_args(
				$args,
				[
					'id'         => 'woobk',
					'name'       => 'WooBuilder blocks',
					'licenses'   => [
						'1'         => [
							'label' => 'Single',
							'price' => '$49'
						],
						'5'         => [
							'label' => '5',
							'price' => '$75'
						],
						'25'         => [
							'label' => '25',
							'price' => '$99'
						],
						'unlimited' => [
							'label' => 'Unlimited',
							'price' => '$199'
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

Pootlepress_Freemius_Shortcodes::instance();
