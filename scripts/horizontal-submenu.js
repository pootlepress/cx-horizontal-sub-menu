/**
 * Created by Alan on 21/4/2014.
 */

(function ($) {

    $(document).ready(function () {


        $('#navigation ul.nav > .menu-item').each(function () {
            $(this).hover(function () {
                var $subMenu = $(this).find('.sub-menu');
                if ($subMenu.length > 0 && $subMenu.find('.current-menu-item').length == 0) {
                    $subMenu.show();
                    $subMenu.css('visibility', 'visible');
                    $subMenu.css('z-index', '10000');
                }
            }, function () {
                var $subMenu = $(this).find('.sub-menu');
                if ($subMenu.length > 0 && $subMenu.find('.current-menu-item').length == 0) {
                    $subMenu.hide();
                    $subMenu.css('visibility', 'hidden');
                    $subMenu.css('z-index', '9999');
                }
            });
        });

        var navHeight = $('#navigation').outerHeight(false);

        var navBorderTop = $('#navigation').css('border-top-width');
        navBorderTop = navBorderTop.replace('px', '');
        navBorderTop = parseInt(navBorderTop);
        $('#navigation .sub-menu').css('top', (navHeight - navBorderTop) + 'px');


        var hasSubmenuShown = false;
        var $subMenuShown = null;
        $('#navigation .sub-menu .current-menu-item').each(function () {
           var $subMenu = $(this).closest('.sub-menu');
            $subMenu.show();
            $subMenu.css('visibility', 'visible');

            $subMenuShown = $subMenu;
            hasSubmenuShown = true;
        });

        if (hasSubmenuShown) {
            var navMarginBottom = $('#navigation').css('margin-bottom');
            navMarginBottom = navMarginBottom.replace('px', '');
            navMarginBottom = parseInt(navMarginBottom);

            var space = $subMenuShown.outerHeight(true);
            $('#navigation').css('margin-bottom', (space + navMarginBottom) + "px");
        }

        setSubMenuWidth();

        $(window).resize(function () {
           setSubMenuWidth();
        });
    });

    function convertPixelValue(withPx) {
        var result = withPx.replace('px', '');
        result = parseFloat(result);
        return result;
    }

    window.setSubMenuWidth = function() {
        // check if header is full width
        if ($("body").hasClass('full-header')) {
            var windowWidth = $('body').innerWidth();

            $('#navigation .sub-menu').each(function () {

                $(this).css('box-sizing', 'border-box');

                var borderLeftWidth = $(this).css('border-left-width');
                borderLeftWidth = convertPixelValue(borderLeftWidth);

                var borderRightWidth = $(this).css('border-right-width');
                borderRightWidth = convertPixelValue(borderRightWidth);

//                var newWidth = windowWidth;
                var newWidth = $('#header-container').width();
                console.log('Header container width: ' + newWidth);

                $(this).css('width', newWidth + 'px');

                // have to show this element, so jquery can return its offset correctly
                var initialDisplay = $(this).css('display');
                var initialVisibility = $(this).css('visibility');

                $(this).css('visibility', 'hidden');
                $(this).show();

                // get and set offset relative to document
                var offset = $(this).offset();
                var documentLeft = offset.left;

                var left = $(this).css('left');
                left = left.substr(0, left.length - 2);
                left = parseInt(left);

                var newLeft = left - documentLeft;

                $(this).attr('style', function(i,s) { return s + 'left: ' + newLeft + 'px !important;' });

                $(this).css('display', initialDisplay);
                $(this).css('visibility', initialVisibility);


                if (typeof StickyHeader2Compat != 'undefined' && StickyHeader2Compat != null && StickyHeader2Compat.isFullHeaderAndAlignRight) {

                } else if (typeof PHS != 'undefined' && PHS != null && PHS.isPrimaryNavCentered) {
                } else {
                    var $nav = $(this).closest('ul.nav');
                    var $firstTopItem = $nav.find('> .menu-item:first-child');
                    var topItemOffset = $firstTopItem.offset();

                    var submenuBorderLeftWidth = $(this).css('border-left-width');
                    submenuBorderLeftWidth = convertPixelValue(submenuBorderLeftWidth);

                    var paddingLeft = topItemOffset.left - submenuBorderLeftWidth;
                    paddingLeft = paddingLeft + 'px';
                    $(this).css('padding-left', paddingLeft);
                }

            });
        }
    }

})(jQuery);
