/**
 * Created by Alan on 21/4/2014.
 */

(function ($) {

    $(document).ready(function () {

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

    function setSubMenuWidth() {
        // check if header is full width
        if ($("body").hasClass('full-header')) {
            var windowWidth = $('body').innerWidth();

            $('#navigation .sub-menu').each(function () {

                var borderLeftWidth = $(this).css('border-left-width');
                borderLeftWidth = convertPixelValue(borderLeftWidth);

                var borderRightWidth = $(this).css('border-right-width');
                borderRightWidth = convertPixelValue(borderRightWidth);

                var newWidth = windowWidth - borderLeftWidth - borderRightWidth;

                $(this).width(newWidth);

                // have to show this element, so jquery can return its offset correctly
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

                $(this).css('display', '');
                $(this).css('visibility', '');


                var $li = $(this).closest('li');
                var topItemOffset = $li.offset();

                var submenuBorderLeftWidth = $(this).css('border-left-width');
                submenuBorderLeftWidth = convertPixelValue(submenuBorderLeftWidth);

                var paddingLeft = topItemOffset.left - submenuBorderLeftWidth;
                paddingLeft = paddingLeft + 'px';
                $(this).css('padding-left', paddingLeft);
            });
        }
    }

})(jQuery);
