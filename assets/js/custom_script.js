jQuery(document).ready(function( $ ) {

	var is_blocked = function( $node ) {
		return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
	};


	var block = function( $node ) {
		if ( ! is_blocked( $node ) ) {
			$node.addClass( 'processing' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );
		}
	};

	var unblock = function( $node ) {
		$node.removeClass( 'processing' ).unblock();
	};


	$("#cm_calculate_shipping").submit(function(evt) {
		evt.preventDefault();
	
		block( $( 'div.cart_totals' ) );
		block( $( 'div.cm-table-calculation-con' ) );

		var $form = $(this);

		var shipping_location = $(this).find('#shipping_location').val();
		var shipping_carrier = $(this).find('#shipping_carrier').val();
		var shipping_time = $(this).find('#shipping_time').val();
		
		$.ajax({
			type: "post",
			url: cm_ajax.ajaxurl,
			dataType: 'html',
			data: {
				action: 'cm_add_cart_fee',
				shipping_location_post: shipping_location,
				shipping_carrier_post: shipping_carrier,
				shipping_time_post: shipping_time,
				security: cm_ajax.nonce
			},
			beforeSend: function () {
				
				$('.cm-table-calculation-con').slideDown();
			},
			success: function (response) {

				//console.log(response);
				//$('.box-ajax').remove();
				//$('.cart-collaterals').slideDown();
				$( '.box-calc-wrap' ).replaceWith( response );

			},
			complete: function() {
				unblock( $( 'div.cart_totals' ) );
				unblock( $( 'div.cm-table-calculation-con' ) );
				$( document.body ).trigger( 'updated_shipping_method' );
			}
		});

		return false;

	})

});