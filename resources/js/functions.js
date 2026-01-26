'use strict';

// Cookie law banner
document.querySelector('#cookie-banner-dismiss') && document.querySelector('#cookie-banner-dismiss').addEventListener('click', function () {
    setCookie('cookie_law', 1, new Date().getTime() + (10 * 365 * 24 * 60 * 60 * 1000), '/');
    document.querySelector('#cookie-banner').classList.add('d-none');
});

// Dark mode
document.querySelector('#dark-mode') && document.querySelector('#dark-mode').addEventListener('click', function (e) {
    e.preventDefault();

    // Update the sources
    document.querySelectorAll('[data-theme-target]').forEach(function (element) {
        element.setAttribute(element.dataset.themeTarget, document.querySelector('html').classList.contains('dark') ? element.dataset.themeLight : element.dataset.themeDark);
    });

    // Update the text
    this.querySelector('span').textContent = document.querySelector('html').classList.contains('dark') ? this.querySelector('span').dataset.textLight : this.querySelector('span').dataset.textDark;

    // Update the dark mode cookie
    setCookie('dark_mode', (document.querySelector('html').classList.contains('dark') ? 0 : 1), new Date().getTime() + (10 * 365 * 24 * 60 * 60 * 1000), '/');

    // Update the CSS class
    if (document.querySelector('html').classList.contains('dark')) {
        document.querySelector('html').classList.remove('dark');
    } else {
        document.querySelector('html').classList.add('dark');
    }
});

// Pricing plans
document.querySelector('#plan-month') && document.querySelector('#plan-month').addEventListener("click", function () {
    document.querySelectorAll('.plan-month').forEach(element => element.classList.add('d-block'));
    document.querySelectorAll('.plan-year').forEach(element => element.classList.remove('d-block'));
});

document.querySelector('#plan-year') && document.querySelector('#plan-year').addEventListener("click", function () {
    document.querySelectorAll('.plan-year').forEach(element => element.classList.add('d-block'));
    document.querySelectorAll('.plan-month').forEach(element => element.classList.remove('d-block'));
});

let updateSummary = (type) => {
    if (type == 'month') {
        document.querySelectorAll('.checkout-month').forEach(function (element) {
            element.classList.add('d-inline-block');
        });

        document.querySelectorAll('.checkout-year').forEach(function (element) {
            element.classList.remove('d-inline-block');
        });
    } else {
        document.querySelectorAll('.checkout-month').forEach(function (element) {
            element.classList.remove('d-inline-block');
        });

        document.querySelectorAll('.checkout-year').forEach(function (element) {
            element.classList.add('d-inline-block');
        });
    }
};

let updateBillingType = (value) => {
    // Show the offline instructions
    if (value == 'bank') {
        document.querySelector('#bank-instructions').classList.remove('d-none');
        document.querySelector('#bank-instructions').classList.add('d-block');
    }
    // Hide the offline instructions
    else {
        if (document.querySelector('#bank-instructions')) {
            document.querySelector('#bank-instructions').classList.add('d-none');
            document.querySelector('#bank-instructions').classList.remove('d-block');
        }
    }

    if (value == 'cryptocom' || value == 'coinbase' || value == 'bank') {
        document.querySelectorAll('.checkout-subscription').forEach(function (element) {
            element.classList.remove('d-block');
        });

        document.querySelectorAll('.checkout-subscription').forEach(function (element) {
            element.classList.add('d-none');
        });

        document.querySelectorAll('.checkout-one-time').forEach(function (element) {
            element.classList.add('d-block');
        });

        document.querySelectorAll('.checkout-one-time').forEach(function (element) {
            element.classList.remove('d-none');
        });
    } else {
        document.querySelectorAll('.checkout-subscription').forEach(function (element) {
            element.classList.remove('d-none');
        });

        document.querySelectorAll('.checkout-subscription').forEach(function (element) {
            element.classList.add('d-block');
        });

        document.querySelectorAll('.checkout-one-time').forEach(function (element) {
            element.classList.add('d-none');
        });

        document.querySelectorAll('.checkout-one-time').forEach(function (element) {
            element.classList.remove('d-block');
        });
    }
}

