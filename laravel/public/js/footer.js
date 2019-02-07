$(function(){
    $('#footer').hide();
    let scrollHeight = $(document).height();
    let scrollPosition = $(window).height() + $(window).scrollTop();
    if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
        $('#footer').show();
    }
});
$(window).on("scroll", function() {
    let scrollHeight = $(document).height();
    let scrollPosition = $(window).height() + $(window).scrollTop();
    if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
        $('#footer').show();
    }else{
        $('#footer').hide();
    }
});