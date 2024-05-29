toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

function currency_change(currency_code) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#currency-route").data("currency-route"),
        data: {
            currency_code: currency_code,
        },
        success: function (data) {
            toastr.success(data.success + data.name);
            location.reload();
        },
    });
}

function global_search() {
    $(".search-card").css("display", "block");
    let name = $(".search-bar-input").val();
    let category_id = $("#search_category_value").val();
    let base_url = $('meta[name="base-url"]').attr("content");
    if (name.length > 0) {
        $.get({
            url: base_url + "/searched-products",
            dataType: "json",
            data: {
                name,
                category_id,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $(".search-result-box").show().empty().html(data.result);
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    } else {
        $(".search-result-box").empty();
    }
}

$(".search-bar-input-mobile").keyup(function () {
    $(".search-card").css("display", "block");
    let name = $(".search-bar-input-mobile").val();
    let base_url = $('meta[name="base-url"]').attr("content");
    if (name.length > 0) {
        $.get({
            url: base_url + "/searched-products",
            dataType: "json",
            data: {
                name,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $(".search-result-box").empty().html(data.result);
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    } else {
        $(".search-result-box").empty();
    }
});

function couponCode() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#coupon-apply").data("url"),
        data: $("#coupon-code-ajax").serializeArray(),
        success: function (data) {
            if (data.status == 1) {
                let ms = data.messages;
                ms.forEach(function (m, index) {
                    toastr.success(m, index, {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                });
            } else {
                let ms = data.messages;
                ms.forEach(function (m, index) {
                    toastr.error(m, index, {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                });
            }
            setInterval(function () {
                location.reload();
            }, 2000);
        },
    });
}

function cartQuantityInitialize() {
    $(".btn-number").click(function (e) {
        e.preventDefault();

        fieldName = $(this).attr("data-field");
        type = $(this).attr("data-type");
        productType = $(this).attr("product-type");
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (type == "minus") {
                if (currentVal > input.attr("min")) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr("min")) {
                    $(this).attr("disabled", true);
                }
            } else if (type == "plus") {
                if (
                    currentVal < input.attr("max") ||
                    productType === "digital"
                ) {
                    input.val(currentVal + 1).change();
                }

                if (
                    parseInt(input.val()) == input.attr("max") &&
                    productType === "physical"
                ) {
                    $(this).attr("disabled", true);
                }
            }
        } else {
            input.val(0);
        }
    });

    $(".input-number").focusin(function () {
        $(this).data("oldValue", $(this).val());
    });

    $(".input-number").change(function () {
        productType = $(this).attr("product-type");
        minValue = parseInt($(this).attr("min"));
        maxValue = parseInt($(this).attr("max"));
        valueCurrent = parseInt($(this).val());

        var name = $(this).attr("name");
        if (valueCurrent >= minValue) {
            $(
                ".btn-number[data-type='minus'][data-field='" + name + "']"
            ).removeAttr("disabled");
        } else {
            Swal.fire({
                icon: "error",
                title: "Cart",
                text: "Sorry, the minimum order quantity does not match",
            });
            $(this).val($(this).data("oldValue"));
        }
        if (productType === "digital" || valueCurrent <= maxValue) {
            $(
                ".btn-number[data-type='plus'][data-field='" + name + "']"
            ).removeAttr("disabled");
        } else {
            Swal.fire({
                icon: "error",
                title: "Cart",
                text: "Sorry, stock limit exceeded.",
            });
            $(this).val($(this).data("oldValue"));
        }
    });
    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (
            $.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)
        ) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if (
            (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
            (e.keyCode < 96 || e.keyCode > 105)
        ) {
            e.preventDefault();
        }
    });
}

function quickView(product_id, url) {
    $.get({
        url: url,
        dataType: "json",
        data: {
            product_id: product_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            $("#quickViewModal_content").empty().html(data.view);
            $("#quickViewModal").modal("show");
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

// Product Details Color OnClick Image Change || Start
function focus_preview_image_by_color(key) {
    let swiper_slide = new Swiper(".quickviewSlider2", {});
    swiper_slide.slideTo(key, 200, false);
    $(".color_variants").removeClass("color_variant_active");
    $(`#color_variants_${key}`).addClass("color_variant_active");
}
function slider_thumb_img_preview(key) {
    let mySwiper = new Swiper(".quickviewSlider2", {
        pagination: {
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    let targetSlide = $(`.${key}`);
    let slideIndex = targetSlide.index();
    mySwiper.slideToLoop(slideIndex, 300, false);
}
// Product Details Color OnClick Image Change || End

// Product Add To Wishlist || Start
function addWishlist(product_id, action_url) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });

    $.ajax({
        url: action_url,
        method: "POST",
        data: {
            product_id,
        },
        success: function (data) {
            if (data.value == 1) {
                toastr.success(data.success);
                $(`.wishlist-${product_id}`).addClass("wishlist_icon_active");
                $(".wishlist_count_status").html(
                    parseInt($(".wishlist_count_status").html()) + 1
                );
            } else if (data.value == 2) {
                $(`.wishlist-${product_id}`).removeClass(
                    "wishlist_icon_active"
                );
                $(".wishlist_count_status").html(
                    parseInt($(".wishlist_count_status").html()) - 1
                );
                toastr.success(data.error);
            } else {
                toastr.error(data.error);
                $("#quickViewModal").modal("hide");
                $("#loginModal").modal("show");
            }
        },
    });
}
// Product Add To Wishlist || End
// Product Compare list
function addCompareList(product_id, action_url) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });

    $.ajax({
        url: action_url,
        method: "POST",
        data: {
            product_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.value == 1) {
                toastr.success(data.success);
                $(`.compare_list_icon_active`).removeClass(
                    "compare_list_icon_active"
                );
                $(".compare_list_count_status").html(data.count);

                $.each(data.compare_product_ids, function(key, id) {
                    $(`.compare_list-${id}`).addClass(
                        "compare_list_icon_active"
                    );
                });
            } else if (data.value == 2) {
                $(`.compare_list_icon_active`).removeClass(
                    "compare_list_icon_active"
                );
                $.each(data.compare_product_ids, function(key, id) {
                    $(`.compare_list-${id}`).addClass(
                        "compare_list_icon_active"
                    );
                });
                $(".compare_list_count_status").html(data.count);
                toastr.success(data.error);
            } else {
                toastr.error(data.error);
                $("#quickViewModal").modal("hide");
                $("#loginModal").modal("show");
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}
// End of product Compare List

// Product Share Link Generator JS || Start
function shareOnFacebook(url, social) {
    var width = 600,
        height = 400,
        left = (screen.width - width) / 2,
        top = (screen.height - height) / 2;
    window.open(
        "https://" + social + encodeURIComponent(url),
        "Popup",
        "toolbar=0,status=0,width=" +
            width +
            ",height=" +
            height +
            ",left=" +
            left +
            ",top=" +
            top
    );
}
// Product Share Link Generator JS || End

function checkAddToCartValidity(form_id) {
    var names = {};
    $("." + form_id + " input:radio").each(function () {
        // find unique names
        names[$(this).attr("name")] = true;
    });
    var count = 0;
    $.each(names, function () {
        // then count them
        count++;
    });
    if ($("." + form_id + " input:radio:checked").length == count) {
        return true;
    }
    return false;
}

// Product Buy Now Button Action || Start
function buy_now(form_id, redirect_status, url = null) {
    addToCart(form_id, redirect_status);
    if (redirect_status == true) {
        url != null ? (location.href = url) : "";
    } else {
        $("#quickViewModal").modal("hide");
        $("#loginModal").modal("show");
    }
}
// Product Buy Now Button Action || End

$("#add-to-cart-form input").on("change", function () {
    stock_check();
});

function stock_check() {
    minValue = parseInt($(".product_quantity__qty").attr("min"));
    maxValue = parseInt($(".product_quantity__qty").attr("max"));
    valueCurrent = parseInt($(".product_quantity__qty").val());
    let product_qty = $(".product_quantity__qty");

    if (minValue >= valueCurrent) {
        $(".product_quantity__qty").val(minValue);
        product_qty
            .parent()
            .find(".quantity__minus")
            .html('<i class="bi bi-trash3-fill text-danger fs-10"></i>');
    } else {
        product_qty
            .parent()
            .find(".quantity__minus")
            .html('<i class="bi bi-dash"></i>');
    }

    if (valueCurrent > maxValue) {
        toastr.warning("Sorry, stock limit exceeded");
        $(".product_quantity__qty").val(maxValue);
    }
    getVariantPrice();
}

/* Increase */
$(".quantity__plus").on("click", function () {
    var $qty = $(this).parent().find("input");
    var currentVal = parseInt($qty.val());
    if (!isNaN(currentVal)) {
        $qty.val(currentVal + 1);
    }
    if (currentVal >= $qty.attr("max") - 1) {
        $(this).attr("disabled", true);
    }
    // quantityListener();
    stock_check();
});

/* Decrease */
$(".quantity__minus").on("click", function () {
    var $qty = $(this).parent().find("input");
    var currentVal = parseInt($qty.val());
    if (!isNaN(currentVal) && currentVal > 1) {
        $qty.val(currentVal - 1);
    }
    if (currentVal < $qty.attr("max")) {
        $(".quantity__plus").removeAttr("disabled", true);
    }
    // quantityListener();
    stock_check();
});

$("#add-to-cart-form").on("submit", function (e) {
    e.preventDefault();
});

function addToCart(form_id, redirect_to_checkout = false) {
    if (
        checkAddToCartValidity(form_id) &&
        $("#" + form_id + " input[name=quantity]").val() != 0
    ) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        $.post({
            url: $(`#` + form_id).attr("action"),
            data: $("#" + form_id).serializeArray(),
            beforeSend: function () {},
            success: function (response) {
                if (response.status == 1) {
                    updateNavCart();
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 3000, // duration
                    });
                    $("#quickViewModal").modal("hide");
                    return false;
                } else if (response.status == 0) {
                    toastr.warning(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000, // duration
                    });
                    return false;
                }
            },
            complete: function () {},
        });
    } else if ($("#" + form_id + " input[name=quantity]").val() == 0) {
        toastr.warning($(`#` + form_id).data("outofstock"), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000, // duration
        });
    } else {
        toastr.info($(`#` + form_id).data("errormessage"), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000, // duration
        });
    }
}

function updateNavCart() {
    let url = $("#update_nav_cart_url").data("url");
    $.post(
        url,
        {
            _token: $('meta[name="_token"]').attr("content"),
        },
        function (response) {
            $("#cart_items").html(response.data);
            $("#mobile_app_bar").html(response.mobile_nav);
        }
    );
}

function removeFromCart(key) {
    let cart_quantity_of = $(`#cart_quantity_of_${key}`).val();
    let url = $("#remove_from_cart_url").data("url");
    if (cart_quantity_of == 1) {
        $.post(
            url,
            {
                _token: $('meta[name="_token"]').attr("content"),
                key: key,
            },
            function (response) {
                updateNavCart();
                toastr.info(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                });
                let segment_array = window.location.pathname.split("/");
                let segment = segment_array[segment_array.length - 1];
                if (
                    segment === "checkout-payment" ||
                    segment === "checkout-details"
                ) {
                    location.reload();
                }
            }
        );
    } else {
        console.log($(this));
        let $qty = $(this).parent().find("input");
        let currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $qty.val(currentVal - 1);
        }
        if (currentVal < $qty.attr("max")) {
            $(".quantity__plus").removeAttr("disabled", true);
        }
        var qty = $(this);
        if (qty.val() == 1) {
            qty.siblings(".quantity__minus").html(
                '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
            );
        } else {
            qty.siblings(".quantity__minus").html('<i class="bi bi-dash"></i>');
        }
    }
}

