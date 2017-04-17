<?php

class CM_Boxes_After_Cart {

	public function __construct() {

		add_action('woocommerce_after_cart', array($this, 'cm_calculation_boxes'));

	}

	public static function init() {

		$instance = false;

		if(!$instance) {

			$instance = new CM_Boxes_After_Cart;

		}

		return $instance;

	}

	public function cm_calculation_boxes() {


	   $cart_session = WC()->session->get( 'cm_calculate_session' );
	   $cart_add_cost_session = WC()->session->get( 'cm_add_cost_session' );
	   $cart_add_order_item_meta_p = WC()->session->get( 'cm_order_item_meta' );

	   //echo '<pre>'.print_r($cart_add_cost_session, true).'</pre>';

	   	$cart_session_ini = CM_Calculation_Session::init();
	 
	   	$cart_add_cost_session = $cart_session_ini->cart_add_session;
		$packages = WC()->shipping->get_packages();

		//echo '<pre>'.print_r(     $cart_add_order_item_meta_p  , true).'</pre>';

	   $class = "cm-hide";

	   if(!empty($cart_session )) {

	   		$class = "cm-show";
	 
	   }

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

	        // Output cart data
	        if( current_user_can('administrator') ) {
		        // echo ' Pack Unit: ' .$packunit . '<br>';
		        // echo ' Number per box: ' .$num_per_box . '<br>';
		        // echo ' QTY: ' . $quantity . '<br>';
	        }
		} 

	    	   // Special shipping
	    if (!empty($special_shipping)) {

	    	echo '<h4>Special Shipping Required</h4>';
	    	echo '<p>Note: One of the cart items requires special delivery. Call Cairns Marine HQ to discuss your order or continue with your order and we will contact you once we have received the details.</p>
			<p>The following item(s) have special delivery requirements:</p>';
			echo '<ul>';
			foreach ($special_shipping as $product_title) {
				echo '<li>' . $product_title . '</li>';
			}
			echo '</ul>';
			echo '<hr>';

			$class = "cm-hide";

			return;
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

		<div class="cm-table-calculation-con box-ajax <?php echo $class; ?>" >

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

			//WC()->session->set( 'cm_add_cost_session', $cm_add_cost);

			

			// $cost_products = WC()->cart->subtotal;
			// $cost_total = number_format($cost_freight + $cost_packaging + $cost_products, '2', '.' ,',');


		    ?>
		<!-- Fillers -->
			<div style="margin: 20px auto; text-align: right;">
				<label><input type="checkbox" name="checkbox" value="value">&nbsp;&nbsp;Check box to add fillers to your order</label>
			</div> 

		</div>

			
		<?php

	}

}