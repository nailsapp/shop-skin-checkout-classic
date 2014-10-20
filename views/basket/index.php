<div class="nails-shop-skin-checkout-classic basket">
	<div class="row">
		<div class="col-xs-12">
		<?php

			if ( ! empty( $basket->items ) ) :

				$this->load->view( $skin_checkout->path . 'views/basket/table', array( 'items' => $basket->items, 'totals' => $basket->totals ) );

				?>
				<hr />
				<div class="row">
					<div class="col-xs-12">
						<div class="well well-sm">
							<?=form_open( $shop_url . 'basket/add_voucher', 'class="add-voucher"' )?>
							<div class="row">
								<div class="col-sm-10">
									<?=form_input( 'voucher', '', 'placeholder="Enter your promotional voucher, if you have one." class="form-control"' )?>
								</div>
								<div class="col-sm-2">
									<button type="submit" class="btn btn-primary btn-block">
										Add Voucher
									</button>
								</div>
							</div>
							<?=form_close()?>
						</div>
					</div>
				</div>
				<hr />
				<p class="text-center">
					<?=anchor( $continue_shopping_url, 'Continue Shopping', 'class="btn btn-lg btn-primary"' )?>
					<?=anchor( $shop_url . 'checkout', 'Checkout Now', 'class="btn btn-lg btn-success"' )?>
				</p>
				<hr />
				<?php

			else :

				?>
				<div class="basket-empty well well-default">
					<h3 class="text-center">
						Your basket is empty
					</h3>
				</div>
				<?php


			endif;

		?>
		</div>
	</div>
	<?php if ( ! empty( $recently_viewed ) ) : ?>
	<div class="row">
		<div class="col-md-12">
			<h4>Recently Viewed</h4>
		</div>
	</div>
	<div class="row product-browser">
	<?php

		foreach( $recently_viewed AS $product ) :

			echo '<div class="product col-sm-2">';

				if ( $product->featured_img ) :

					$_url = cdn_thumb( $product->featured_img, 360, 360 );

				else :

					$_url = $skin_front->url . 'assets/img/product-no-image.png';

				endif;

				echo '<div class="product-image">';
					echo anchor( $product->url, img( array( 'src' => $_url, 'class' => 'img-responsive img-thumbnail center-block' ) ) );

					if ( count( $product->variations ) > 1 ) :

						if ( app_setting( 'browse_product_ribbon_mode', 'shop-' . $skin_front->slug ) == 'corner' ) :

							echo '<div class="ribbon corner">';
								echo '<div class="ribbon-wrapper">';
									echo '<div class="ribbon-text">' . count( $product->variations ) . ' options' . '</div>';
								echo '</div>';
							echo '</div>';

						else :

							echo '<div class="ribbon horizontal">';
								echo count( $product->variations ) . ' options available';
							echo '</div>';

						endif;

					endif;

				echo '</div>';

				echo '<p>' . anchor( $product->url, $product->label ) . '</p>';
				echo '<p>';
					echo '<span class="badge">' . $product->price->user_formatted->price_string . '</span>';
				echo '</p>';
				echo '<hr class="hidden-sm hidden-md hidden-lg" />';

			echo '</div>';

		endforeach;

	?>
	</div>
	<?php endif; ?>
</div>