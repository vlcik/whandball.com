// <![CDATA[

function mycarousel_initCallback(carousel) {
    $('.cv_newslist-next').bind('click', function() { carousel.next(); return false; });
    $('.cv_newslist-prev').bind('click', function() { carousel.prev(); return false; });
};

// Ride the carousel...
$(function() {
    $(".cv_newslist").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback,
        buttonNextHTML: null,
        buttonPrevHTML: null,
        vertical: true,
        itemLastOutCallback: { onAfterAnimation: disableCustomButtons },
        itemLastInCallback: { onAfterAnimation: disableCustomButtons }
    });
});

function disableCustomButtons(carousel){
	/*
    var prev_class = 'jcarousel-prev-disabled jcarousel-prev-disabled-vertical';
    if (carousel.first == 1) { $('.cv_newslist-prev').attr('disabled', 'true').addClass(prev_class); }
	else { $('.cv_newslist-prev').removeAttr('disabled').removeClass(prev_class); }
    var next_class = 'jcarousel-next-disabled jcarousel-next-disabled-vertical';
    if (carousel.last == carousel.size()) { $('.cv_newslist-next').attr('disabled', 'true').addClass(next_class); }
	else { $('.cv_newslist-next').removeAttr('disabled').removeClass(next_class); }
	*/
};