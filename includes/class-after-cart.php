<?php

class CM_After_cart {

	public $cart_session;

	public function __construct() {

		add_action('woocommerce_after_cart', array($this, 'cm_total_boxes'));

	}

	public static function init() {

		$instance = false;

		if(!$instance) {
			$instance = new CM_After_cart;
		}

		return $instance;

	}


	public function cm_total_boxes () {

		$cart_session = CM_Calculation_Session::init();

		//echo '<pre>'.print_r( $cart_session, true).'</pre>';

		$selected_shipping_location = $cart_session->cart_session['shipping_location'];
		$selected_shipping_carrier =  $cart_session->cart_session['shipping_carrier'];
		$selected_shipping_time =  $cart_session->cart_session['shipping_time'];

		?>

		<div class="calculate-shipping-selection-con">

			<h2>Calculate Shipping</h2>

			<p style="margin-bottom: 10px;">Select / change destination, carrier and pickup time:</p>
			<form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" id="cm_calculate_shipping">
				<select name="shipping_location" id="shipping_location">
				   <option value="Select Airport">Select Airport</option>
				   <option <?php echo (($selected_shipping_location == 'Adelaide') ? 'selected'  : ''); ?> value="Adelaide">Adelaide</option>
				   <option <?php echo (($selected_shipping_location == 'Albury') ? 'selected'  : ''); ?> value="Albury">Albury</option>
				   <option <?php echo (($selected_shipping_location == 'Armidale') ? 'selected'  : ''); ?> value="Armidale">Armidale</option>
				   <option <?php echo (($selected_shipping_location == 'Ballina') ? 'selected'  : ''); ?> value="Ballina">Ballina</option>
				   <option <?php echo (($selected_shipping_location == 'Bathurst') ? 'selected'  : ''); ?> value="Bathurst">Bathurst</option>
				   <option <?php echo (($selected_shipping_location == 'Brisbane') ? 'selected'  : ''); ?> value="Brisbane">Brisbane</option>
				   <option <?php echo (($selected_shipping_location == 'Broome') ? 'selected'  : ''); ?> value="Broome">Broome</option>
				   <option <?php echo (($selected_shipping_location == 'Bundaberg') ? 'selected'  : ''); ?> value="Bundaberg">Bundaberg</option>
				   <option <?php echo (($selected_shipping_location == 'Cairns') ? 'selected'  : ''); ?> value="Cairns">Cairns</option>
				   <option <?php echo (($selected_shipping_location == 'Canberra') ? 'selected'  : ''); ?> value="Canberra">Canberra</option>
				   <option <?php echo (($selected_shipping_location == 'Coffs Harbour') ? 'selected'  : ''); ?> value="Coffs Harbour">Coffs Harbour</option>
				   <option <?php echo (($selected_shipping_location == 'Coolangatta') ? 'selected'  : ''); ?> value="Coolangatta">Coolangatta</option>
				   <option <?php echo (($selected_shipping_location == 'Darwin') ? 'selected'  : ''); ?> value="Darwin">Darwin</option>
				   <option <?php echo (($selected_shipping_location == 'Emerald') ? 'selected'  : ''); ?> value="Emerald">Emerald</option>
				   <option <?php echo (($selected_shipping_location == 'Geraldton') ? 'selected'  : ''); ?> value="Geraldton">Geraldton</option>
				   <option <?php echo (($selected_shipping_location == 'Gladstone') ? 'selected'  : ''); ?> value="Gladstone">Gladstone</option>
				   <option <?php echo (($selected_shipping_location == 'Griffith') ? 'selected'  : ''); ?> value="Griffith">Griffith</option>
				   <option <?php echo (($selected_shipping_location == 'Hamilton Island') ? 'selected'  : ''); ?> value="Hamilton Island">Hamilton Island</option>
				   <option <?php echo (($selected_shipping_location == 'Hervey Bay') ? 'selected'  : ''); ?> value="Hervey Bay">Hervey Bay</option>
				   <option <?php echo (($selected_shipping_location == 'Hobart') ? 'selected'  : ''); ?> value="Hobart">Hobart</option>
				   <option <?php echo (($selected_shipping_location == 'Launceston') ? 'selected'  : ''); ?> value="Launceston">Launceston</option>
				   <option <?php echo (($selected_shipping_location == 'Mackay') ? 'selected'  : ''); ?> value="Mackay">Mackay</option>
				   <option <?php echo (($selected_shipping_location == 'Maroochydore') ? 'selected'  : ''); ?> value="Maroochydore">Maroochydore</option>
				   <option <?php echo (($selected_shipping_location == 'Melbourne') ? 'selected'  : ''); ?> value="Melbourne">Melbourne</option>
				   <option <?php echo (($selected_shipping_location == 'Merimbula') ? 'selected'  : ''); ?> value="Merimbula">Merimbula</option>
				   <option <?php echo (($selected_shipping_location == 'Mildura') ? 'selected'  : ''); ?> value="Mildura">Mildura</option>
				   <option <?php echo (($selected_shipping_location == 'Moranbah') ? 'selected'  : ''); ?> value="Moranbah">Moranbah</option>
				   <option <?php echo (($selected_shipping_location == 'Moree') ? 'selected'  : ''); ?> value="Moree">Moree</option>
				   <option <?php echo (($selected_shipping_location == 'Newcastle') ? 'selected'  : ''); ?> value="Newcastle">Newcastle</option>
				   <option <?php echo (($selected_shipping_location == 'Orange') ? 'selected'  : ''); ?> value="Orange">Orange</option>
				   <option <?php echo (($selected_shipping_location == 'Parkes') ? 'selected'  : ''); ?> value="Parkes">Parkes</option>
				   <option <?php echo (($selected_shipping_location == 'Perth') ? 'selected'  : ''); ?> value="Perth">Perth</option>
				   <option <?php echo (($selected_shipping_location == 'Port Lincoln') ? 'selected'  : ''); ?> value="Port Lincoln">Port Lincoln</option>
				   <option <?php echo (($selected_shipping_location == 'Port Macquarie') ? 'selected'  : ''); ?> value="Port Macquarie">Port Macquarie</option>
				   <option <?php echo (($selected_shipping_location == 'Proserpine') ? 'selected'  : ''); ?> value="Proserpine">Proserpine</option>
				   <option <?php echo (($selected_shipping_location == 'Rockhampton') ? 'selected'  : ''); ?> value="Rockhampton">Rockhampton</option>
				   <option <?php echo (($selected_shipping_location == 'Sydney') ? 'selected'  : ''); ?> value="Sydney">Sydney</option>
				   <option <?php echo (($selected_shipping_location == 'Tamworth') ? 'selected'  : ''); ?> value="Tamworth">Tamworth</option>
				   <option <?php echo (($selected_shipping_location == 'Taree') ? 'selected'  : ''); ?> value="Taree">Taree</option>
				   <option <?php echo (($selected_shipping_location == 'Toowoomba') ? 'selected'  : ''); ?> value="Toowoomba">Toowoomba</option>
				   <option <?php echo (($selected_shipping_location == 'Townsville') ? 'selected'  : ''); ?> value="Townsville">Townsville</option>
				   <option <?php echo (($selected_shipping_location == 'Wagga Wagga') ? 'selected'  : ''); ?> value="Wagga Wagga">Wagga Wagga</option>
				   <option <?php echo (($selected_shipping_location == 'Whyalla') ? 'selected'  : ''); ?> value="Whyalla">Whyalla</option>
				</select>
			    <select name="shipping_carrier" id="shipping_carrier">
			    	<option value="Select Carrier">Select Carrier</option>
				   <option <?php echo (($selected_shipping_carrier == 'Qantas') ? 'selected'  : ''); ?> value="Qantas">Qantas</option>
				   <option <?php echo (($selected_shipping_carrier == 'Virgin') ? 'selected'  : ''); ?> value="Virgin">Virgin</option>
				</select>
			    <select name="shipping_time" id="shipping_time">
			    	<option value="Select Pickup Time">Select Pickup Time</option>
				   <option <?php echo (($selected_shipping_time == 'AM') ? 'selected'  : ''); ?> value="AM">Morning</option>
				   <option <?php echo (($selected_shipping_time == 'PM') ? 'selected'  : ''); ?> value="PM">Afternoon</option>
				</select>

				<input type="submit" value="Calculate Shipping" class="submit" />
			</form>

		</div>

	<?php

	}

}