function updateCartQuantity(cart_id, product_id, action, event) {
    let remove_url = $("#remove_from_cart_url").data("url");
    let update_quantity_url = $("#update_quantity_url").data("url");
    let token = $('meta[name="_token"]').attr("content");
    let product_qyt =
        parseInt($(`.cart_quantity_of_${cart_id}`).val()) + parseInt(action);
    let cart_quantity_of = $(`.cart_quantity_of_${cart_id}`);
    let segment_array = window.location.pathname.split("/");
    let segment = segment_array[segment_array.length - 1];

    if (
        (cart_quantity_of.val() == cart_quantity_of.data("min") &&
            event == "minus") ||
        cart_quantity_of.val() == 0
    ) {
        $.post(
            remove_url,
            {
                _token: token,
                key: cart_id,
            },
            function (response) {
                updateNavCart();
                toastr.info(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                });
                if (
                    segment === "shop-cart" ||
                    segment === "checkout-payment" ||
                    segment === "checkout-details"
                ) {
                    location.reload();
                }
            }
        );
    } else {
        if(cart_quantity_of.val() < cart_quantity_of.data("min")){
            let min_value = cart_quantity_of.data("min");
            toastr.error('Minimum order quantity cannot be less than '+min_value);
            cart_quantity_of.val(min_value)
            updateCartQuantity(cart_id, product_id, action, event)
        }else{
            $(`.cart_quantity_${cart_id}`).html(product_qyt);
            $.post(
                update_quantity_url,
                {
                    _token: token,
                    key: cart_id,
                    product_id: product_id,
                    quantity: product_qyt,
                },
                function (response) {
                    if (response["status"] == 0) {
                        toastr.error(response["message"]);
                    } else {
                        toastr.success(response["message"]);
                    }
                    response["qty"] <= 1
                        ? $(`.cart_quantity__minus${cart_id}`).html(
                            '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
                        )
                        : $(`.cart_quantity__minus${cart_id}`).html(
                            '<i class="bi bi-dash"></i>'
                        );

                    $(`.cart_quantity_of_${cart_id}`).val(response["qty"]);
                    $(`.cart_quantity_${cart_id}`).html(response["qty"]);
                    $(".cart_total_amount").html(response.total_price);
                    $(`.discount_price_of_${cart_id}`).html(
                        response["discount_price"]
                    );
                    $(`.quantity_price_of_${cart_id}`).html(
                        response["quantity_price"]
                    );

                    if (response["qty"] == cart_quantity_of.data("min")) {
                        cart_quantity_of
                            .parent()
                            .find(".quantity__minus")
                            .html(
                                '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
                            );
                    } else {
                        cart_quantity_of
                            .parent()
                            .find(".quantity__minus")
                            .html('<i class="bi bi-dash"></i>');
                    }
                    if (
                        segment === "shop-cart" ||
                        segment === "checkout-payment" ||
                        segment === "checkout-details"
                    ) {
                        location.reload();
                    }
                }
            );
        }
    }
}

