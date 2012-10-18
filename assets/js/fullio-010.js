/*! fullio-010.js - http://fullioapp.com */
$(document).ready(function() {
    /**
     * Setup
     */
    var $root = ("onorientationchange" in window) ? $(document) : $(window);
    var setI = 0;

    $('.next,.prev').css({ top: (($root.height()*(setI+1))/2)-44 });

    $('.set').css({ 
        width:$root.width(),
        height:$root.height(),
        top:0
    });

    _resize($root);
    $('.set.active').carousel();

    /**
     * Events
     */
    $(window).resize(function() { 
        _resize($root, setI);
    });

    $('#down').click(function(e) {
        if ($(this).hasClass('disabled'))
            return;

        var active = $('.set.active');
        active
            .animate({top:-($root.height())}, 250)
            .removeClass('active')
        .next('.set').addClass('active');

        $('.set.active').carousel();

        // new active 
        var active = $('.set.active');

        var past = active.prev('.set').length ? true : false;
        var future = active.next('.set').next('.set').length ? active.next('.set') : false;

        if (!future) {
            $("#down").addClass('disabled');
        }
        
        if (past) {
            $("#up.disabled").removeClass('disabled');                    
        }
    });
    $('#up').click(function(e) {
        if ($(this).hasClass('disabled'))
            return;

        var active = $('.set.active');
        active
            .animate({top:($root.height())}, 250)
            .removeClass('active')
        .next('.set').addClass('active');

        $('.set.active').carousel();

        // new active 
        var active = $('.set.active');

        var past = active.next('.set').length ? true : false;
        var future = active.prev('.set').prev('.set').length ? active.prev('.set') : false;

        if (!future) {
            $("#up").addClass('disabled');
        }
        
        if (past) {
            $("#down.disabled").removeClass('disabled');                    
        }
    });
});

function _resize(rootElement, setI) {
    $('.set').css({
        'height':rootElement.height(),
        'width':rootElement.width()
    });
    $('img').scaleImage().animate({'opacity':1}, 450);
    $('.next,.prev').css({ top: ((rootElement.height()*(setI+1))/2)-44 });
}