// Payment form
if (document.querySelector('#form-payment')) {
    let url = new URL(window.location.href);

    document.querySelectorAll('[name="interval"]').forEach(function (element) {
        if (element.checked) {
            updateSummary(element.value);
        }

        // Listen to interval changes
        element.addEventListener('change', function () {
            // Update the URL address
            url.searchParams.set('interval', element.value);

            history.pushState(null, null, url.href);

            updateSummary(element.value);
        });
    });

    document.querySelectorAll('[name="payment_processor"]').forEach(function (element) {
        if (element.checked) {
            updateBillingType(element.value);
        }

        // Listen to payment processor changes
        element.addEventListener('change', function () {
            // Update the URL address
            url.searchParams.set('payment', element.value);

            history.pushState(null, null, url.href);

            updateBillingType(element.value);
        });
    });

    // If the Add a coupon button is clicked
    document.querySelector('#coupon') && document.querySelector('#coupon').addEventListener('click', function (e) {
        e.preventDefault();

        // Hide the link
        this.classList.add('d-none');

        // Show the coupon input
        document.querySelector('#coupon-input').classList.remove('d-none');

        // Enable the coupon input
        document.querySelector('input[name="coupon"]').removeAttribute('disabled');
    });

    // If the Cancel coupon button is clicked
    document.querySelector('#coupon-cancel') && document.querySelector('#coupon-cancel').addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector('#coupon').classList.remove('d-none');

        // Hide the coupon input
        document.querySelector('#coupon-input').classList.add('d-none');

        // Disable the coupon input
        document.querySelector('input[name="coupon"]').setAttribute('disabled', 'disabled');
    });

    // If the country value changes
    document.querySelector('#i-country').addEventListener('change', function () {
        // Remove the submit button
        document.querySelector('#form-payment').submit.remove();

        // Submit the form
        document.querySelector('#form-payment').submit();
    });
}

// Coupon form
if (document.querySelector('#form-coupon')) {
    document.querySelector('#i-type').addEventListener('change', function () {
        if (document.querySelector('#i-type').value == 1) {
            document.querySelector('#form-group-redeemable').classList.remove('d-none');
            document.querySelector('#form-group-discount').classList.add('d-none');
            document.querySelector('#i-percentage').setAttribute('disabled', 'disabled');
        } else {
            document.querySelector('#form-group-redeemable').classList.add('d-none');
            document.querySelector('#form-group-discount').classList.remove('d-none');
            document.querySelector('#i-percentage').removeAttribute('disabled');
        }
    });
}

// Table filters
document.querySelector('#search-filters') && document.querySelector('#search-filters').addEventListener('click', function (e) {
    e.stopPropagation();
});

// Whitelist SVG tags
jQuery.fn.tooltip.Constructor.Default.whiteList.svg = ['xmlns', 'class', 'viewbox'];
jQuery.fn.tooltip.Constructor.Default.whiteList.path = ['d'];

// Clipboard
new ClipboardJS('[data-clipboard="true"]');

document.querySelectorAll('[data-clipboard-copy]').forEach(function (element) {
    element.addEventListener('click', function (e) {
        e.preventDefault();

        try {
            let value = this.dataset.clipboardCopy;
            let tempInput = document.createElement('textarea');

            document.body.append(tempInput);

            // Set the input's value to the url to be copied
            tempInput.value = value;

            // Select the input's value to be copied
            tempInput.select();

            // Copy the url
            document.execCommand("copy");

            // Remove the temporary input
            tempInput.remove();
        } catch (e) {}
    });
});

// Tooltip
jQuery('[data-tooltip="true"]').tooltip({animation: true, trigger: 'hover', boundary: 'window'});

// Copy tooltip
jQuery('[data-tooltip-copy="true"]').tooltip({animation: true});

document.querySelectorAll('[data-tooltip-copy="true"]').forEach(function (element) {
    element.addEventListener('click', function (e) {
        // Update the tooltip
        jQuery(this).tooltip('hide').attr('data-original-title', this.dataset.textCopied).tooltip('show');
    });

    element.addEventListener('mouseleave', function () {
        this.setAttribute('data-original-title', this.dataset.textCopy);
    });
});