// Product Variant Function for details page & quick view
function getVariantPrice() {
    if (
        $("#add-to-cart-form input[name=quantity]").val() > 0 &&
        checkAddToCartValidity("add_to_cart_form")
    ) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });

        let qty_val = $(".product_quantity__qty").val();
        $.ajax({
            type: "POST",
            url: $("#add-to-cart-form").data("varianturl"),
            data: $("#add-to-cart-form").serializeArray(),
            success: function (data) {
                $("#add-to-cart-form .total_price").html(data.price);
                $(".product_vat").html(data.update_tax);
                $(".in_stock_status").html(data.quantity);

                if (data.quantity > qty_val) {
                    $(".single_quantity__plus").removeAttr("disabled", true);
                    $(".product_quantity__qty").attr("max", data.quantity);
                } else {
                    if(data.quantity < qty_val) {
                        $(".single_quantity__plus").attr("disabled", true);
                        $(".single_quantity__minus").attr("disabled", true);
                    }else if(data.quantity <= 0) {
                        $(".product_quantity__qty").val(
                            parseInt($(".product_quantity__qty").attr("min"))
                        );
                    } else {
                        $(".product_quantity__qty").attr("max", data.quantity);
                    }
                }
            },
        });
    }
}

// Chat with Seller Modal JS || Start
$("#contact_with_seller_form").on("submit", function (e) {
    e.preventDefault();
    let messages_form = $(this);
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });

    $.ajax({
        type: "post",
        url: messages_form.attr("action"),
        data: messages_form.serialize(),
        success: function (respons) {
            toastr.success(
                $("#contact_with_seller_form").data("success-message"),
                {
                    CloseButton: true,
                    ProgressBar: true,
                }
            );
            $("#contact_with_seller_form").trigger("reset");
            $("#contact_sellerModal").modal("hide");
        },
    });
});
// Chat with Seller Modal JS || End

