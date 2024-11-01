jQuery(function($) {
    'use strict';

    // update mini cart content
    function sprm_update_cart(cart_fragment, count, total) {
        $('.sprm-mini-cart-content').html(cart_fragment);
        $('.sprm-mini-cart-total, .sprm-mini-cart-total-wrap .woocommerce-Price-amount').html(total);
        $('.sprm-mini-cart-count').html(count);
    }

    // refresh mini cart on item added to cart or removed
    $(document).on('added_to_cart removed_from_cart', function() {
        $.get(SPRM_AJAX_URL, {action: 'sprm_mini_cart'}, function(response) {
            if(response.status) {
                sprm_update_cart(response.cart_fragment, response.count, response.total);
            }
        });
    });

    // quick view variation options
    $(document).on('click', '.product_type_variable', function(e) {
        var $that = $(this);
        e.preventDefault();
        var product_id = $(this).data('product_id');
        $.get(SPRM_AJAX_URL, {action: 'sprm_quick_view', product_id: product_id}, function(response) {
            $that.closest('li').find('[data-sprm-product="' + product_id + '"]').html(response);
            $('html, body, root').animate({
                scrollTop: $that.closest('li').find('[data-sprm-product="' + product_id + '"]').offset().top - 50
            });

            setTimeout(function() {
                $that.closest('li').find('.variations_form').wc_variation_form();
            }, 500);

            var $cartForm = $that.closest('li').find('[data-sprm-product="' + product_id + '"]').find('form.cart');
            
            if($cartForm && !$cartForm.hasClass('variations_form')) {
                //$cartForm.append('<input type="hidden" name="add-to-cart" value="'+product_id+'" />');
            } else {
                $cartForm.find('[name=add-to-cart]').remove();
            }
        });
    });

    // on submission of variations options
    $(document).on('submit', '.sprm .variations_form', function(e) {
        
        e.preventDefault();
        
        var $form = $(this);
        if($form.hasClass('sprm-loading')) {
            return;
        }

        $form.addClass('sprm-loading');

        var $btnElem = $form.closest('.sprm-menu-item').find('.product_type_variable');
        var product_id = $btnElem.data('product_id');

        $.ajax({
            url: SPRM_AJAX_URL + '?action=sprm_add_to_cart_variation',
            data: $form.serialize(),
            type: 'post',
            success: function(response) {
                if(response.status) {
                    sprm_update_cart(response.cart_fragment, response.count, response.total);
                    $form.closest('.sprm-menu-item').find('[data-sprm-product="' + product_id + '"]').html("");
                }
            },
            complete: function() {
                $form.removeClass('sprm-loading');
            }
        });
    })

    // show search in default and skin-2
    $(document).on('click', '.sprm-button-show-search', function(e) {
        e.preventDefault();
        $('.sprm-search-wrap').addClass('active');
    });

    // hide search in default and skin-3
    $(document).on('click', '.sprm-button-hide-search', function(e) {
        e.preventDefault();
        $('#sprm-search-q').val('').change();
        $('.sprm-search-wrap').removeClass('active');
        $('.sprm-menu-item').show();
    });

    // search item in current list
    $(document).on('keyup', '#sprm-search-q', function() {
        $('.sprm-menu-item').hide();
        var q = $(this).val().toLowerCase();
        $('.sprm-menu-title').each(function() {
            var text = $.trim(this.innerText).toLowerCase();
            if(text.indexOf(q) !== -1) {
                $(this).closest('.sprm-menu-item').show();
            }
        });
    });

    // preventing search form to be submitted
    $(document).on('submit', '.sprm-search-wrap form', function(e) {
        e.preventDefault();
    });

    // toggling mini cart in mobile
    $(document).on('click', '.sprm-mini-cart-mobile-wrap', function(e) {
        $(".sprm-mini-cart").toggleClass("active");
    });

    // fetching products using ajax for each category on load. added a delay to reduce server load
    $('.sprm-category-menus-main').each(function() {
        var term_id = $(this).data('sprm_category');
        var $that = $(this);
        setTimeout(function() {
            $.get(SPRM_AJAX_URL, {action: 'sprm_product_list', term_id: term_id}, function(response) {
                $that.removeClass('sprm-menu-loading');
                $that.html(response);
            })
        }, 500);
    });
    
    // delete item from cart using ajax and refresh cart 
    $(document).on('click', '.sprm-cart-item-remove-link', function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), function() {
            $(document).trigger('removed_from_cart');
        });
    });

    // updating qty from mini cart and quick view of variation product
    $(document).on('click', '.sprm-qty-decrement, .sprm-qty-increment', function(e) {
        e.preventDefault();
        var $qty = $(this).closest('.quantity').find('.qty');
        var qty = parseInt($qty.val());

        if($(this).hasClass('sprm-qty-decrement')) {
            qty = qty - 1;
        } else {
            qty = qty + 1;
        }

        if(isNaN(qty) || qty < 1) {
            return;
        }

        $qty.val(qty);
        
        if($(this).closest('.sprm-mini-cart-content').length > 0) { // if this is triggered from mini cart so update cart qty
            
            var $cart_parameters = $(this).closest('.quantity').next('.sprm-cart-parameters');

            var cart_item_key = $cart_parameters.find('[name=sprm_cart_item_key]').val();
            var product_id = $cart_parameters.find('[name=sprm_product_id]').val();
        
            var data = {
                action: 'sprm_update_cart_item',
                product_id: product_id,
                cart_item_key: cart_item_key,
                qty: qty
            }

            $.get(SPRM_AJAX_URL, data, function(response) {
                if(response.status) {
                    sprm_update_cart(response.cart_fragment, response.count, response.total);
                }
            });
        }
    });

    // on scroll fixed category bar
    $(window).on('scroll', function() {

        if(!$('body').hasClass('sprm-body')) {
            return;
        }

        if($(window).scrollTop() > $('.sprm-content').offset().top) {
            $('.sprm-content').addClass('sprm-fixed');
        } else {
            $('.sprm-content').removeClass('sprm-fixed');
        }

        // on scroll fixed category and mini cart for skin 2 
        if($('body').hasClass('sprm-skin-2')) {

            var offsetTop = $('.sprm-content-top').offset().top
            var scrollTop = $(window).scrollTop();
            var difference = 15;
            if($(window).width() > 900 && scrollTop > offsetTop) {
                var marginTop = scrollTop - offsetTop;
                $('.sprm-content-top-inner').stop().animate({marginTop: marginTop+difference});
                if($('.sprm-mini-cart').height() > $(window).height()) {
                    difference = $('.sprm-mini-cart').height() - $(window).height();
                }
                $('.sprm-mini-cart').stop().animate({marginTop: marginTop-difference});
                
            } else {
                $('.sprm-content-top-inner, .sprm-mini-cart').stop().animate({marginTop: 0});
            }
        }
    });

});