/*
 * jQuery Carousel/slider
 * Version 0.0.2
 *
 * Carousel done my way
 *
 * Copyright (c) 2011 Adrian Unger (http://staydecent.ca)
 * Released under the MIT license.
*/

(function( $ ) {

    $.fn.carousel = function(options) {
        _this = $(this); // save this

        var defaults = {
            speed: 800, // string, or integer
        };

        var options = $.extend(defaults, options);

        _this.each(function(){
            var container = $(this);
            var images = $('img', container);
            var animating = false;
            
            // setup CSS
            container.css({ overflow:'hidden' });
            images.css({ position:'absolute', left:'0', top:'0', zIndex:'8', opacity:0 });
            images.first().addClass('active').css('zIndex', '10').animate({ opacity:1 }, options.speed);
            $("nav .prev", container).addClass('disabled');

            var next_slide = function() {
                var active = container.find('img.active');
                // find the next element, or swing back around to the first one
                var next = active.next('img').length ? active.next() : false;
                // no loop, just stop
                if (!next || animating) return false;
                var future = active.next('img').next('img').length ? active.next() : false;
                if (!future) {
                    $("nav .next", container).addClass('disabled');
                }
                else if (future || next) {
                    $(".disabled", container).removeClass('disabled');                    
                }

                animating = true;

                next.addClass('active')
                    .css({ opacity:0, zIndex:'10' })
                    .animate({opacity: 1}, options.speed, function() {
                        active.addClass('last_active')
                            .css({ opacity:0, zIndex:'9' })
                            .removeClass('active last_active');
                        animating = false;
                    });

                return false;
            };
            var prev_slide = function() {
                var active = container.find('img.active');
                // find the next element, or swing back around to the first one
                var prev = active.prev('img').length ? active.prev() : false;
                // no loop, just stop
                if (!prev || animating) return false;
                var future = active.prev('img').prev('img').length ? active.next() : false;
                if (!future) {
                    $("nav .prev", container).addClass('disabled');
                }
                else if (future || prev) {
                    $(".disabled", container).removeClass('disabled');                    
                }

                animating = true;

                active.css('zIndex', '9');
                prev.addClass('active')
                    .css({ opacity:0, zIndex:'10' })
                    .animate({opacity: 1}, options.speed, function() {
                        active.addClass('last_active')
                            .css({ opacity:0, zIndex:'9' })
                            .removeClass('active last_active');
                        animating = false;
                    });

                return false;
            };

            // bind buttons 
            $("nav .next", container).click(function(e) { 
                next_slide();
                e.preventDefault();
            });
            $("nav .prev", container).click(function(e) { 
                prev_slide();
                e.preventDefault();
            });
        });

    };

})( jQuery );