// Product Review - View more button action
var load_review_count = 2;
function load_review(productid) {
    let url_load_review = $(".see-more-details-review").data("routename");
    let onerror = $(".see-more-details-review").data("onerror");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "post",
        url: url_load_review,
        data: {
            product_id: productid,
            offset: load_review_count,
        },
        success: function (data) {
            $("#product-review-list").append(data.productReview);
            if (data.not_empty == 0 && load_review_count > 2) {
                toastr.info(onerror, {
                    CloseButton: true,
                    ProgressBar: true,
                });
            }

            if (data.checkReviews == 0) {
                if (load_review_count != 1) {
                    $(".see-more-details-review").removeClass("view_text");
                    $(".see-more-details-review").html(
                        $(".see-more-details-review").data("afterextend")
                    );
                    $(".see-more-details-review").addClass("view_checked");
                } else {
                    $(".see-more-details-review").html(
                        $(".see-more-details-review").data("seemore")
                    );
                }
                $(".see-more-details-review").removeAttr("onclick", true);
                $(".see-more-details-review").attr("onclick", "seemore()");
            }
            $(".lightbox_custom")
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();
                    new lightbox(this);
                });
        },
    });
    load_review_count++;
}

function seemore() {
    let productid = $(".see-more-details-review").data("productid");
    $(".see-more-details-review")
        .parent()
        .siblings(".details-content-wrap")
        .toggleClass("custom-height");
    if ($(".see-more-details-review").hasClass("view_checked")) {
        $(".see-more-details-review")
            .parent()
            .siblings(".details-content-wrap")
            .hasClass("custom-height")
            ? $(".see-more-details-review").html(
                  $(".see-more-details-review").data("seemore")
              )
            : $(".see-more-details-review").html(
                  $(".see-more-details-review").data("afterextend")
              );
    }

    if (!$(".see-more-details-review").hasClass("view_checked")) {
        $(".see-more-details-review").attr(
            `onclick`,
            `load_review('${productid}')`
        );
    }
}

