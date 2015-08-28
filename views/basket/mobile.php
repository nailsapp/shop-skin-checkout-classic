<div class="visible-xs visible-sm">
    <h2>Your Basket</h2>
    <?php

    foreach ($items as $item) { ?>

        <div class="row basket-row">

            <?php

            if (!empty($item->variant->featured_img)) {

                $featuredImg = $item->variant->featured_img;

            } elseif (!empty($item->product->featured_img)) {

                $featuredImg = $item->product->featured_img;

            } else {

                $featuredImg = false;
            }


            if ($featuredImg) {

                echo '<div class="col-xs-3">';

                    $url = cdn_thumb($featuredImg, 175, 175);
                    echo img(array('src' => $url, 'class' => 'img-thumbnail'));

                echo '</div>';

            } else {

                echo '<div class="col-sm-12">';

            }

            ?>

            <div class="col-xs-7">
                <?= anchor($item->product->url, '<strong>' . $item->product->label . '</strong>'); ?>
                <?php
                if ($item->variant->label !== $item->product->label) {

                    echo '<br />';
                    echo '<em>' . $item->variant->label . '</em>';
                }
                ?>

                <div>

                <?php

                $omitVariantTaxPricing = app_setting('omit_variant_tax_pricing', 'shop-' . $skin->slug);

                if (app_setting('price_exclude_tax', 'shop')) {

                    echo '<strong class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
                        echo $item->variant->price->price->user_formatted->value_ex_tax;
                    echo '</strong>';

                    if (!$omitVariantTaxPricing && $item->variant->price->price->user->value_tax > 0) {

                        echo '<br />';
                        echo '<small class="text-muted">';
                            echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
                                echo $item->variant->price->price->user_formatted->value_inc_tax;
                            echo '</span>';
                            echo ' inc. tax';
                        echo '</small>';
                    }

                } else {

                    echo '<span class="variant-unit-price-inc-tax-' . $item->variant->id . '">';
                        echo $item->variant->price->price->user_formatted->value_inc_tax;
                    echo '</span>';

                    if (!$omitVariantTaxPricing && $item->variant->price->price->user->value_tax > 0) {

                        echo '<br />';
                        echo '<small class="text-muted">';
                            echo '<span class="variant-unit-price-ex-tax-' . $item->variant->id . '">';
                                echo $item->variant->price->price->user_formatted->value_ex_tax;
                            echo '</span>';
                            echo ' ex. tax';
                        echo '</small>';
                    }
                }

                ?>

                </div>

            </div>

            <div class="col-xs-2 text-center">
 
            <?php

            if (empty($readonly)) {

                /**
                 * Determine whether the user can increment the product. In order to be
                 * incrementable there must:
                 * - Be sufficient stock (or unlimited)
                 * - not exceed any limit imposed by the product type
                 */
                
                if (is_null($item->variant->quantity_available)) {

                    //  Unlimited quantity
                    $sufficient = true;

                } elseif ($item->quantity < $item->variant->quantity_available) {

                    //  Fewer than the quantity available, user can increment
                    $sufficient = true;

                } else {

                    $sufficient = false;
                }

                if (empty($item->product->type->max_per_order)) {

                    //  Unlimited additions allowed
                    $notExceed = true;

                } elseif ($item->quantity < $item->product->type->max_per_order) {

                    //  Not exceeded the maximum per order, user can increment
                    $notExceed = true;

                } else {

                    $notExceed = false;
                }

                if ($sufficient && $notExceed) {

                    echo anchor(
                        $shop_url . 'basket/increment?variant_id=' . $item->variant->id,
                        '<div class="basket-incrementer">
                            <span class="glyphicon glyphicon-plus-sign text-muted"></span>
                        </div>'
                    );
                }
            }

            echo '<span class="variant-quantity-' . $item->variant->id . '">';
                echo number_format($item->quantity);
            echo '</span>';


            if (empty($readonly)) {

                echo anchor(
                    $shop_url . 'basket/decrement?variant_id=' . $item->variant->id,
                    '<div class="basket-incrementer">
                        <span class="glyphicon glyphicon-minus-sign text-muted"></span>
                    </div>'
                );
            }


            ?>
            </div>

        </div>

    <?php

    }

    ?>

    <div class="basket-row">
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-left">Sub Total</div>
                <div class="pull-right"><b><?=$totals->user_formatted->item?></b></div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <!-- Shipping Total -->
                <?php

                $rowContext = $shippingType === 'DELIVER_COLLECT' || $shippingType === 'COLLECT' ? 'warning' : '';

                if (app_setting('warehouse_collection_enabled', 'shop')) {

                    echo '<div class="alert alert-warning">';

                    if ($shippingType === 'DELIVER') {

                        echo 'Shipping';

                        if (empty($readonly)) {

                            echo '<small>';
                            echo '<br />';
                            echo anchor(
                                $shop_url . 'basket/set_as_collection',
                                'Click here to collect your order'
                            );
                            echo '</small>';
                        }

                    } elseif ($shippingType === 'DELIVER_COLLECT') {

                        echo 'Your order will only be partially shipped';
                        echo '<div><small>';
                            echo 'Your order contains items which are collect only<br />These items will not be shipped';
                            echo '<br /><br />';
                            echo anchor(
                                $shop_url . 'basket/set_as_collection',
                                'Click here to collect your entire order'
                            );
                            echo '</small></div>';

                    } else {

                        echo 'You will collect your order ';

                        if (empty($readonly) && $basket->shipping->isDeliverable) {

                            echo '<small>';
                                echo '<br />';
                                echo anchor(
                                    $shop_url . 'basket/set_as_delivery',
                                    'Click here to have your order delivered'
                                );
                                echo '</small>';
                        }

                        $address   = array();
                        $address[] = app_setting('warehouse_addr_addressee', 'shop');
                        $address[] = app_setting('warehouse_addr_line1', 'shop');
                        $address[] = app_setting('warehouse_addr_line2', 'shop');
                        $address[] = app_setting('warehouse_addr_town', 'shop');
                        $address[] = app_setting('warehouse_addr_postcode', 'shop');
                        $address[] = app_setting('warehouse_addr_state', 'shop');
                        $address[] = app_setting('warehouse_addr_country', 'shop');
                        $address   = array_filter($address);

                        if ($address) {

                            $mapsUrl = 'http://maps.google.com/?q=' . urlencode(implode(', ', $address));

                            echo '<small>';
                                echo '<br /><strong>Collection from:</strong>';
                                echo '<br />' . implode('<br />', $address) . '<br />';
                                echo anchor($mapsUrl, 'Map', 'target="_blank"');
                            echo '</small>';
                        }
                    }

                    echo '</div>';

                } else {

                    echo 'Shipping';
                }



                if ($totals->user->shipping) {

                    echo $totals->user_formatted->shipping;

                } else {

                    echo 'Free';
                }

                ?>

            </div>
            
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-left">
                    <?php

                    if (app_setting('price_exclude_tax', 'shop')) {

                        echo 'Tax';

                    } else {

                        echo 'Tax (included)';
                    }

                    ?>
                </div>
                <div class="pull-right">
                    <?= $totals->user_formatted->tax; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-left">
                    Total
                </div>
                <div class="pull-right">
                    <b><?= $totals->user_formatted->grand ?></b>
                </div>
            </div>
        </div>
    </div>

</div>