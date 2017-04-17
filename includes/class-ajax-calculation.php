<?php

class CM_Ajax_Calculation {

	public function __construct() {

		add_action( 'wp_ajax_cm_add_cart_fee', array($this, 'cm_add_cart_fee' ) );
		add_action( 'wp_ajax_nopriv_cm_add_cart_fee',  array($this, 'cm_add_cart_fee' ) );

	}

	public static function init() {

		$instance = false;

		if(!$instance) {
			$instance = new CM_Ajax_Calculation;
		}

		return $instance;

	}

	public function cm_add_cart_fee() {

		check_ajax_referer( 'button_nonce', 'security' );
		
		global $woocommerce;

		$shipping_location = $_POST['shipping_location_post'];
		$shipping_carrier = $_POST['shipping_carrier_post'];
		$shipping_time = $_POST['shipping_time_post'];

		//$cm_session_unset = WC()->session->__unset( 'cm_calculate_session' );

		$data_session = array(
							'shipping_location' => (!empty($shipping_location)) ? $shipping_location : '',
							'shipping_carrier' => (!empty($shipping_carrier)) ? $shipping_carrier : '',
							'shipping_time' => (!empty($shipping_time)) ? $shipping_time : ''
						);

		$cm_session = WC()->session->set( 'cm_calculate_session', $data_session);


		// Get Cart
		$items =  WC()->cart->get_cart() ;

		// Create arrays
		$total_volume = array();
		$packunits = array();
		$packunit_names = array();
		$categories = array();
		$total_weight = array();
		$total_boxes = array();
		$boxes = array();
		$special_shipping = array();

		foreach($items as $item => $values) {
			    
	        // Get fields
	        $cart_product = $values['data']->post; 
	        $price = get_post_meta($values['product_id'] , '_price', true);
	        $packunit = ( function_exists( 'get_field' ) ) ? get_field( "packunit" , $values['product_id']) : '';
	        $num_per_box = ( function_exists( 'get_field' ) ) ? get_field( "num_per_box" , $values['product_id']) : '';
	        $product_title = $cart_product->post_title;
	        $quantity = $values['quantity'];
	        $category = ( function_exists( 'get_field' ) ) ? get_field( 'category' , $values['product_id']) : '';
	        $subgroup = ( function_exists( 'get_field' ) ) ? get_field( 'subgroup' , $values['product_id']) : '';
	        $stockitem = ( function_exists( 'get_field' ) ) ? get_field( 'stock_item' , $values['product_id']) : '';

	        // Build arrays
	        $total_volume[] = $num_per_box * $quantity;

	        //$packunits[$packunit] =  $num_per_box * $quantity;

	 		$packunits = array($packunit => $num_per_box * $quantity);

	       //$packunit_names[] = $packunit;

	        $packunit_names = array( 0 => $packunit);

	        //$categories[$category] = $num_per_box * $quantity;

	        $categories = array( $category => $num_per_box * $quantity);
	      
	  
	        // Special shipping
	        if (strpos($stockitem, 'Shark') !== false) {
			    $special_shipping[] = $stockitem;
			}

	        // BOX TYPES //
	        // FISHBOX
			if ($category == 'FISH-Tropical') {
				$name = 'Fish Box';
				$boxes[$name] = $num_per_box * $quantity;
			} elseif ($category == 'INVERTS-Tropical') {
				$name = 'Fish Box';
				$boxes[$name] = $num_per_box * $quantity;
			} elseif ($category == 'FISH-Subtropical') {
				$name = 'Fish Box';
				$boxes[$name] = $num_per_box * $quantity;
			} elseif ($category == 'INVERTS-Subtropical') {
				$name = 'Fish Box';
				$boxes[$name] = $num_per_box * $quantity;
			// MIXED BOX OF FISH
			} elseif ($subgroup == 'Mixed Fish') {
				$name = 'Mixed Box of Fish';
				$boxes[$name] = $num_per_box * $quantity;
			// CORAL BOX
			} elseif ($category == 'CORALS') {
				$name= 'Coral Box';
				$boxes[$name] = $num_per_box * $quantity;
			// MIXED BOX OF CORAL
			} elseif ($subgroup == 'Mixed Coral') {
				$name = 'Mixed Box of Coral';
				$boxes[$name] = $num_per_box * $quantity;
			// Live Rock 20KG
			// Rockerostris greenarostris - Classic (Green Reef Rock) - Large
			} elseif ($stockitem == 'Rockerostris greenarostris - Classic (Green Reef Rock) - Large') {
				$name = 'Box of Live Rock';
				$boxes[$name] = $num_per_box * $quantity;
			// Rockerostris purgemetopon -  (Premium Cured Rock) - Large
			} elseif ($stockitem == 'Rockerostris purgemetopon -  (Premium Cured Rock) - Large') {
				$name = 'Box of Live Rock';
				$boxes[$name] = $num_per_box * $quantity;
			// Rockerostris xmasarostris - AquaScape (Christmas Rock) - XtraLarge
			} elseif ($stockitem == 'Rockerostris xmasarostris - AquaScape (Christmas Rock) - XtraLarge') {
				$name= 'Box of Live Rock';
				$boxes[$name] = $num_per_box * $quantity;
			// Live Rock 15KG - Rockerostris xmasarostris - AquaScape (Christmas Rock) - Large
			} elseif ($stockitem == 'Rockerostris xmasarostris - AquaScape (Christmas Rock) - Large') {
				$name = 'Box of Live Rock (15KG)';
				$boxes[$name] = $num_per_box * $quantity;
			// Dry Goods
			} elseif ($category == 'Dry Goods') {
				$name = 'Dry Goods';
				$boxes[$name] = 0;
			// Everything else
			} else {
				$name = 'Other';
				$boxes[$name] = $num_per_box * $quantity;
			}

		   	//echo '<pre>'.print_r(  $price, true).'<pre>';

		}


	        // Set box multiplier
	    $box_multiplier = 1;
	    if ($cart_session['shipping_time'] == 'AM') {
	    	$box_multiplier = 1.5;
	    } elseif ($cart_session['shipping_time'] == 'PM') {
	    	$box_multiplier = 1;
	    } elseif ($cart_session['shipping_time'] == 'AM') {
	    	$box_multiplier = 1.5;
	    }

		?>

		<div class="box-calc-wrap">

			<div class="cm-table-calculation-con ajax-box <?php echo $class; ?>" >

				<h2>Boxes &amp; Weight</h2>

				<table>

					<tr>
						<th>Box Type</th>
						<th># Boxes</th>
						<th>Weight</th>
						<th></th>
					</tr>
			
					<?php

					foreach($boxes as $name => $volume) {
						
						// Set pack unit name
						if ($name == 'Fish Box') {
							$packunit_type_weight = 18;
						} elseif ($name == 'Mixed Box of Fish') {
							$packunit_type_weight = 18;
						} elseif ($name == 'Coral Box') {
							$packunit_type_weight = 20;
						} elseif ($name == 'Mixed Box of Coral') {
							$packunit_type_weight = 20;
						} elseif ($name == 'Box of Live Rock') {
							$packunit_type_weight = 20;
						} elseif ($name == 'Box of Live Rock (15KG)') {
							$packunit_type_weight = 15;
						} elseif ($name == 'Dry Goods') {
							$packunit_type_weight = 0;
						} elseif ($name == 'Other') {
							$packunit_type_weight = 20;
						}

						// Box multiplier for fish and corals
						if ($name == 'Fish Box' || $name == 'Coral Box') {
							$volume = $volume * $box_multiplier;
						}



						// Calculate box count
						$box_count = ceil($volume);
						$total_boxes[] = $box_count;

						// Calculate weight
						$weight = $volume * $packunit_type_weight;
						$total_weight[] = $weight;



						echo '<tr>';

							echo '<td>';

								echo $name;

							echo '</td>';

							echo '<td>';

								echo $box_count;

							echo '</td>';

							echo '<td>';

								if ($weight != '0') {
									echo number_format($weight, '4') .' kg';
								} else {
									echo 'NA';
								}

							echo '</td>';

							echo '<td>';

								// Volume notice
								if (floor($volume) != $volume) {

									$vol = '<strong>Box not full:</strong> Your current selected products add up to ' . number_format($volume, '4') . ' boxes</small>';

									echo $vol;
								} 


						echo '</td>';

					
						
						echo '</tr>';

						$total_number_boxes = $box_count + $box_count;
				
					}
					
				?>

				</table>

			

				<?php
			

					

				$freight_base = "";
			    // Set freight base - Qantas
			    if (($cart_session['shipping_carrier'] == 'Qantas')) {
			    	$freight_base = 35;
			    // Set freight base - Virgin
			    } elseif (($cart_session['shipping_carrier'] == 'Virgin')) {
			    	$freight_base = 30;
			    }
			 
			    // Set per KG rate
			    $freight_rate = 1;

			    if (($cart_session == 'Sydney')) {

			    	$freight_rate = 1.75;

			    } elseif (($cart_session['shipping_location'] == 'Townsville')) {

			    	$freight_rate = 1.40;

			    } elseif (($cart_session['shipping_location'] == 'Perth')) {

			    	$freight_rate = 4.60;

			    }

			    // Freight Calculation

				$cost_packaging = (number_format(array_sum($total_boxes)) * 15);

				$cost_freight = $freight_base + array_sum($total_weight) * $freight_rate;
				$cost_freight = number_format($cost_freight, '2');


				$cm_add_cost = array(
							'cost_packaging' => $cost_packaging,
							'cost_freight' => $cost_freight,
							'total_number_boxes' => $total_number_boxes
					);

				WC()->session->set( 'cm_add_cost_session', $cm_add_cost);

				
				$cm_order_item_meta = array(
					'total_number_boxes' =>  $total_number_boxes,
					'shipping_location' => (!empty($shipping_location)) ? $shipping_location : '',
					'shipping_carrier' => (!empty($shipping_carrier)) ? $shipping_carrier : '',
					'shipping_time' => (!empty($shipping_time)) ? $shipping_time : ''
				);
				

		 		WC()->session->set( 'cm_order_item_meta', $cm_order_item_meta);
			




				// $cost_products = WC()->cart->subtotal;
				// $cost_total = number_format($cost_freight + $cost_packaging + $cost_products, '2', '.' ,',');


			    ?>
			<!-- Fillers -->
				<div style="margin: 20px auto; text-align: right;">
					<label><input type="checkbox" name="checkbox" value="value">&nbsp;&nbsp;Check box to add fillers to your order</label>
				</div> 

				</div>



				<div class="cart-collaterals">
					<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

						<?php do_action( 'woocommerce_before_cart_totals' ); ?>

						<h2><?php _e( 'Cart totals', 'woocommerce' ); ?></h2>

						<table cellspacing="0" class="shop_table shop_table_responsive">

						
							
							<tr class="boxes">

								<th>Number of Boxes</th>

								<td>
									<?php
										echo $cm_add_cost['total_number_boxes']; 
									?>
								</td>

							</tr>

							<tr class="destination">

								<th>Destination</th>

								<td>
								 	<?php

								 		$shipping_location_return = "No Shipping location Selected";

									 	if(!empty($shipping_location) && $shipping_location != 'Select Airport') {
									 		$shipping_location_return = $shipping_location;
									 	} 

									 	echo $shipping_location_return;
								 	?>
								</td>
								
							</tr>

							<tr class="carrier">

								<th>Carrier</th>

								<td>
								 	<?php

								 		$shipping_carrier_return = "No Carrier Selected";

									 	if(!empty($shipping_carrier) && $shipping_carrier != 'Select Carrier') {
									 		$shipping_carrier_return = $shipping_carrier;
									 	} 

									 	echo $shipping_carrier_return;
								 	?>
								</td>
								
							</tr>

							<tr class="time">
								<th>Pickup Time</th>
								<td> 
									<?php

								 		$shipping_time_return = "No Pick up Time Selected";

									 	if(!empty($shipping_time) && $shipping_time != 'Select Pickup Time') {
									 		$shipping_time_return = $shipping_time;
									 	} 

									 	echo $shipping_time_return;
								 	?>

								</td>
							</tr>

							<tr class="cart-subtotal">
								<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
								<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
							</tr>

							<tr class="fee">
								<th>Packaging</th>
								<?php
							 		$cart_add_cost_session = $cart_session_ini->cart_add_session;
								?>	

								<td> 
									<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>
									<?php 

									if(!empty($cart_add_cost_session)) {

										echo $cart_add_cost_session['cost_packaging']; 

									} else {

										echo $cm_add_cost['cost_packaging']; 

									}
								

									?>
									</span> <input type="hidden" name="shipping_method[0]" data-index="0" id="shipping_method_0" value="flat_rate:12" class="shipping_method">		
								</td>
							</tr>

						
							<tr class="shipping">

								<th>Freight</th>

								<td data-title="Shipping">

								 <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>


								 <?php 

									if(!empty($cart_add_cost_session)) {

										echo $cart_add_cost_session['cost_freight']; 

									} else {

										echo $cm_add_cost['cost_freight']; 

									}
								

								?>

								

								 </span> <input type="hidden" name="shipping_method[0]" data-index="0" id="shipping_method_0" value="flat_rate:12" class="shipping_method">		

								</td>
							</tr>

							<tr class="order-total">
								<th><?php _e( 'Total', 'woocommerce' ); ?></th>
								<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
							</tr>

						

						</table>

						<div class="wc-proceed-to-checkout">
							<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
						</div>

					</div>
				</div>

			</div>

		<?php

	 	wp_die();

	}




}