// Slide menu
document.querySelectorAll('.slide-menu-toggle').forEach(function (element) {
    element.addEventListener('click', function () {
        document.querySelector('#slide-menu').classList.toggle('active');
    });
});

// Toggle password visibility
document.querySelectorAll('[data-password]').forEach(function (element) {
    element.addEventListener('click', function (e) {
        let passwordInput = document.querySelector('#' + this.dataset.password);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            jQuery(this).tooltip('hide').attr('data-original-title', this.dataset.passwordHide).tooltip('show');
        } else {
            passwordInput.type = 'password';
            jQuery(this).tooltip('hide').attr('data-original-title', this.dataset.passwordShow).tooltip('show');
        }
    });
});

/**
 * Handle the confirmation modal event.
 *
 * @param element
 */
let confirmationModalEvent = (element) => {
    element.addEventListener('click', function () {
        // Unset attributes if previously set
        document.querySelector('#modal-button').removeAttribute('name');
        document.querySelector('#modal-button').removeAttribute('value');

        // Set the attributes
        if (this.dataset.buttonName) {
            document.querySelector('#modal-button').setAttribute('name', this.dataset.buttonName);
        }
        if (this.dataset.buttonValue) {
            document.querySelector('#modal-button').setAttribute('value', this.dataset.buttonValue);
        }
        document.querySelector('#modal-label').textContent = this.dataset.title
        document.querySelector('#modal-button').textContent = this.dataset.title;
        document.querySelector('#modal-button').setAttribute('class', this.dataset.button);
        document.querySelector('#modal-text').textContent = this.dataset.text;
        document.querySelector('#modal-sub-text').textContent = this.dataset.subText;
        document.querySelector('#modal form').setAttribute('action', this.dataset.action);
    });
}

document.querySelectorAll('[data-target="#modal"]').forEach(function (element) {
    confirmationModalEvent(element);
});

// Button loader
document.querySelectorAll('[data-button-loader]').forEach(function (element) {
    element.addEventListener('click', function (e) {
        // Stop the button from being re-submitted while loading
        if (this.classList.contains('disabled')) {
            e.preventDefault();
        }
        this.classList.add('disabled');
        this.querySelector('.spinner-border').classList.remove('d-none');
        this.querySelector('.spinner-text').classList.add('invisible');
    });
});

/**
 * Restore a disabled button.
 */
let restoreDisabledButton = (element) => {
    element.classList.remove('disabled');
    element.querySelector('.spinner-border').classList.add('d-none');
    element.querySelector('.spinner-text').classList.remove('invisible');
}

// Privacy selector
document.querySelectorAll('input[name="privacy"]').forEach(function (element) {
    element.addEventListener('click', function () {
        if (this.checked && this.value == 2) {
            document.querySelector('#input-password').classList.remove('d-none');
            document.querySelector('#input-password').classList.add('d-block')
        } else {
            document.querySelector('#input-password').classList.add('d-none');
            document.querySelector('#input-password').classList.remove('d-block')
        }
    });
});

// Toggle sitemap
document.querySelector('#toggle-sitemap') && document.querySelector('#toggle-sitemap').addEventListener('click', function (e) {
    document.querySelector('#i-url').setAttribute('placeholder', document.querySelector('#i-url').dataset.placeholderSitemap);
});

// Toggle webpage
document.querySelector('#toggle-webpage') && document.querySelector('#toggle-webpage').addEventListener('click', function (e) {
    document.querySelector('#i-url').setAttribute('placeholder', document.querySelector('#i-url').dataset.placeholderWebpage);
});