// Product View Page JS || Start
function filter(value, text) {
    $(".product_view_sort_by ul li").removeClass("selected");
    $(".sort_by-" + value).addClass("selected");
    $(".product_view_sort_by button").html(text);
    sortByfilterBy(value);
}

function sortByfilterBy(value = null, ratings = null) {
    let sort_by_value;
    if (value == null) {
        sort_by_value = $("#sort_by_list li.selected").first().data("value");
    } else {
        sort_by_value = value;
    }

    $.get({
        url: $("#filter_url").data("url"),
        data: {
            id: $("#data_id").val(),
            name: $("#data_name").val(),
            data_from: $("#data_from").val(),
            min_price: $("#price_rangeMin").val(),
            max_price: $("#price_rangeMax").val(),
            sort_by: sort_by_value,
            ratings: ratings,
        },
        dataType: "json",
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            $("#ajax-products-view").html(response.view);
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

function filterByRating(ratings = null) {
    $.get({
        url: $("#filter_url").data("url"),
        data: {
            id: $("#data_id").val(),
            name: $("#data_name").val(),
            data_from: $("#data_from").val(),
            ratings: ratings,
        },
        dataType: "json",
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            $("#ajax-products-view").html(response.view);
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

$(".product-view-option input[name=product_view]").on("change", function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#product_view_style_url").data("url"),
        data: {
            value: $(this).val(),
        },
        success: function (response) {
            console.log(response.message);
        },
    });
});

$("#max_price, #min_price").on("keyup", function () {
    let filter_rangeOne = $('input[name="rangeOne"]'),
        filter_rangeTwo = $('input[name="rangeTwo"]'),
        inclRange = $(".incl-range");
    $("#price_rangeMin").val($("#min_price").val());
    $("#price_rangeMax").val($("#max_price").val());
    $("#data_min_price").val($("#min_price").val());
    $("#data_max_price").val($("#max_price").val());
    if (parseInt(filter_rangeOne.val()) > parseInt(filter_rangeTwo.val())) {
        inclRange.css({
            "inline-size":
                ((filter_rangeOne.val() - filter_rangeTwo.val()) /
                    $("#price_rangeMax").attr("max")) *
                    100 +
                "%",
            "inset-inline-start":
                (filter_rangeTwo.val() / $("#price_rangeMax").attr("max")) *
                    100 +
                "%",
        });
    } else {
        inclRange.css({
            "inline-size":
                ((filter_rangeTwo.val() - filter_rangeOne.val()) /
                    $("#price_rangeMax").attr("max")) *
                    100 +
                "%",
            "inset-inline-start":
                (filter_rangeOne.val() / $("#price_rangeMax").attr("max")) *
                    100 +
                "%",
        });
    }
});

$(".custom_common_nav")
    .find(".has-sub-item div span")
    .on("click", function (event) {
        event.preventDefault();
        $(this).parent().parent(".has-sub-item").toggleClass("sub-menu-opened");
        if ($(this).parent().siblings("ul").hasClass("open")) {
            $(this).parent().siblings("ul").removeClass("open").slideUp("200");
            $(this).removeClass("rotateicon");
        } else {
            $(this).parent().siblings("ul").addClass("open").slideDown("200");
            $(this).addClass("rotateicon");
        }
    });

$(".btn_products_aside_categories").on("click", function () {
    $(".products_aside_categories").css("overflow", "auto");
    $(".btn_products_aside_categories").hide();
});
$(".btn_products_aside_brands").on("click", function () {
    $(".products_aside_brands").css("overflow", "auto");
    $(".btn_products_aside_brands").hide();
});
// Product View Page JS || End

/* wishlist remove by product id */
function removeWishlist(product_id, url) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "POST",
        data: {
            id: product_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            Swal.fire({
                type: "success",
                title: "WishList",
                text: data.success,
            });
            $(".countWishlist").html(data.count);
            $("#set-wish-list").html(data.wishlist);
            $(".tooltip").html("");
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

function order_again(order_id) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#order_again_url").data("url"),
        data: {
            order_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            if (response.status === 1) {
                updateNavCart();
                toastr.success(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 3000, // duration
                });
                $("#quickViewModal").modal("hide");
                return false;
            } else if (response.status === 0) {
                toastr.warning(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 2000, // duration
                });
                return false;
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

// Shop Details Page JS || Start
function shopFollowAction(shop_id) {
    let status = $(".follow_button").data("status");
    if (status == 1) {
        Swal.fire({
            title: $(".follow_button").data("titletext"),
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: $(".follow_button").data("titletext2"),
        }).then((result) => {
            if (result.isConfirmed) {
                shopFollow(shop_id);
            }
        });
    } else {
        shopFollow(shop_id);
    }
}

function shopFollow(shop_id) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: $("#shop_follow_url").data("url"),
        method: "POST",
        data: {
            shop_id: shop_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.value == 1) {
                toastr.success(data.message);
                $(".follower_count").html(data.followers);
                $(".follow_button").html(data.text);
                $(".follow_button").data("status", "1");
            } else if (data.value == 2) {
                toastr.success(data.message);
                $(".follower_count").html(data.followers);
                $(".follow_button").html(data.text);
                $(".follow_button").data("status", "0");
            } else {
                toastr.error(data.message);
                $("#loginModal").modal("show");
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}
// Shop Details Page JS || End

$(".lightbox_custom").on("click", function (e) {
    e.preventDefault();
    new lightbox(this);
});

//coupon copy
function coupon_copy(coupon) {
    $("<textarea/>").appendTo("body").val(coupon).select().each(function () {
        document.execCommand('copy');
    }).remove();

    toastr.success('Successfully coupon copied');
}
