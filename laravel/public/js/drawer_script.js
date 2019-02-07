const offsetLeft = 240;

const closeDrawer = function () { 
    $('.drawer-nav').width(0);
    $('.drawer-toggle').offset({ left: 0 });
    $('#menu_back').hide();
};

const openDrawer = function(){
    $('.drawer-nav').width(offsetLeft);

    $('#menu_back').show();
    $('.drawer-toggle').offset({ left: offsetLeft });
};


$(document).ready(function () {
    $('.drawer').drawer();

    $('.drawer-toggle').click(function () {
        if ($('.drawer-nav').offset().left < 0) {
            openDrawer();
        } else {
            closeDrawer();
        }
    });
});

$(window).on('load resize', function () {
    closeDrawer();
});