// Tools filters
if (document.querySelector('#tool-filters')) {
    let filterTools = (search, category) => {
        let hideCategoryLabels = () => {
            document.querySelectorAll('[data-category-label]').forEach(function (element) {
                element.classList.add('d-none');
            });
        }

        let hideTools = () => {
            document.querySelectorAll('[data-tool]').forEach(function (element) {
                element.classList.add('d-none');
            });
        }

        let showCategoryLabels = () => {
            document.querySelectorAll('[data-category-label]').forEach(function (element) {
                element.classList.remove('d-none');
            });
        }

        let showTools = () => {
            document.querySelectorAll('[data-tool]').forEach(function (element) {
                element.classList.remove('d-none');
            });
        }

        let showCategoryLabel = (name) => {
            document.querySelector('[data-category-label="' + name + '"]').classList.remove('d-none');
        }

        let showTool = (name) => {
            document.querySelector('[data-tool="' + name + '"]').classList.remove('d-none');
        }

        let tools = category ? document.querySelectorAll('[data-tool-category="' + category + '"]') : document.querySelectorAll('[data-tool]');

        if (!search && !category) {
            showCategoryLabels();
            showTools();
        } else {
            hideCategoryLabels();
            hideTools();

            tools.forEach(function (item) {
                if (search) {
                    if (item.dataset.tool.toLowerCase().includes(search.toLowerCase())) {
                        showTool(item.dataset.tool);
                        showCategoryLabel(item.dataset.toolCategory);
                    }
                } else {
                    showTool(item.dataset.tool);
                    showCategoryLabel(item.dataset.toolCategory);
                }
            });
        }
    }

    document.querySelectorAll('[data-filter-category]').forEach(function (button) {
        // Listen for category button click
        button.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('[data-filter-category]').forEach(function (element) {
                // Remove the previous active button state
                element.classList.remove(element.dataset.textColorActive, element.dataset.textColorInactive);
                element.classList.add(element.dataset.textColorInactive);

                // Remove the previous active category
                element.removeAttribute('data-filter-category-active');
                element.setAttribute('href', '#');
            });
            // Set the active button state
            this.classList.remove(this.dataset.textColorInactive);
            this.classList.add(this.dataset.textColorActive);

            // Set the active category
            this.setAttribute('data-filter-category-active', this.dataset.filterCategory);
            this.removeAttribute('href');

            filterTools(document.querySelector('#i-search').value, document.querySelector('[data-filter-category-active]').dataset.filterCategoryActive);
        });
    });

    if (document.querySelector('#form-tools-search')) {
        document.querySelector('#i-search').addEventListener('keyup', function () {
            // If the search input has any value
            if (document.querySelector('#i-search').value.length > 0) {
                filterTools(document.querySelector('#i-search').value, document.querySelector('[data-filter-category-active]').dataset.filterCategoryActive);

                // Show the clear button
                document.querySelector('#clear-button-container').classList.remove('d-none');
                document.querySelector('#i-search').after(document.querySelector('#clear-button-container'));
            } else {
                filterTools('', document.querySelector('[data-filter-category-active]').dataset.filterCategoryActive);

                // Hide the clear button
                document.querySelector('#form-tools-search').append(document.querySelector('#clear-button-container'));
                document.querySelector('#clear-button-container').classList.add('d-none');
            }
        });

        document.querySelector('#b-clear').addEventListener('click', function () {
            // Empty the search input
            document.querySelector('#i-search').value = '';

            // Focus the search input
            document.querySelector('#i-search').focus();

            // Move the clear button outside the search input container
            document.querySelector('#form-tools-search').append(document.querySelector('#clear-button-container'));

            // Hide the clear button
            document.querySelector('#clear-button-container').classList.add('d-none');

            // Show the filtered items
            filterTools('', document.querySelector('[data-filter-category-active]').dataset.filterCategoryActive);
        });
    }
}

// Initialize toasts
jQuery('.toast').toast();

/**
 * Get the value of a given cookie.
 *
 * @param   name
 * @returns {*}
 */
let getCookie = (name) => {
    var name = name + '=';
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');

    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while(c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if(c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
};

/**
 * Set a cookie.
 *
 * @param   name
 * @param   value
 * @param   expire
 * @param   path
 */
let setCookie = (name, value, expire, path) => {
    document.cookie = name + "=" + value + ";expires=" + (new Date(expire).toUTCString()) + ";path=" + path;
};

/**
 * Center the pop-up window
 *
 * @param url
 * @param title
 * @param w
 * @param h
 */
let popupCenter = (url, title, w, h) => {
    // Fixes dual-screen position                         Most browsers      Firefox
    let dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
    let dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

    let width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    let height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    let systemZoom = width / window.screen.availWidth;
    let left = (width - w) / 2 / systemZoom + dualScreenLeft;
    let top = (height - h) / 2 / systemZoom + dualScreenTop;
    let newWindow = window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) newWindow.focus();
};
