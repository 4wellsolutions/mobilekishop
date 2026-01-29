import './bootstrap';
import echo from './echo';

// Initialize global state access
const config = window.MKS_STATE || {};

$(document).ready(function () {
    // 1. Initialize Echo (Lazy Loading)
    echo.init({
        throttle: 250,
        unload: false
    });

    // 2. Sign with Google handler
    $('#sign-with-google').on('click', function (e) {
        e.preventDefault();
        const newHref = $(this).data('href');
        if (newHref) {
            window.location.href = newHref;
        }
    });

    // 3. Mobile Menu logic
    if (window.innerWidth < 768) {
        const $sidebar = $('.sidebar');
        if ($sidebar.length) {
            $('.mobileMenu').append($sidebar.html()).removeClass("d-none");
        }
    }

    // 4. Search bar toggle (mobile)
    $(".search-icon").on('click', function () {
        const $this = $(this);
        const id = $this.data("id");
        if (id == 0) {
            $this.data("id", 1);
            $(".search-bar").show();
        } else {
            $this.data("id", 0);
            $(".search-bar").hide();
        }
    });

    // 5. AJAX Login Form
    const $loginForm = $("#loginForm");
    if ($loginForm.length) {
        $loginForm.on('submit', function (e) {
            e.preventDefault();
            const $btn = $(".login_button");
            const originalHtml = $btn.html();
            $btn.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');

            const formData = $loginForm.serialize();
            const urlLogin = $loginForm.data("url") || (config.routes && config.routes.login);
            const $errors = $('.validation-errors');
            $errors.html("");

            $.ajax({
                url: urlLogin,
                type: 'POST',
                data: formData,
                success: function (data) {
                    $btn.html(originalHtml);
                    if (data.auth) {
                        location.reload();
                    } else {
                        $.each(data.errors, function (key, value) {
                            $errors.append('<div class="alert alert-danger py-2 rounded-0">' + value + '</div>');
                        });
                    }
                },
                error: function () {
                    $btn.html(originalHtml);
                    $errors.append('<div class="alert alert-danger py-2 rounded-0">Contact Admin.</div>');
                }
            });
        });
    }

    // 6. AJAX Register Form
    const $registerForm = $("#registerForm");
    if ($registerForm.length) {
        $registerForm.on('submit', function (e) {
            e.preventDefault();
            const $btn = $(".register_button");
            const originalHtml = $btn.html();
            $btn.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>').attr("disabled", true);

            const formData = $registerForm.serialize();
            const urlRegister = $registerForm.data("url") || (config.routes && config.routes.register);
            const $errors = $('.validation-errors');
            $errors.html("");

            $.ajax({
                url: urlRegister,
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        $.each(data.errors, function (key, value) {
                            $errors.append('<div class="alert alert-danger py-2 rounded-0">' + value + '</div>');
                        });
                    }
                    $btn.html(originalHtml).attr("disabled", false);
                },
                error: function () {
                    $btn.html(originalHtml).attr("disabled", false);
                    $errors.append('<div class="alert alert-danger py-2 rounded-0">Contact Admin.</div>');
                }
            });
        });
    }

    // 7. AdSense Lazy Loader
    function loadAds() {
        if (document.getElementById('adsense-script')) return;
        const script = document.createElement('script');
        script.id = 'adsense-script';
        script.async = true;
        script.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9435537056478331";
        script.setAttribute('crossorigin', 'anonymous');
        document.head.appendChild(script);
        window.removeEventListener('scroll', onScroll);
    }

    function onScroll() {
        const scrollPos = window.scrollY || window.pageYOffset;
        const threshold = window.innerHeight / 2;
        if (scrollPos >= threshold) {
            loadAds();
        }
    }
    window.addEventListener('scroll', onScroll);

    // 13. Amazon Button / External Click Tracking
    $('.amazonButton').on('click', function () {
        const product_slug = $(this).data('product-slug');
        if (!product_slug) return;
        $.ajax({
            url: config.routes && config.routes.storeUserInfo ? config.routes.storeUserInfo : '/store-user-info',
            type: "POST",
            data: {
                _token: config.csrfToken,
                "product_slug": product_slug,
                "url": window.location.href,
            }
        });
    });

    // 14. Comparison Tab Handler
    $("#nav-compare-tab").on('click', function () {
        const target = $(this).data("href");
        if (target) window.location.replace(target);
    });

    // 11. Product Review / Star Rating Logic
    const isLoggedIn = config.isLoggedIn || false;
    $('.stars').on('click', function () {
        if (!isLoggedIn) {
            const $loginModal = $('#loginModal');
            if ($loginModal.length) $loginModal.modal('show');
        } else {
            const val = $(this).data('value') || $(this).attr('id');
            $('#stars').val(val);
            $('.stars').attr('src', config.baseUrl + "/images/icons/star.png");
            for (let i = 1; i <= val; i++) {
                $('.star-' + i + ', #star-' + i).attr('src', config.baseUrl + "/images/icons/star-fill.png");
            }
            $('#stars-error').hide();
            $(".submitButton").attr("disabled", false);
        }
    });

    const $reviewForm = $('#reviewForm');
    if ($reviewForm.length) {
        $reviewForm.on('submit', function (e) {
            e.preventDefault();
            const starsValue = $('#stars').val();
            const reviewText = $('#review').val();
            const product_id = $('#product_id').val();
            let isValid = true;

            if (!starsValue) {
                $('#stars-error').show();
                isValid = false;
            }
            if (!reviewText) {
                $('#review-error').show();
                $('#reviewTextarea').addClass('border-danger');
                isValid = false;
            } else {
                $('#review-error').hide();
                $('#reviewTextarea').removeClass('border-danger');
            }

            if (!isValid) return;

            const $btn = $(".submitReview");
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status"></span> Submitting...').prop('disabled', true);

            $.ajax({
                url: config.routes && config.routes.reviewPost ? config.routes.reviewPost : '/review/post',
                type: 'POST',
                data: {
                    _token: config.csrfToken,
                    stars: starsValue,
                    review: reviewText,
                    product_id: product_id
                },
                success: function (response) {
                    $('#stars').val('');
                    $('#review').val('');
                    $('.stars').attr('src', config.baseUrl + "/images/icons/star.png");
                    $btn.html(originalHtml).prop('disabled', false);
                    if (response.success) {
                        showToast('Success', response.message, 'bg-success text-white');
                    } else if (response.errors) {
                        let errors = [];
                        $.each(response.errors, function (k, v) { errors.push(v[0]); });
                        showToast('Error', errors.join("\n"), 'bg-danger text-white');
                    }
                },
                error: function (xhr) {
                    $btn.html(originalHtml).prop('disabled', false);
                    const errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    if (errors) {
                        let messages = [];
                        $.each(errors, function (k, v) { messages.push(v[0]); });
                        showToast('Error', messages.join("\n"), 'bg-danger text-white');
                    } else {
                        showToast('Error', 'Contact Admin.', 'bg-danger text-white');
                    }
                }
            });
        });
    }

    // 12. Toast Utility
    window.showToast = function (title, message, classes) {
        const $container = $('#toastContainer');
        if (!$container.length) return;
        $container.html('');
        const toastHtml = `
            <div class="toast align-items-center ${classes} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
        $container.append(toastHtml);
        const $toastElem = $container.find('.toast');
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            new bootstrap.Toast($toastElem[0]).show();
        }
    };

    // 9. Search/Category Filter Auto-submit
    $('.select-filter').off('change').on('change', function () {
        $(this).closest('form').submit();
    });

    // 10. Infinite Scroll Logic
    const $productList = $('#productList');
    const $compareList = $('#compareList');
    const $scrollContainer = $productList.length ? $productList : $compareList;

    if ($scrollContainer.length && ($scrollContainer.data('next-page') || $scrollContainer.data('next-page') === 2)) {
        let isLoading = false;
        let loadAfterProductNumber = 18;
        const currentUrl = config.currentUrl || window.location.href;

        const loadData = () => {
            if (isLoading) return;

            const nextPage = $scrollContainer.data('next-page');
            if (nextPage === 'done') return;

            const separator = currentUrl.includes('?') ? '&' : '?';
            const urlWithPageParam = currentUrl + separator + "page=" + nextPage;

            isLoading = true;
            $('#loadingSpinner').show();

            $.ajax({
                url: urlWithPageParam,
                type: 'GET',
                success: function (response) {
                    // Handle JSON response (upcoming page) vs HTML response (brand/category pages)
                    let htmlContent = "";
                    let isDone = false;

                    if (typeof response === 'object') {
                        if (response.success === false) {
                            isDone = true;
                        } else {
                            htmlContent = response;
                        }
                    } else {
                        if (response.trim() === "") {
                            isDone = true;
                        } else {
                            htmlContent = response;
                        }
                    }

                    if (isDone) {
                        $scrollContainer.data('next-page', 'done');
                        $(window).off('scroll', checkScrollPosition);
                        $('#loadingSpinner').hide();
                        return;
                    }

                    $scrollContainer.append(htmlContent);
                    $scrollContainer.data('next-page', parseInt(nextPage) + 1);
                    isLoading = false;
                    $('#loadingSpinner').hide();
                    loadAfterProductNumber += 18;

                    if (typeof echo !== 'undefined') {
                        echo.render();
                    }
                },
                error: function () {
                    isLoading = false;
                    $('#loadingSpinner').hide();
                }
            });
        };

        const checkScrollPosition = () => {
            const $triggerItem = $('.mobileImage, .compareImage').eq(loadAfterProductNumber - 1);
            if ($triggerItem.length) {
                const topOfTriggerItem = $triggerItem.offset().top;
                const bottomOfScreen = $(window).scrollTop() + $(window).height();
                if (bottomOfScreen > topOfTriggerItem && !isLoading) {
                    loadData();
                }
            } else if ($scrollContainer.data('next-page') !== 'done') {
                // Fallback for pages without clear product images (informational)
                if ($(window).scrollTop() + $(window).height() >= $(document).height() * 0.95) {
                    loadData();
                }
            }
        };

        $(window).on('scroll', checkScrollPosition);
    }

    // 8. Installment Calculator Logic
    const $brandSelect = $('#brand_id');
    if ($brandSelect.length && typeof Choices !== 'undefined') {
        let productChoices;

        new Choices($brandSelect[0], {
            searchResultLimit: 5,
            searchEnabled: true,
            searchChoices: true,
            removeItemButton: true,
            shouldSort: true,
        });

        $brandSelect.on('change', function () {
            $(".modelDiv").addClass("d-none");
            if (productChoices) {
                productChoices.destroy();
            }

            const brandId = $(this).val();
            const url = config.routes && config.routes.getProductsByBrand ? config.routes.getProductsByBrand : '/get-products-by-brand'; // Fallback or add to MKS_STATE

            $.ajax({
                url: url,
                type: 'GET',
                data: { brand_id: brandId },
                dataType: 'json',
                success: function (response) {
                    $(".modelDiv").removeClass("d-none").fadeIn();
                    const $productSelect = $('#product_id');
                    $productSelect.empty();
                    $productSelect.append($('<option>', { value: '', text: 'Select Model' }));

                    if (response.products.length > 0) {
                        $.each(response.products, function (index, product) {
                            $productSelect.append($('<option>', {
                                value: product.id,
                                price: product.price_in_pkr,
                                text: product.name
                            }));
                        });
                    }

                    productChoices = new Choices('#product_id', {
                        searchResultLimit: 5,
                        searchEnabled: true,
                        searchChoices: true,
                        removeItemButton: true,
                        shouldSort: true,
                    });
                }
            });
        });

        $('#installmentsForm').on('submit', function (e) {
            e.preventDefault();
            const $results = $(".results");
            $results.html("");
            const $submitBtn = $('.submitBtn');
            const originalText = $submitBtn.html();
            $submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').attr('disabled', true);

            const url = config.routes && config.routes.installmentPlanPost ? config.routes.installmentPlanPost : '/mobile-installment-calculator';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success === false) {
                        let errorHtml = '<ul>';
                        $.each(response.errors, function (i, err) {
                            errorHtml += '<li>' + err + '</li>';
                        });
                        errorHtml += '</ul>';
                        $results.html(errorHtml);
                    } else {
                        $results.html(response);
                    }
                    $submitBtn.html(originalText).removeAttr('disabled');
                }
            });
        });
    }

    // 15. PTA Calculator Logic
    const $brandIdPta = $('#brand_id');
    const $ptaForm = $('#ptaForm');
    if ($brandIdPta.length && $ptaForm.length) {
        if ($.fn.select2) {
            $('#brand_id').select2();
            $('#product_id').select2();
        }
        $brandIdPta.on('change', function () {
            const brandId = $(this).val();
            $(".taxTable").fadeOut("fast");
            $("#taxOnPassport").text("");
            $("#taxOnCNIC").text("");

            const url = config.routes && config.routes.getProductsByBrandPta ? config.routes.getProductsByBrandPta : '/get-products-by-brand-pta';

            $.ajax({
                url: url,
                type: 'GET',
                data: { brand_id: brandId },
                dataType: 'json',
                success: function (response) {
                    const $productSelect = $('#product_id');
                    $productSelect.empty();
                    $productSelect.append($('<option>', { value: '', text: 'Select Model' }));
                    if (response.products && response.products.length > 0) {
                        $.each(response.products, function (index, product) {
                            $productSelect.append($('<option>', {
                                value: product.id,
                                text: product.name
                            }));
                        });
                    }
                }
            });
        });

        $ptaForm.on('submit', function (e) {
            e.preventDefault();
            const url = config.routes && config.routes.getPta ? config.routes.getPta : '/get-pta';
            $.ajax({
                url: url,
                type: 'GET',
                data: { brand_id: $("#brand_id").val(), product_id: $("#product_id").val() },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $(".taxTable").fadeIn("fast");
                        $("#taxOnPassport").text(response.tax.tax_on_passport);
                        $("#taxOnCNIC").text(response.tax.tax_on_cnic);
                    }
                }
            });
        });
    }

    // 16. Edit Review / Wishlist handlers
    $(".editReview").on('click', function () {
        const id = $(this).data("id");
        const stars = $(this).data("star");
        const review = $(".comment-text-" + id).text();
        $("#review_id").val(id);
        $("#stars").val(stars);
        $(".star-" + stars).addClass("active");
        $("#review").html(review);
    });

    $(".submitButton").on('click', function () {
        $("#reviewForm").submit();
    });

    // 17. Account Password Toggle
    $("#change-pass-checkbox").on('change', function () {
        if (this.checked) {
            $("#account-chage-pass").fadeIn().removeClass("d-none");
            $("#password").attr("required", true);
            $("#password_confirmation").attr("required", true);
        } else {
            $("#account-chage-pass").fadeOut();
            $("#password").attr("required", false);
            $("#password_confirmation").attr("required", false);
        }
    });

    // 18. Comparison Search (Bloodhound)
    if ($(".search").length > 0) {
        var routeCompare = MKS_STATE.routes.autocomplete;
        var categoryId = $("#category_id").val() || 1;

        var engine = new Bloodhound({
            remote: {
                url: routeCompare + "?query=%QUERY%&category_id=" + categoryId,
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.whitespace('query'),
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        $(".search").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: engine.ttAdapter(),
            limit: 11,
            name: 'usersList',
            displayKey: 'name',
            templates: {
                empty: '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>',
                header: '<div class="list-group search-results-dropdown">',
                suggestion: function (data) {
                    return '<div class="row bg-white border-bottom"><div class="col-4"><img src="' + data.thumbnail + '" class="img-fluid searchImage my-1"></div><div class="col-8 text-uppercase text-start" style="font-weight:600;">' + data.name + '</div></div>'
                }
            }
        });

        $('.search').on('typeahead:select', function (ev, suggestion) {
            $("#input-" + $(this).attr("id")).val(suggestion.slug);
            var base_url = MKS_STATE.baseUrl + "/compare/";
            var param1 = $("#input-search1").val() || "";
            var param2 = $("#input-search2").val() ? "-vs-" + $("#input-search2").val() : "";
            var param3 = $("#input-search3").val() ? "-vs-" + $("#input-search3").val() : "";
            if (param1) {
                window.location.replace(base_url + param1 + param2 + param3);
            }
        });
    }
});
