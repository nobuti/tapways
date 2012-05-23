(function( $ ){

    // ========================= smartresize ===============================

    /*
    * smartresize: debounced resize event for jQuery
    *
    * latest version and complete README available on Github:
    * https://github.com/louisremi/jquery.smartresize.js
    *
    * Copyright 2011 @louis_remi
    * Licensed under the MIT license.
    */

    var $event = $.event,
        resizeTimeout;

    $event.special.smartresize = {
        setup: function() {
            $(this).bind( "resize", $event.special.smartresize.handler );
        },
        teardown: function() {
            $(this).unbind( "resize", $event.special.smartresize.handler );
        },
        handler: function( event, execAsap ) {
            // Save the context
            var context = this,
                args = arguments;

            // set correct event type
            event.type = "smartresize";

            if ( resizeTimeout ) { clearTimeout( resizeTimeout ); }
            resizeTimeout = setTimeout(function() {
                jQuery.event.handle.apply( context, args );
            }, execAsap === "execAsap"? 0 : 100 );
        }
    };

    $.fn.smartresize = function( fn ) {
        return fn ? this.bind( "smartresize", fn ) : this.trigger( "smartresize", ["execAsap"] );
    };


    // ======================= imagesLoaded Plugin ===============================
    /*!
    * jQuery imagesLoaded plugin v1.1.0
    * http://github.com/desandro/imagesloaded
    *
    * MIT License. by Paul Irish et al.
    */


    // $('#my-container').imagesLoaded(myFunction)
    // or
    // $('img').imagesLoaded(myFunction)

    // execute a callback when all images have loaded.
    // needed because .load() doesn't work on cached images

    // callback function gets image collection as argument
    //  `this` is the container

    $.fn.imagesLoaded = function( callback ) {
        var $this = this,
            $images = $this.find('img').add( $this.filter('img') ),
            len = $images.length,
            blank = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==',
            loaded = [];

        function triggerCallback() {
            callback.call( $this, $images );
        }

        function imgLoaded( event ) {
            var img = event.target;
            if ( img.src !== blank && $.inArray( img, loaded ) === -1 ){
                loaded.push( img );
                if ( --len <= 0 ){
                  setTimeout( triggerCallback );
                  $images.unbind( '.imagesLoaded', imgLoaded );
                }
            }
        }

        // if no images, trigger immediately
        if ( !len ) {
            triggerCallback();
        }

        $images.bind( 'load.imagesLoaded error.imagesLoaded',  imgLoaded ).each( function() {
            // cached images don't fire load sometimes, so we reset src.
            var src = this.src;
            // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
            // data uri bypasses webkit log warning (thx doug jones)
            this.src = blank;
            this.src = src;
        });

        return $this;
    };


    $.Fite = function(options, element){
        this.element = $( element );
        this.init(options);
    }

    $.Fite.settings = {
        containerStyle: {
            position: 'relative',
            overflow: 'hidden'
        },
        responsive: false,
        minwidth: 0,
        duration: 600,
        fadein: 4000,
        marginx: 25,
        marginy: 0
    }
    $.Fite.prototype = {
        init: function(options){
            this.options = $.extend( {}, $.Fite.settings, options );
            this.styleQueue = [];
            this.styleOriginal = [];
            this.instant = false;
            this.offset = {
                left: parseInt( ( this.element.css('padding-left') || 0 ), 10 ),
                top: parseInt( ( this.element.css('padding-top') || 0 ), 10 )
            };
            
            this.element.css(this.options.containerStyle);

            var self = this;
            $(window).on('smartresize.fite', function(){
                self.reposition();
            });
            self.reposition();

        },
        destroy : function( ) {
            $(window).unbind('.fite');
        },
        reposition : function( ) {
        
            var mainWidth = this.element.width(),
                position = (mainWidth > this.options.minwidth) ? 'absolute' : 'relative';
                props = {
                    x : 0,
                    y : 0,
                    height: 0
                };

            

            this.childs = this.element.children();
            var self = this;
            this.childs.each(function(index, child){
                if (self.options.responsive){
                    self.styleOriginal.push({ $el: $(child), width: $(child).width(), height: $(child).height() });
                }
                $(child).css('position','absolute');

            })

            this.childs.each( function(index, element) {
                var $e = $(element), 
                    h = 0, 
                    w = 0;
                if ($e[0] != $('#infscr-loading')[0]){
                    
                    if (self.options.responsive){

                        var original = self.getOriginalStyle($e),
                            ratio = 1;

                        if (position === 'relative'){
                            ratio = (original.width > self.options.minwidth) ? Math.min(ratio, self.options.minwidth/original.width) : 1;
                            w = Math.floor(original.width*ratio);
                            h = Math.floor(original.height*ratio);
                            self.pushStyle($e, { width: w, height: h });
                        } else { 
                            ratio = (original.width > self.options.minwidth) ? Math.min(ratio, mainWidth/original.width) : 1;   
                            w = Math.floor(original.width*ratio);
                            h = Math.floor(original.height*ratio);
                            self.pushStyle($e, { width: w, height: h });
                        }
                        
                    } else {
                        h = $e.height(), 
                        w = $e.width();
                    }
                    
                    if (position === 'relative'){
                        props.x = 0;
                        props.y = props.height;
                    } else {
                        if ( props.x !== 0 && w + props.x > mainWidth ) {
                            props.x = 0;
                            props.y = props.height;
                        }
                    }
                    self.pushElement( $e, props.x, props.y );
                    props.height = Math.max( props.y + h + self.options.marginy, props.height );
                    props.x += w + self.options.marginx;
                }
            });
            
            this.pushStyle(this.element, { height: props.height });
            this.update();
            
        },
        redraw: function(){
            this.childs = this.element.children();
            this.instant = true;
            this.reposition();
        },
        pushStyle : function($e, style){
            var stack=this.styleQueue, 
                l = stack.length,
                found = false;

            while (l--){
                if (stack[l].$el[0] == $e[0]){
                    found = true;
                    stack[l].style = $.extend( {}, stack[l].style, style );
                    break;
                }
            }
            if (!found){
                this.styleQueue.push({ $el: $e, style: style });
            }
        },
        pushElement : function( $e, x, y ) {
            x = Math.round( x + this.offset.left );
            y = Math.round( y + this.offset.top );
            var position = this.getPositionStyles( x, y );
            this.pushStyle($e, position);
        },
        getOriginalStyle : function (element) {
            var stack=this.styleOriginal, 
                l = stack.length,
                target =  null;
            while (l--){
                if (stack[l].$el[0] == element[0]){
                    target = stack[l]; 
                    return target;
                }
            }
            return false;
        },
        getPositionStyles : function( x, y ) {
            return { left: x, top: y };
        },
        update : function() { 
            var self = this;
            $.each(this.styleQueue, function(index, element) {
                var el = element.$el;
                if (el.is(":hidden")){
                    el.css("opacity", 0).show().end();
                }
                if (self.instant){
                    el.css(element.style);
                    el.animate({'opacity': 100}, self.options.fadein);
                } else {
                    element.style.opacity = 100;
                    el.animate(element.style, self.options.duration);
                }
            })
            this.instant = false;
            this.styleQueue = [];
        }
    }

    $.fn.fite = function( options ) {
        if ( typeof options === 'string' ) {
            // call method
            var args = Array.prototype.slice.call( arguments, 1 );
            return this.each(function(){
                var instance = $.data( this, 'fite' );
                instance[ options ].apply( instance, args );
            });
        } else {
            return this.each(function() {
              // initialize new instance
              $.data( this, 'fite', new $.Fite( options, this ) );
            });
        }
    }
  

})( jQuery );