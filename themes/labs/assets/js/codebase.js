/*
 *  Document   : codebase.js
 *  Author     : pixelcave
 *  Description: Codebase - UI Framework Custom Functionality
 *
 */

'use strict';

var Codebase = function() {
    var lHtml,
        lBody,
        lPage,
        lSidebar,
        lSidebarScroll,
        lSideOverlay,
        lSideOverlayScroll,
        lHeader,
        lHeaderSearch,
        lHeaderSearchInput,
        lHeaderLoader,
        lMain,
        lFooter,
        windowW;

    // Set helper variables
    var uiInit = function() {
        lHtml              = jQuery('html');
        lBody              = jQuery('body');
        lPage              = jQuery('#page-container');
        lSidebar           = jQuery('#sidebar');
        lSidebarScroll     = jQuery('#sidebar-scroll');
        lSideOverlay       = jQuery('#side-overlay');
        lSideOverlayScroll = jQuery('#side-overlay-scroll');
        lHeader            = jQuery('#page-header');
        lHeaderSearch      = jQuery('#page-header-search');
        lHeaderSearchInput = jQuery('#page-header-search-input');
        lHeaderLoader      = jQuery('#page-header-loader');
        lMain              = jQuery('#main-container');
        lFooter            = jQuery('#page-footer');
    };

    /*
     ********************************************************************************************
     *
     * BASE UI FUNCTIONALITY
     *
     * Functions which handle vital UI functionality such as main navigation and layout
     * They are auto initialized
     *
     *********************************************************************************************
     */

    // Handles sidebar and side overlay custom scrolling functionality
    var uiHandleScroll = function(mode) {
        windowW = getWidth();

        // Init scrolling
        if (mode === 'init') {
            var sScrollTimeout;

            // Unbind events in case they are already binded
            jQuery(window).off('resize.cb.scroll orientationchange.cb.scroll');

            // Bind the events
            jQuery(window).on('resize.cb.scroll orientationchange.cb.scroll', function(){
                clearTimeout(sScrollTimeout);

                sScrollTimeout = setTimeout(function(){
                    uiHandleScroll();
                }, 150);
            }).triggerHandler('resize.cb.scroll');
        } else {
            // If screen width is greater than 991 pixels and .side-scroll is added to #page-container
            if (windowW > 991 && lPage.hasClass('side-scroll')) {
                // Turn scroll lock off (sidebar and side overlay - slimScroll will take care of it)
                jQuery(lSidebar).add(lSideOverlay).scrollLock('disable');

                // If sidebar scrolling does not exist init it..
                if (lSidebarScroll.length && (!lSidebarScroll.parent('.slimScrollDiv').length)) {
                    lSidebarScroll.slimScroll({
                        height: lSidebar.outerHeight(),
                        color: '#cdcdcd',
                        size: '4px',
                        opacity : .9,
                        wheelStep : 15,
                        distance : '0',
                        railVisible: false,
                        railOpacity: 1
                    });

                    // Small hack, so that scrolling works if the mouse is over the scrolling area on load and hasn't moved yet
                    lSidebarScroll.mouseover();
                }
                else { // ..else resize scrolling height
                    lSidebarScroll
                        .add(lSidebarScroll.parent())
                        .css('height', lSidebar.outerHeight());

                    // Small hack, so that scrolling works if the mouse is over the scrolling area on load and hasn't moved yet
                    lSidebarScroll.mouseover();
                }

                // If side overlay scrolling does not exist init it..
                if (lSideOverlayScroll.length && (!lSideOverlayScroll.parent('.slimScrollDiv').length)) {
                    lSideOverlayScroll.slimScroll({
                        height: lSideOverlay.outerHeight(),
                        color: '#cdcdcd',
                        size: '4px',
                        opacity : .9,
                        wheelStep : 15,
                        distance : '0',
                        railVisible: false,
                        railOpacity: 1
                    });
                }
                else { // ..else resize scrolling height
                    lSideOverlayScroll
                        .add(lSideOverlayScroll.parent())
                        .css('height', lSideOverlay.outerHeight());
                }
            } else {
                // Turn scroll lock on (sidebar and side overlay)
                jQuery(lSidebar).add(lSideOverlay).scrollLock('enable');

                // If sidebar scrolling exists destroy it..
                if (lSidebarScroll.length && lSidebarScroll.parent('.slimScrollDiv').length) {
                    lSidebarScroll
                        .slimScroll({destroy: true});
                    lSidebarScroll
                        .attr('style', '');
                }

                // If side overlay scrolling exists destroy it..
                if (lSideOverlayScroll.length && lSideOverlayScroll.parent('.slimScrollDiv').length) {
                    lSideOverlayScroll
                        .slimScroll({destroy: true});
                    lSideOverlayScroll
                        .attr('style', '');
                }
            }
        }
    };

    // Resizes #main-container to fill empty space if exists (pushes footer to the bottom) + Adds transition to sidebar (small fix for IE)
    var uiHandleMain = function() {
        var resizeTimeout;

        // Unbind events in case they are already binded
        jQuery(window).off('resize.cb.main orientationchange.cb.main');

        // If #main-container element exists
        if (lMain.length) {
            jQuery(window).on('resize.cb.main orientationchange.cb.main', function(){
                clearTimeout(resizeTimeout);

                resizeTimeout = setTimeout(function(){
                    var hWindow     = jQuery(window).height();
                    var hHeader     = lHeader.outerHeight() || 0;
                    var hFooter     = lFooter.outerHeight() || 0;

                    // Set #main-container min height accordingly
                    if (lPage.hasClass('page-header-fixed') || lPage.hasClass('page-header-glass')) {
                        lMain.css('min-height', hWindow - hFooter);
                    } else {
                        lMain.css('min-height', hWindow - hHeader - hFooter);
                    }

                    // Show footer's content
                    lFooter.fadeTo(1000, 1);
                }, 150);
            }).triggerHandler('resize.cb.main');
        }

        // Add 'side-trans-enabled' class to #page-container (enables sidebar and side overlay transition on open/close)
        // Fixes IE10, IE11 and Edge bug in which animation was executed on each page load - really annoying!
        lPage.addClass('side-trans-enabled');
    };

    // Handles header related classes
    var uiHandleHeader = function() {
        // Unbind event in case it is already enabled
        jQuery(window).off('scroll.cb.header');

        // If the header is fixed and has the glass style, add the related class on scrolling to add a background color to the header
        if (lPage.hasClass('page-header-glass') && lPage.hasClass('page-header-fixed')) {
            jQuery(window).on('scroll.cb.header', function(){
                if (jQuery(this).scrollTop() > 60) {
                    lPage.addClass('page-header-scroll');
                } else {
                    lPage.removeClass('page-header-scroll');
                }
            }).trigger('scroll.cb.header');
        }
    };

    // Main navigation functionality
    var uiHandleNav = function() {
        // Unbind event in case it is already enabled
        lPage.off('click.cb.menu');

        // When a submenu link is clicked
        lPage.on('click.cb.menu', '[data-toggle="nav-submenu"]', function(e){
            // Get link
            var link = jQuery(this);

            // Get link's parent
            var parentLi = link.parent('li');

            if (parentLi.hasClass('open')) { // If submenu is open, close it..
                parentLi.removeClass('open');
            } else { // .. else if submenu is closed, close all other (same level) submenus first before open it
                link
                    .closest('ul')
                    .children('li')
                    .removeClass('open');

                parentLi
                    .addClass('open');
            }

            // Remove focus from submenu link
            if (lHtml.hasClass('no-focus')) {
                link.blur();
            }

            return false;
        });
    };

    // Material form inputs functionality
    var uiHandleForms = function() {
        jQuery('.form-material.floating > .form-control').each(function(){
            var input  = jQuery(this);
            var parent = input.parent('.form-material');

            setTimeout(function() {
                if (input.val() ) {
                    parent.addClass('open');
                }
            }, 150);

            input.off('change.cb.inputs').on('change.cb.inputs', function(){
                if (input.val()) {
                    parent.addClass('open');
                } else {
                    parent.removeClass('open');
                }
            });
        });
    };

    // Set active color theme functionality
    var uiHandleTheme = function() {
        var themeEl = jQuery('#css-theme');
        var cookies = lPage.hasClass('enable-cookies') ? true : false;

        // If cookies are enabled
        if (cookies) {
            var themeName  = Cookies.get('cbThemeName') || false;

            // Update color theme
            if (themeName) {
                uiHandleThemeChange(themeEl, themeName);
            }

            // Update theme element
            themeEl = jQuery('#css-theme');
        }

        // Set the active color theme link as active
        jQuery('[data-toggle="theme"][data-theme="' + (themeEl.length ? themeEl.attr('href') : 'default') + '"]')
            .parent('li')
            .addClass('active');

        // Unbind event in case it is already enabled
        lPage.off('click.cb.themes');

        // When a color theme link is clicked
        lPage.on('click.cb.themes', '[data-toggle="theme"]', function(){
            var themeName = jQuery(this).data('theme');

            // Set this color theme link as active
            jQuery('[data-toggle="theme"]')
                .parent('li')
                .removeClass('active');

            jQuery('[data-toggle="theme"][data-theme="' + themeName + '"]')
                .parent('li')
                .addClass('active');

            // Update color theme
            uiHandleThemeChange(themeEl, themeName);

            // Update theme element
            themeEl = jQuery('#css-theme');

            // If cookies are enabled, save the new active color theme
            if (cookies) {
                Cookies.set('cbThemeName', themeName, { expires: 7 });
            }
        });
    };

    // Helper function for changing a theme
    var uiHandleThemeChange = function(themeEl, themeName) {
        if (themeName === 'default') {
            if (themeEl.length) {
                themeEl.remove();
            }
        } else {
            if (themeEl.length) {
                themeEl.attr('href', themeName);
            } else {
                jQuery('#css-main')
                    .after('<link rel="stylesheet" id="css-theme" href="' + themeName + '">');
            }
        }
    };

    /*
     ********************************************************************************************
     *
     * API
     *
     * Functions which handle requests for blocks and layout
     *
     *********************************************************************************************
     */

    // Layout API
    var uiApiLayout = function(mode) {
        windowW = getWidth();

        // Mode selection
        switch(mode) {
            case 'init':
                // Unbind event in case it is already enabled
                lPage.off('click.cb.layout');

                // Call layout API on button click
                lPage.on('click.cb.layout', '[data-toggle="layout"]', function(){
                    var el = jQuery(this);

                    uiApiLayout(el.data('action'));

                    if (lHtml.hasClass('no-focus')) {
                        el.blur();
                    }
                });
                break;
            case 'sidebar_pos_toggle':
                lPage.toggleClass('sidebar-r');
                break;
            case 'sidebar_pos_left':
                lPage.removeClass('sidebar-r');
                break;
            case 'sidebar_pos_right':
                lPage.addClass('sidebar-r');
                break;
            case 'sidebar_toggle':
                if (windowW > 991) {
                    lPage.toggleClass('sidebar-o');
                } else {
                    lPage.toggleClass('sidebar-o-xs');
                }
                break;
            case 'sidebar_open':
                if (windowW > 991) {
                    lPage.addClass('sidebar-o');
                } else {
                    lPage.addClass('sidebar-o-xs');
                }
                break;
            case 'sidebar_close':
                if (windowW > 991) {
                    lPage.removeClass('sidebar-o');
                } else {
                    lPage.removeClass('sidebar-o-xs');
                }
                break;
            case 'sidebar_mini_toggle':
                if (windowW > 991) {
                    lPage.toggleClass('sidebar-mini');
                }
                break;
            case 'sidebar_mini_on':
                if (windowW > 991) {
                    lPage.addClass('sidebar-mini');
                }
                break;
            case 'sidebar_mini_off':
                if (windowW > 991) {
                    lPage.removeClass('sidebar-mini');
                }
                break;
            case 'sidebar_style_inverse_toggle':
                lPage.toggleClass('sidebar-inverse');
                break;
            case 'sidebar_style_inverse_on':
                lPage.addClass('sidebar-inverse');
                break;
            case 'sidebar_style_inverse_off':
                lPage.removeClass('sidebar-inverse');
                break;
            case 'side_overlay_toggle':
               if (lPage.hasClass('side-overlay-o')) {
                   uiApiLayout('side_overlay_close');
               } else {
                   uiApiLayout('side_overlay_open');
               }
                break;
            case 'side_overlay_open':
                // When ESCAPE key is hit close the side overlay
                jQuery(document).on('keydown.cb.sideOverlay', function(e){
                    if (e.which === 27) {
                        e.preventDefault();
                        uiApiLayout('side_overlay_close');
                    }
                });

                lPage.addClass('side-overlay-o');
                break;
            case 'side_overlay_close':
                // Unbind ESCAPE key
                jQuery(document).off('keydown.cb.sideOverlay');

                lPage.removeClass('side-overlay-o');
                break;
            case 'side_overlay_hoverable_toggle':
                lPage.toggleClass('side-overlay-hover');
                break;
            case 'side_overlay_hoverable_on':
                lPage.addClass('side-overlay-hover');
                break;
            case 'side_overlay_hoverable_off':
                lPage.removeClass('side-overlay-hover');
                break;
            case 'header_fixed_toggle':
                lPage.toggleClass('page-header-fixed');
                uiHandleHeader();
                uiHandleMain();
                break;
            case 'header_fixed_on':
                lPage.addClass('page-header-fixed');
                uiHandleHeader();
                uiHandleMain();
                break;
            case 'header_fixed_off':
                lPage.removeClass('page-header-fixed');
                uiHandleHeader();
                uiHandleMain();
                break;
            case 'header_style_modern':
                lPage.removeClass('page-header-glass page-header-inverse').addClass('page-header-modern');
                uiHandleHeader();
                uiHandleMain();
                break;
            case 'header_style_classic':
                lPage.removeClass('page-header-glass page-header-modern');
                uiHandleHeader();
                uiHandleMain();
                break;
            case 'header_style_glass':
                lPage.removeClass('page-header-modern').addClass('page-header-glass');
                uiHandleHeader();
                uiHandleMain();
                break;
            case 'header_style_inverse_toggle':
                if (!lPage.hasClass('page-header-modern')) {
                    lPage.toggleClass('page-header-inverse');
                }
                break;
            case 'header_style_inverse_on':
                if (!lPage.hasClass('page-header-modern')) {
                    lPage.addClass('page-header-inverse');
                }
                break;
            case 'header_style_inverse_off':
                if (!lPage.hasClass('page-header-modern')) {
                    lPage.removeClass('page-header-inverse');
                }
                break;
            case 'header_search_on':
                lHeaderSearch.addClass('show');
                lHeaderSearchInput.focus();

                // When ESCAPE key is hit close the search section
                jQuery(document).on('keydown.cb.header.search', function(e){
                    if (e.which === 27) {
                        e.preventDefault();
                        console.log('test');
                        uiApiLayout('header_search_off');
                    }
                });
                break;
            case 'header_search_off':
                lHeaderSearch.removeClass('show');
                lHeaderSearchInput.blur();

                // Unbind ESCAPE key
                jQuery(document).off('keydown.cb.header.search');
                break;
            case 'header_loader_on':
                lHeaderLoader.addClass('show');
                break;
            case 'header_loader_off':
                lHeaderLoader.removeClass('show');
                break;
            case 'side_scroll_toggle':
                lPage.toggleClass('side-scroll');
                uiHandleScroll();
                break;
            case 'side_scroll_on':
                lPage.addClass('side-scroll');
                uiHandleScroll();
                break;
            case 'side_scroll_off':
                lPage.removeClass('side-scroll');
                uiHandleScroll();
                break;
            case 'content_layout_toggle':
                if (lPage.hasClass('main-content-boxed')) {
                    uiApiLayout('content_layout_narrow');
                } else if (lPage.hasClass('main-content-narrow')) {
                    uiApiLayout('content_layout_full_width');
                } else {
                    uiApiLayout('content_layout_boxed');
                }
                break;
            case 'content_layout_boxed':
                lPage.removeClass('main-content-narrow').addClass('main-content-boxed');
                break;
            case 'content_layout_narrow':
                lPage.removeClass('main-content-boxed').addClass('main-content-narrow');
                break;
            case 'content_layout_full_width':
                lPage.removeClass('main-content-boxed main-content-narrow');
            default:
                return false;
        }
    };

    // Blocks API
    var uiApiBlocks = function(block, mode) {
        // Set default icons for fullscreen and content toggle buttons
        var iconFullscreen         = 'si si-size-fullscreen';
        var iconFullscreenActive   = 'si si-size-actual';
        var iconContent            = 'si si-arrow-up';
        var iconContentActive      = 'si si-arrow-down';

        if (mode === 'init') {
            // Auto add the default toggle icons to fullscreen and content toggle buttons
            jQuery('[data-toggle="block-option"][data-action="fullscreen_toggle"]').each(function(){
                var el = jQuery(this);

                el.html('<i class="' + (jQuery(el).closest('.block').hasClass('block-mode-fullscreen') ? iconFullscreenActive : iconFullscreen) + '"></i>');
            });

            jQuery('[data-toggle="block-option"][data-action="content_toggle"]').each(function(){
                var el = jQuery(this);

                el.html('<i class="' + (el.closest('.block').hasClass('block-mode-hidden') ? iconContentActive : iconContent) + '"></i>');
            });

            // Unbind event in case it is already enabled
            lPage.off('click.cb.blocks');

            // Call blocks API on option button click
            lPage.on('click.cb.blocks', '[data-toggle="block-option"]', function(){
                uiApiBlocks(jQuery(this).closest('.block'), jQuery(this).data('action'));
            });
        } else {
            // Get block element
            var elBlock = (block instanceof jQuery) ? block : jQuery(block);

            // If element exists, procceed with blocks functionality
            if (elBlock.length) {
                // Get block option buttons if exist (need them to update their icons)
                var btnFullscreen       = jQuery('[data-toggle="block-option"][data-action="fullscreen_toggle"]', elBlock);
                var btnContentToggle    = jQuery('[data-toggle="block-option"][data-action="content_toggle"]', elBlock);

                // Mode selection
                switch(mode) {
                    case 'fullscreen_toggle':
                        elBlock.removeClass('block-mode-pinned').toggleClass('block-mode-fullscreen');

                        // Enable/disable scroll lock to block
                        if (elBlock.hasClass('block-mode-fullscreen')) {
                            jQuery(elBlock).scrollLock('enable');
                        } else {
                            jQuery(elBlock).scrollLock('disable');
                        }

                        // Update block option icon
                        if (btnFullscreen.length) {
                            if (elBlock.hasClass('block-mode-fullscreen')) {
                                jQuery('i', btnFullscreen)
                                    .removeClass(iconFullscreen)
                                    .addClass(iconFullscreenActive);
                            } else {
                                jQuery('i', btnFullscreen)
                                    .removeClass(iconFullscreenActive)
                                    .addClass(iconFullscreen);
                            }
                        }
                        break;
                    case 'fullscreen_on':
                        elBlock.removeClass('block-mode-pinned').addClass('block-mode-fullscreen');

                        // Enable scroll lock to block
                        jQuery(elBlock).scrollLock('enable');

                        // Update block option icon
                        if (btnFullscreen.length) {
                            jQuery('i', btnFullscreen)
                                .removeClass(iconFullscreen)
                                .addClass(iconFullscreenActive);
                        }
                        break;
                    case 'fullscreen_off':
                        elBlock.removeClass('block-mode-fullscreen');

                        // Disable scroll lock to block
                        jQuery(elBlock).scrollLock('disable');

                        // Update block option icon
                        if (btnFullscreen.length) {
                            jQuery('i', btnFullscreen)
                                .removeClass(iconFullscreenActive)
                                .addClass(iconFullscreen);
                        }
                        break;
                    case 'content_toggle':
                        elBlock.toggleClass('block-mode-hidden');

                        // Update block option icon
                        if (btnContentToggle.length) {
                            if (elBlock.hasClass('block-mode-hidden')) {
                                jQuery('i', btnContentToggle)
                                    .removeClass(iconContent)
                                    .addClass(iconContentActive);
                            } else {
                                jQuery('i', btnContentToggle)
                                    .removeClass(iconContentActive)
                                    .addClass(iconContent);
                            }
                        }
                        break;
                    case 'content_hide':
                        elBlock.addClass('block-mode-hidden');

                        // Update block option icon
                        if (btnContentToggle.length) {
                            jQuery('i', btnContentToggle)
                                .removeClass(iconContent)
                                .addClass(iconContentActive);
                        }
                        break;
                    case 'content_show':
                        elBlock.removeClass('block-mode-hidden');

                        // Update block option icon
                        if (btnContentToggle.length) {
                            jQuery('i', btnContentToggle)
                                .removeClass(iconContentActive)
                                .addClass(iconContent);
                        }
                        break;
                    case 'state_toggle':
                        elBlock.toggleClass('block-mode-loading');

                        // Return block to normal state if the demostration mode is on in the refresh option button - data-action-mode="demo"
                        if (jQuery('[data-toggle="block-option"][data-action="state_toggle"][data-action-mode="demo"]', elBlock).length) {
                            setTimeout(function(){
                                elBlock.removeClass('block-mode-loading');
                            }, 2000);
                        }
                        break;
                    case 'state_loading':
                        elBlock.addClass('block-mode-loading');
                        break;
                    case 'state_normal':
                        elBlock.removeClass('block-mode-loading');
                        break;
                    case 'pinned_toggle':
                        elBlock.removeClass('block-mode-fullscreen').toggleClass('block-mode-pinned');
                        break;
                    case 'pinned_on':
                        elBlock.removeClass('block-mode-fullscreen').addClass('block-mode-pinned');
                        break;
                    case 'pinned_off':
                        elBlock.removeClass('block-mode-pinned');
                        break;
                    case 'close':
                        elBlock.hide();
                        break;
                    case 'open':
                        elBlock.show();
                        break;
                    default:
                        return false;
                }
            }
        }
    };

    /*
     ********************************************************************************************
     *
     * PRIVATE HELPERS
     *
     * Private helper functions
     *
     *********************************************************************************************
     */

    // Get window width
    var getWidth = function() {
        return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    };

    /*
     ********************************************************************************************
     *
     * CORE HELPERS
     *
     * Third party plugin inits or various custom user interface helpers to extend functionality
     * They are called by default and can be used right away
     *
     *********************************************************************************************
     */

    // Toggle class
    var uiHelperCoreToggleClass = function() {
        jQuery('[data-toggle="class-toggle"]:not(.js-class-toggle-enabled)').on('click.cb.helpers.core', function(){
            var el = jQuery(this);

            // Add .js-class-toggle-enabled class to tag it as activated
            el.addClass('js-class-toggle-enabled');

            jQuery(el.data('target').toString()).toggleClass(el.data('class').toString());

            if (lHtml.hasClass('no-focus')) {
                el.blur();
            }
        });
    };

    // Scroll to element animation
    var uiHelperCoreScrollTo = function() {
        jQuery('[data-toggle="scroll-to"]:not(.js-scroll-to-enabled)').on('click.cb.helpers.core', function(e){
            e.stopPropagation();

            // Set variables
            var el            = jQuery(this);
            var elTarget      = el.data('target') || el.attr('href');
            var elSpeed       = el.data('speed') || 1000;
            var headerHeight  = (lHeader.length && lPage.hasClass('page-header-fixed')) ? lHeader.outerHeight() : 0;

            // Add .js-scroll-to-enabled class to tag it as activated
            el.addClass('js-scroll-to-enabled');

            jQuery('html, body').animate({
                scrollTop: jQuery(elTarget).offset().top - headerHeight
            }, elSpeed);
        });
    };

    // Add the correct copyright year
    var uiHelperCoreYearCopy = function() {
        var yearCopy = jQuery('.js-year-copy');

        if (yearCopy.length > 0) {
            var date        = new Date();
            var curYear     = date.getFullYear();
            var baseYear    = (yearCopy.html().length > 0) ? yearCopy.html() : curYear;

            if (parseInt(baseYear) >= curYear) {
                yearCopy.html(curYear);
            } else {
                yearCopy.html(baseYear + '-' + curYear.toString().substr(2, 2));
            }
        }
    };

    // Bootstrap tooltip, for more examples you can check out https://getbootstrap.com/docs/4.0/components/tooltips/
    var uiHelperCoreTooltip = function() {
        jQuery('[data-toggle="tooltip"]:not(.js-tooltip-enabled)').add('.js-tooltip:not(.js-tooltip-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-tooltip-enabled class to tag it as activated
            el.addClass('js-tooltip-enabled');

            // Init
            el.tooltip({
                container: el.data('container') || 'body',
                animation: el.data('animation') || false
            });
        });
    };

    // Bootstrap popover, for more examples you can check out https://getbootstrap.com/docs/4.0/components/popovers/
    var uiHelperCorePopover = function() {
        jQuery('[data-toggle="popover"]:not(.js-popover-enabled)').add('.js-popover:not(.js-popover-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-popover-enabled class to tag it as activated
            el.addClass('js-popover-enabled');

            // Init
            el.popover({
                container: el.data('container') || 'body',
                animation: el.data('animation') || false,
                trigger: el.data('trigger') || 'hover focus'
            });
        });
    };

    // Bootstrap tab, for more examples you can check out http://getbootstrap.com/docs/4.0/components/navs/#tabs
    var uiHelperCoreTab = function() {
        jQuery('[data-toggle="tabs"]:not(.js-tabs-enabled)').add('.js-tabs:not(.js-tabs-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-tabs-enabled class to tag it as activated
            el.addClass('js-tabs-enabled');

            // Init
            el.find('a').on('click.cb.helpers.core', function(e){
                e.preventDefault();
                jQuery(this).tab('show');
            });
        });
    };

    // jQuery Appear, for more examples you can check out https://github.com/bas2k/jquery.appear
    var uiHelperCoreAppear = function(){
        // Add a specific class on elements (when they become visible on scrolling)
        jQuery('[data-toggle="appear"]:not(.js-appear-enabled)').each(function(){
            windowW = getWidth();

            var el         = jQuery(this);
            var elCssClass = el.data('class') || 'animated fadeIn';
            var elOffset   = el.data('offset') || 0;
            var elTimeout  = (lHtml.hasClass('ie9') || windowW < 992) ? 0 : (el.data('timeout') ? el.data('timeout') : 0);

            // Add .js-appear-enabled class to tag it as activated
            el.addClass('js-appear-enabled');

            // Init
            el.appear(function() {
                setTimeout(function(){
                    el
                        .removeClass('invisible')
                        .addClass(elCssClass);
                }, elTimeout);
            },{accY: elOffset});
        });
    };

    // jQuery Appear + jQuery countTo, for more examples you can check out https://github.com/bas2k/jquery.appear and https://github.com/mhuggins/jquery-countTo
    var uiHelperCoreAppearCountTo = function(){
        // Init counter functionality
        jQuery('[data-toggle="countTo"]:not(.js-count-to-enabled)').each(function(){
            var el         = jQuery(this);
            var elAfter    = el.data('after');
            var elBefore   = el.data('before');

            // Add .js-count-to-enabled class to tag it as activated
            el.addClass('js-count-to-enabled');

            // Init
            el.appear(function() {
                el.countTo({
                    speed: el.data('speed') || 1500,
                    refreshInterval: el.data('refresh-interval') || 15,
                    onComplete: function() {
                        if(elAfter) {
                            el.html(el.html() + elAfter);
                        } else if (elBefore) {
                            el.html(elBefore + el.html());
                        }
                    }
                });
            });
        });
    };

    // jQuery SlimScroll, for more examples you can check out http://rocha.la/jQuery-slimScroll
    var uiHelperCoreSlimscroll = function(){
        // Init slimScroll functionality
        jQuery('[data-toggle="slimscroll"]:not(.js-slimscroll-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-slimscroll-enabled class to tag it as activated
            el.addClass('js-slimscroll-enabled');

            // Init
            el.slimScroll({
                height: el.data('height') || '200px',
                size: el.data('size') || '5px',
                position: el.data('position') || 'right',
                color: el.data('color') || '#000',
                opacity: el.data('opacity') || '.25',
                distance: el.data('distance') || '0',
                alwaysVisible: el.data('always-visible') ? true : false,
                railVisible: el.data('rail-visible') ? true : false,
                railColor: el.data('rail-color') ||'#999',
                railOpacity: el.data('rail-opacity') || .3
            });
        });
    };

    // Manage page loading screen functionality
    var uiHelperCorePageLoader = function(mode, colorClass) {
        var lpageLoader = jQuery('#page-loader');

        if (mode === 'show') {
            if (lpageLoader.length) {
                if (colorClass) {
                    lpageLoader.removeClass().addClass(colorClass);
                }

                lpageLoader.addClass('show');
            } else {
                if (colorClass) {
                    lBody.prepend('<div id="page-loader" class="show ' + colorClass + '"></div>');
                } else {
                    lBody.prepend('<div id="page-loader" class="show"></div>');
                }
            }
        } else if (mode === 'hide') {
            if (lpageLoader.length) {
                lpageLoader.removeClass('show');
            }
        }

        return false;
    };

    // Ripple effect fuctionality
    var uiHelperCoreRipple = function() {
        jQuery('[data-toggle="click-ripple"]:not(.js-click-ripple-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-click-ripple-enabled class to tag it as activated
            el.addClass('js-click-ripple-enabled');

            // Add required properties to the element
            el.css({
                'overflow': 'hidden',
                'position': 'relative',
                'z-index': 1
            });

            // On element click
            el.on('click.cb.helpers.core', function(e) {
                var cssClass = 'click-ripple', ripple, d, x, y;

                // If the ripple element doesn't exist in this element, add it..
                if (el.children('.' + cssClass).length === 0) {
                    el.prepend('<span class="' + cssClass + '"></span>');
                }
                else { // ..else remove .animate class from ripple element
                    el.children('.' + cssClass).removeClass('animate');
                }

                // Get the ripple element
                var ripple = el.children('.' + cssClass);

                // If the ripple element doesn't have dimensions set them accordingly
                if(!ripple.height() && !ripple.width()) {
                    d = Math.max(el.outerWidth(), el.outerHeight());
                    ripple.css({height: d, width: d});
                }

                // Get coordinates for our ripple element
                x = e.pageX - el.offset().left - ripple.width()/2;
                y = e.pageY - el.offset().top - ripple.height()/2;

                // Position the ripple element and add the class .animate to it
                ripple.css({top: y + 'px', left: x + 'px'}).addClass('animate');
            });
        });
    };

    /*
     ********************************************************************************************
     *
     * UI HELPERS (ON DEMAND)
     *
     * Third party plugin inits or various custom user interface helpers to extend functionality
     * They need to be called in a page to be initialized. They are included here to be easy to
     * init them on demand on multiple pages (usually repeated init code in common components)
     *
     ********************************************************************************************
     */

    /*
     * Print Page functionality
     *
     * Codebase.helper('print-page');
     *
     */
    var uiHelperPrint = function() {
        // Store all #page-container classes
        var pageCls = lPage.prop('class');

        // Remove all classes from #page-container
        lPage.prop('class', '');

        // Print the page
        window.print();

        // Restore all #page-container classes
        lPage.prop('class', pageCls);
    };

    /*
     * Custom Table functionality such as section toggling or checkable rows
     *
     * Codebase.helper('table-tools');
     *
     */

    // Table sections functionality
    var uiHelperTableToolsSections = function(){
        // For each table
        jQuery('.js-table-sections:not(.js-table-sections-enabled)').each(function(){
            var table = jQuery(this);

            // Add .js-table-sections-enabled class to tag it as activated
            table.addClass('js-table-sections-enabled');

            // When a row is clicked in tbody.js-table-sections-header
            jQuery('.js-table-sections-header > tr', table).on('click.cb.helpers', function(e) {
                if (e.target.type !== 'checkbox'
                        && e.target.type !== 'button'
                        && e.target.tagName.toLowerCase() !== 'a'
                        && !jQuery(e.target).parent('label').length) {
                    var row    = jQuery(this);
                    var tbody  = row.parent('tbody');

                    if ( ! tbody.hasClass('show')) {
                        jQuery('tbody', table).removeClass('show table-active');
                    }

                    tbody.toggleClass('show table-active');
                }
            });
        });
    };

    // Checkable table functionality
    var uiHelperTableToolsCheckable = function() {
        // For each table
        jQuery('.js-table-checkable:not(.js-table-checkable-enabled)').each(function(){
            var table = jQuery(this);

            // Add .js-table-checkable-enabled class to tag it as activated
            table.addClass('js-table-checkable-enabled');

            // When a checkbox is clicked in thead
            jQuery('thead input:checkbox', table).on('click.cb.helpers', function() {
                var checkedStatus = jQuery(this).prop('checked');

                // Check or uncheck all checkboxes in tbody
                jQuery('tbody input:checkbox', table).each(function() {
                    var checkbox = jQuery(this);

                    checkbox.prop('checked', checkedStatus);
                    uiHelperTableToolscheckRow(checkbox, checkedStatus);
                });
            });

            // When a checkbox is clicked in tbody
            jQuery('tbody input:checkbox', table).on('click.cb.helpers', function() {
                var checkbox = jQuery(this);

                uiHelperTableToolscheckRow(checkbox, checkbox.prop('checked'));
            });

            // When a row is clicked in tbody
            jQuery('tbody > tr', table).on('click.cb.helpers', function(e) {
                if (e.target.type !== 'checkbox'
                        && e.target.type !== 'button'
                        && e.target.tagName.toLowerCase() !== 'a'
                        && !jQuery(e.target).parent('label').length) {
                    var checkbox       = jQuery('input:checkbox', this);
                    var checkedStatus  = checkbox.prop('checked');

                    checkbox.prop('checked', ! checkedStatus);
                    uiHelperTableToolscheckRow(checkbox, ! checkedStatus);
                }
            });
        });
    };

    // Checkable table functionality helper - Checks or unchecks table row
    var uiHelperTableToolscheckRow = function(checkbox, checkedStatus) {
        if (checkedStatus) {
            checkbox
                .closest('tr')
                .addClass('table-active');
        } else {
            checkbox
                .closest('tr')
                .removeClass('table-active');
        }
    };

    /*
     * Content filtering functionality
     *
     * Codebase.helper('content-filter');
     *
     */
    var uiHelperContentFilter = function() {
        // Content Filtering init
        jQuery('.js-filter:not(.js-filter-enabled)').each(function(){
            var el          = jQuery(this);
            var filterNav   = jQuery('.nav-pills', el);
            var filterLinks = jQuery('a[data-category-link]', el);
            var filterItems = jQuery('[data-category]', el);
            var filterSpeed = el.data('speed') || 200;

            // Add .js-filter-enabled class to tag it as activated
            el.addClass('js-filter-enabled');

            // If navigation pills are used, make them responsive (stacked on smaller screens)
            if (filterNav.length) {
                var resizeTimeout, windowW;

                jQuery(window).on('resize.cb.helpers', function(){
                    clearTimeout(resizeTimeout);

                    resizeTimeout = setTimeout(function(){
                        windowW = getWidth();

                        if (windowW < 768) {
                            filterNav.addClass('flex-column');
                        } else {
                            filterNav.removeClass('flex-column');
                        }
                    }, 150);
                }).trigger('resize.cb.helpers');
            }

            // Add number of items to the links if enabled by adding data-numbers="true" to the main element
            if (el.data('numbers')) {
                filterLinks.each(function(){
                    var filterLink  = jQuery(this);
                    var filterCat   = filterLink.data('category-link');

                    // Add number of items to this category link
                    if (filterCat === 'all') {
                        filterLink.append(' (' + filterItems.length + ')');
                    } else {
                        filterLink.append(' (' + filterItems.filter('[data-category="' + filterCat + '"]').length + ')');
                    }
                });
            }

            // When a filter link is clicked
            filterLinks.on('click.cb.helpers', function() {
                var filterLink = jQuery(this);
                var filterCat;

                // Procceed only if the user clicked on an inactive category
                if ( ! filterLink.hasClass('active')) {
                    // Remove active class from all filter links
                    filterLinks.removeClass('active');

                    // Add the active class to the clicked link
                    filterLink.addClass('active');

                    // Get its data-category value
                    filterCat = filterLink.data('category-link');

                    // If the value is 'all' hide current visible items and show them all together, else hide them all and show only from the category we need
                    if (filterCat === 'all') {
                        if (filterItems.filter(':visible').length) {
                            filterItems.filter(':visible').fadeOut(filterSpeed, function(){
                                filterItems.fadeIn(filterSpeed);
                            });
                        } else {
                            filterItems.fadeIn(filterSpeed);
                        }
                    } else {
                        if (filterItems.filter(':visible').length) {
                            filterItems.filter(':visible').fadeOut(filterSpeed, function(){
                                filterItems
                                    .filter('[data-category="' + filterCat + '"]')
                                    .fadeIn(filterSpeed);
                            });
                        } else {
                            filterItems
                                .filter('[data-category="' + filterCat + '"]')
                                .fadeIn(filterSpeed);
                        }
                    }
                }

                return false;
            });
        });
    };

    /*
     ********************************************************************************************
     *
     * All the following helpers require each plugin's resources (JS, CSS) to be included in order to work
     *
     ********************************************************************************************
     */

    /*
     * Magnific Popup functionality, for more examples you can check out http://dimsemenov.com/plugins/magnific-popup/
     *
     * Codebase.helper('magnific-popup');
     *
     */
    var uiHelperMagnific = function(){
        // Gallery init
        jQuery('.js-gallery:not(.js-gallery-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-gallery-enabled class to tag it as activated
            el.addClass('js-gallery-enabled');

            // Init
            el.magnificPopup({
                delegate: 'a.img-lightbox',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    };

    /*
     * CKEditor init, for more examples you can check out http://ckeditor.com/
     *
     * Codebase.helper('ckeditor');
     *
     */
    var uiHelperCkeditor = function(){
        // Init inline text editor
        if (jQuery('#js-ckeditor-inline:not(.js-ckeditor-inline-enabled)').length) {
            jQuery('#js-ckeditor-inline').attr('contenteditable','true');
            CKEDITOR.inline('js-ckeditor-inline');

            // Add .js-ckeditor-inline-enabled class to tag it as activated
            jQuery('#js-ckeditor-inline').addClass('js-ckeditor-inline-enabled');
        }

        // Init full text editor
        if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
            CKEDITOR.replace('js-ckeditor');

            // Add .js-ckeditor-enabled class to tag it as activated
            jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
        }
    };

    /*
     * SimpleMDE init, for more examples you can check out https://github.com/NextStepWebs/simplemde-markdown-editor
     *
     * Codebase.helper('simplemde');
     *
     */
    var uiHelperSimpleMDE = function(){
        // Init markdown editor (with .js-simplemde class)
        jQuery('.js-simplemde:not(.js-simplemde-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-simplemde-enabled class to tag it as activated
            el.addClass('js-simplemde-enabled');

            // Init editor
            new SimpleMDE({ element: el[0] });
        });
    };

    /*
     * Slick init, for more examples you can check out http://kenwheeler.github.io/slick/
     *
     * Codebase.helper('slick');
     *
     */
    var uiHelperSlick = function(){
        // Get each slider element (with .js-slider class)
        jQuery('.js-slider:not(.js-slider-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-slider-enabled class to tag it as activated
            el.addClass('js-slider-enabled');

            // Init slick slider
            el.slick({
                arrows: el.data('arrows') || false,
                dots: el.data('dots') || false,
                slidesToShow: el.data('slides-to-show') || 1,
                slidesToScroll: el.data('slides-to-scroll') || 1,
                centerMode: el.data('center-mode') || false,
                autoplay: el.data('autoplay') || false,
                autoplaySpeed: el.data('autoplay-speed') || 3000
            });
        });
    };

    /*
     * Bootstrap Datepicker init, for more examples you can check out https://github.com/eternicode/bootstrap-datepicker
     *
     * Codebase.helper('datepicker');
     *
     */
    var uiHelperDatepicker = function(){
        // Init datepicker (with .js-datepicker and .input-daterange class)
        jQuery('.js-datepicker:not(.js-datepicker-enabled)').add('.input-daterange:not(.js-datepicker-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-datepicker-enabled class to tag it as activated
            el.addClass('js-datepicker-enabled');

            // Init
            el.datepicker({
                weekStart: el.data('week-start') || 0,
                autoclose: el.data('autoclose') || false,
                todayHighlight: el.data('today-highlight') || false,
                orientation: 'bottom' // Position issue when using BS4, set it to bottom until officially supported
            });
        });
    };

    /*
     * Bootstrap Colorpicker init, for more examples you can check out https://github.com/itsjavi/bootstrap-colorpicker/
     *
     * Codebase.helper('colorpicker');
     *
     */
    var uiHelperColorpicker = function(){
        // Get each colorpicker element (with .js-colorpicker class)
        jQuery('.js-colorpicker:not(.js-colorpicker-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-enabled class to tag it as activated
            el.addClass('js-colorpicker-enabled');

            // Init colorpicker
            el.colorpicker();
        });
    };

    /*
     * Masked Inputs, for more examples you can check out http://digitalbush.com/projects/masked-input-plugin/
     *
     * Codebase.helper('masked-inputs');
     *
     */
    var uiHelperMaskedInputs = function(){
        // Init Masked Inputs
        // a - Represents an alpha character (A-Z,a-z)
        // 9 - Represents a numeric character (0-9)
        // * - Represents an alphanumeric character (A-Z,a-z,0-9)
        jQuery('.js-masked-date:not(.js-masked-enabled)').mask('99/99/9999');
        jQuery('.js-masked-date-dash:not(.js-masked-enabled)').mask('99-99-9999');
        jQuery('.js-masked-phone:not(.js-masked-enabled)').mask('(999) 999-9999');
        jQuery('.js-masked-phone-ext:not(.js-masked-enabled)').mask('(999) 999-9999? x99999');
        jQuery('.js-masked-taxid:not(.js-masked-enabled)').mask('99-9999999');
        jQuery('.js-masked-ssn:not(.js-masked-enabled)').mask('999-99-9999');
        jQuery('.js-masked-pkey:not(.js-masked-enabled)').mask('a*-999-a999');
        jQuery('.js-masked-time:not(.js-masked-enabled)').mask('99:99');

        jQuery('.js-masked-date')
            .add('.js-masked-date-dash')
            .add('.js-masked-phone')
            .add('.js-masked-phone-ext')
            .add('.js-masked-taxid')
            .add('.js-masked-ssn')
            .add('.js-masked-pkey')
            .add('.js-masked-time')
            .addClass('js-masked-enabled');
    };

    /*
     * Tags Inputs, for more examples you can check out https://github.com/xoxco/jQuery-Tags-Input
     *
     * Codebase.helper('tags-inputs');
     *
     */
    var uiHelperTagsInputs = function(){
        // Init Tags Inputs (with .js-tags-input class)
        jQuery('.js-tags-input:not(.js-tags-input-enabled)').each(function(){
           var el = jQuery(this);

            // Add .js-tags-input-enabled class to tag it as activated
            el.addClass('js-tags-input-enabled');

            // Init
            el.tagsInput({
                height: el.data('height') || false,
                width: el.data('width') || '100%',
                defaultText: el.data('default-text') || 'Add tag',
                removeWithBackspace: el.data('remove-with-backspace') || true,
                delimiter: [',']
            });
        });
    };

    /*
     * Select2, for more examples you can check out https://github.com/select2/select2
     *
     * Codebase.helper('select2');
     *
     */
    var uiHelperSelect2 = function(){
        // Init Select2 (with .js-select2 class)
        jQuery('.js-select2:not(.js-select2-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-select2-enabled class to tag it as activated
            el.addClass('js-select2-enabled');

            // Init
            el.select2();
        });
    };

    /*
     * Highlight.js, for more examples you can check out https://highlightjs.org/usage/
     *
     * Codebase.helper('highlightjs');
     *
     */
    var uiHelperHighlightjs = function(){
        // Init Highlight.js
        if ( ! hljs.isHighlighted) {
            hljs.initHighlighting();
        }
    };

    /*
     * Bootstrap Notify, for more examples you can check out http://bootstrap-growl.remabledesigns.com/
     *
     * Codebase.helper('notify');
     *
     */
    var uiHelperNotify = function(){
        // Init notifications (with .js-notify class)
        jQuery('.js-notify:not(.js-notify-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-notify-enabled class to tag it as activated
            el.addClass('js-notify-enabled');

            // Init on click
            el.on('click.cb.helpers', function(){
                var growl = jQuery(this);

                // Create notification
                jQuery.notify({
                        icon: growl.data('icon') || '',
                        message: growl.data('message'),
                        url: growl.data('url') || ''
                    },
                    {
                        element: 'body',
                        type: growl.data('type') || 'info',
                        allow_dismiss: true,
                        newest_on_top: true,
                        showProgressbar: false,
                        placement: {
                            from: growl.data('from') || 'top',
                            align: growl.data('align') || 'right'
                        },
                        offset: 20,
                        spacing: 10,
                        z_index: 1033,
                        delay: 5000,
                        timer: 1000,
                        template: '<div data-notify="container" class="col-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<span data-notify="icon"></span> ' +
                                    '<span data-notify="title">{1}</span> ' +
                                    '<span data-notify="message">{2}</span>' +
                                    '<div class="progress" data-notify="progressbar">' +
                                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                                    '</div>' +
                                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                                    '</div>',
                        animate: {
                            enter: 'animated fadeIn',
                            exit: 'animated fadeOutDown'
                        }
                    });
            });
        });
    };

    /*
     * Draggable items with jQuery, for more examples you can check out https://jqueryui.com/sortable/
     *
     * Codebase.helper('draggable-items');
     *
     */
    var uiHelperDraggableItems = function(){
        // Init draggable items functionality (with .js-draggable-items class)
        jQuery('.js-draggable-items:not(.js-draggable-items-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-draggable-items-enabled class to tag it as activated
            el.addClass('js-draggable-items-enabled');

            // Init
            el.children('.draggable-column').sortable({
                connectWith: '.draggable-column',
                items: '.draggable-item',
                dropOnEmpty: true,
                opacity: .75,
                handle: '.draggable-handler',
                placeholder: 'draggable-placeholder',
                tolerance: 'pointer',
                start: function(e, ui){
                    ui.placeholder.css({
                        'height': ui.item.outerHeight(),
                        'margin-bottom': ui.item.css('margin-bottom')
                    });
                }
            });
        });
    };

    /*
     * Easy Pie Chart, for more examples you can check out http://rendro.github.io/easy-pie-chart/
     *
     * Codebase.helper('easy-pie-chart');
     *
     */
    var uiHelperEasyPieChart = function(){
        // Init Easy Pie Charts (with .js-pie-chart class)
        jQuery('.js-pie-chart:not(.js-pie-chart-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-pie-chart-enabled class to tag it as activated
            el.addClass('js-pie-chart-enabled');

            // Init
            el.easyPieChart({
                barColor: el.data('bar-color') || '#777777',
                trackColor: el.data('track-color') || '#eeeeee',
                lineWidth: el.data('line-width') || 3,
                size: el.data('size') || '80',
                animate: el.data('animate') || 750,
                scaleColor: el.data('scale-color') || false
            });
        });
    };

    /*
     * Bootstrap Maxlength, for more examples you can check out https://github.com/mimo84/bootstrap-maxlength
     *
     * Codebase.helper('maxlength');
     *
     */
    var uiHelperMaxlength = function(){
        // Init Bootstrap Maxlength (with .js-maxlength class)
        jQuery('.js-maxlength:not(.js-maxlength-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-maxlength-enabled class to tag it as activated
            el.addClass('js-maxlength-enabled');

            // Init
            el.maxlength({
                alwaysShow: el.data('always-show') ? true : false,
                threshold: el.data('threshold') || 10,
                warningClass: el.data('warning-class') || 'badge badge-warning',
                limitReachedClass: el.data('limit-reached-class') || 'badge badge-danger',
                placement: el.data('placement') || 'bottom',
                preText: el.data('pre-text') || '',
                separator: el.data('separator') || '/',
                postText: el.data('post-text') || ''
            });
        });
    };

    /*
     * Ion Range Slider, for more examples you can check out https://github.com/IonDen/ion.rangeSlider
     *
     * Codebase.helper('rangeslider');
     *
     */
    var uiHelperRangeslider = function(){
        // Init Ion Range Slider (with .js-rangeslider class)
        jQuery('.js-rangeslider:not(.js-rangeslider-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-rangeslider-enabled class to tag it as activated
            el.addClass('js-rangeslider-enabled');

            // Init
            el.ionRangeSlider({
                input_values_separator: ';'
            });
        });
    };

    /*
     * Summernote, for more examples you can check out https://github.com/summernote/summernote/
     *
     * Codebase.helper('summernote');
     *
     */
    var uiHelperSummernote = function(){
        // Init text editor in air mode (inline)
        jQuery('.js-summernote-air:not(.js-summernote-air-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-summernote-air-enabled class to tag it as activated
            el.addClass('js-summernote-air-enabled');

            // Init
            el.summernote({
                airMode: true,
                tooltip: false
            });
        });

        // Init full text editor
        jQuery('.js-summernote:not(.js-summernote-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-summernote-enabled class to tag it as activated
            el.addClass('js-summernote-enabled');

            // Init
            el.summernote({
                height: 350,
                minHeight: null,
                maxHeight: null
            });
        });
    };

    return {
        init: function() {
            // LAYOUT VARIABLES
            uiInit();

            // BASE UI
            uiHandleScroll('init');
            uiHandleMain();
            uiHandleHeader();
            uiHandleNav();
            uiHandleForms();
            uiHandleTheme();

            // API
            uiApiLayout('init');
            uiApiBlocks(false, 'init');

            // CORE HELPERS
            uiHelperCoreToggleClass();
            uiHelperCoreScrollTo();
            uiHelperCoreYearCopy();
            uiHelperCoreTooltip();
            uiHelperCorePopover();
            uiHelperCoreTab();
            uiHelperCoreAppear();
            uiHelperCoreAppearCountTo();
            uiHelperCoreSlimscroll();
            uiHelperCorePageLoader('hide');
            uiHelperCoreRipple();
        },
        layout: function(mode) {
            uiApiLayout(mode);
        },
        blocks: function(block, mode) {
            uiApiBlocks(block, mode);
        },
        loader: function(mode, colorClass) {
            uiHelperCorePageLoader(mode, colorClass);
        },
        helper: function(helper) {
            switch (helper) {
                case 'core-fn-uiInit':
                    uiInit();
                    break;
                case 'core-fn-uiHandleScrollInit':
                    uiHandleScroll('init');
                    break;
                case 'core-fn-uiHandleScroll':
                    uiHandleScroll();
                    break;
                case 'core-fn-uiHandleMain':
                    uiHandleMain();
                    break;
                case 'core-fn-uiHandleHeader':
                    uiHandleHeader();
                    break;
                case 'core-fn-uiHandleNav':
                    uiHandleNav();
                    break;
                case 'core-fn-uiHandleForms':
                    uiHandleForms();
                    break;
                case 'core-fn-uiHandleTheme':
                    uiHandleTheme();
                    break;
                case 'core-fn-uiApiLayout':
                    uiApiLayout('init');
                    break;
                case 'core-fn-uiApiBlocks':
                    uiApiBlocks(false, 'init');
                    break;
                case 'core-tooltip':
                    uiHelperCoreTooltip();
                    break;
                case 'core-popover':
                    uiHelperCorePopover();
                    break;
                case 'core-tab':
                    uiHelperCoreTab();
                    break;
                case 'core-scrollTo':
                    uiHelperCoreScrollTo();
                    break;
                case 'core-toggle-class':
                    uiHelperCoreToggleClass();
                    break;
                case 'core-year-copy':
                    uiHelperCoreYearCopy();
                    break;
                case 'core-appear':
                    uiHelperCoreAppear();
                    break;
                case 'core-appear-countTo':
                    uiHelperCoreAppearCountTo();
                    break;
                case 'core-slimscroll':
                    uiHelperCoreSlimscroll();
                    break;
                case 'core-ripple':
                    uiHelperCoreRipple();
                    break;
                case 'core-page-loader':
                    uiHelperCorePageLoader('hide');
                    break;
                case 'print-page':
                    uiHelperPrint();
                    break;
                case 'table-tools':
                    uiHelperTableToolsSections();
                    uiHelperTableToolsCheckable();
                    break;
                case 'content-filter':
                    uiHelperContentFilter();
                    break;
                case 'slimscroll':
                    uiHelperSlimscroll();
                    break;
                case 'magnific-popup':
                    uiHelperMagnific();
                    break;
                case 'ckeditor':
                    uiHelperCkeditor();
                    break;
                case 'simplemde':
                    uiHelperSimpleMDE();
                    break;
                case 'slick':
                    uiHelperSlick();
                    break;
                case 'datepicker':
                    uiHelperDatepicker();
                    break;
                case 'colorpicker':
                    uiHelperColorpicker();
                    break;
                case 'tags-inputs':
                    uiHelperTagsInputs();
                    break;
                case 'masked-inputs':
                    uiHelperMaskedInputs();
                    break;
                case 'select2':
                    uiHelperSelect2();
                    break;
                case 'highlightjs':
                    uiHelperHighlightjs();
                    break;
                case 'notify':
                    uiHelperNotify();
                    break;
                case 'draggable-items':
                    uiHelperDraggableItems();
                    break;
                case 'easy-pie-chart':
                    uiHelperEasyPieChart();
                    break;
                case 'maxlength':
                    uiHelperMaxlength();
                    break;
                case 'rangeslider':
                    uiHelperRangeslider();
                    break;
                case 'summernote':
                    uiHelperSummernote();
                    break;
                default:
                    return false;
            }
        },
        helpers: function(helpers) {
            if (helpers instanceof Array) {
                for (var index in helpers) {
                    Codebase.helper(helpers[index]);
                }
            } else {
                Codebase.helper(helpers);
            }
        }
    };
}();

// Initialize when page loads
jQuery(function(){
    if (typeof angular === 'undefined') {
        Codebase.init();
    }
});
