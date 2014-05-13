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

    });

})(jQuery);
