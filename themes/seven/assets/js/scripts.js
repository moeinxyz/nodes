/*jshint jquery:true */
/*jshint unused:false */

$(document).ready(function($) {
    "use strict";

    jQuery(window).load(function () {

        // Page Loading Gif
        jQuery(".loadstack").fadeOut();
        jQuery(".preloader").delay(1000).fadeOut("slow");

        // Wow Page Animations
        var wow = new WOW(
          { animateClass: 'animated', // set our global css classT (default is animated)
            offset: 210, // set distance to content until it triggers (default is 0)
            mobile: false, // remove animations for mobiles/tablets (default is true)
            live: true }); // act on asynchronously loaded content (default is true)
        new WOW().init();

    });

    jQuery(function ($) {
        // Home hero fade effect
        var divs = $('.herofade');
        $(window).on('scroll', function () {
            var st = $(this).scrollTop();
            divs.css({
                'margin-top': -(st / 0) + "px",
                'opacity': 1 - st / 600
            });
        });
    });

    jQuery(function ($) {
        // Blog post hero fade effect
        var divs = $('.postfade');
        $(window).on('scroll', function () {
            var st = $(this).scrollTop();
            divs.css({
                'margin-top': -(st / 0) + "px",
                'opacity': 1 - st / 400
            });
        });
    });

    // Resizable 'on-demand' full-height hero
    var resizeHero = function () {
        var hero = $(".cover"),
            window1 = $(window);
        hero.css({
            "height": window1.height()
        });
    };

    resizeHero();

    $(window).resize(function () {
        resizeHero();
    });

    // Fix for iOS8 orientation bug
    // Works in conjunction with lines 332-348 in style.css
    var resizeWrapper = function () {
        var wrap = $("#wrapper,.wallpaper"),
            window1 = $(window);
        wrap.css({
            "width": window1.width()
        });
    };

    resizeWrapper();

    $(window).resize(function () {
        resizeWrapper();
    });


    // Flyup animation for main menu items
    // Combine with time delays, as seen between lines 767-860 in the theme stylesheet, (style.css)
    $(function () {
        $('.motion li').addClass('animated fadeInUp');
    });

    $(function () {
        $("[data-toggle='popover']").popover({
            trigger: 'focus'
        });
    });

    // Initialize Tooltips
    $("[data-toggle='tooltip']").tooltip();

    // Bullet Navigation
    // Apply 'current-page-item' when viewport scolls to DOM.
    // Corrosponds with jquery.nav.js in [root]/js folder
    $('#bullets').onePageNav({
        currentClass: 'current-page-item'
    });

    //Hero ScollTo
    $("a.scrollto").click(function () {
        $("html,body").animate({
            scrollTop: $(".scrollreceive").offset().top
        }, 1200);
        return false;
    });

    // Page Scrollbar
    // There are many options available - see http://areaaperta.com/nicescroll/ for further usage variables
    // =======================================================
    var nice = $("html").niceScroll({
        cursorcolor: "#202020",
        scrollspeed: 60,
        mousescrollstep: 30,
        boxzoom: false,
        autohidemode: false,
        cursorborder: "0 solid #202020",
        cursorborderradius: "0",
        cursorwidth: 10,
        background: "#666",
        horizrailenabled: false
    });

    // Team / Staff Block
    // =======================================================
    $(".team-avatars a").click(function (e) {
        e.preventDefault();
    });

    $(".team-avatars a").hover(function () {
        $(".team-avatars a").removeClass('active');
        $(this).addClass('active');
        var team_meta_id = $(this).attr('href');
        $(".team-meta").removeClass('active');
        $(team_meta_id).addClass('active');
    });

    // Main Menu Background fade effect
    // =======================================================
    $('.modal-body li.col-md-4.col-sm-6').hover(
        function () {
            $(this).find('.menubg').stop().animate({
                opacity: 0.8
            }, 200, 'easeOutQuad');
        },
        function () {
            $(this).find('.menubg').stop().animate({
                opacity: 0.3
            }, 200, 'easeInQuad');
        }
    );

    /*!
     * IE10 viewport hack for Surface/desktop Windows 8 bug
     * Copyright 2014 Twitter, Inc.
     * Licensed under the Creative Commons Attribution 3.0 Unported License. For
     * details, see http://creativecommons.org/licenses/by/3.0/.
     */
    // See the Getting Started docs for more information:
    // http://getbootstrap.com/getting-started/#support-ie10-width
    if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
        var msViewportStyle = document.createElement('style');
        msViewportStyle.appendChild(
            document.createTextNode(
                '@-ms-viewport{width:auto!important}'
            )
        );
        document.querySelector('head').appendChild(msViewportStyle);
    }

}(jQuery));