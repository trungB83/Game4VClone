// No Sidebar Full Content Blog
jQuery(function ($) { 
	if ( $(".sidebar-right-blog").parents("#content").length == 0 && $(".sidebar-left-blog").parents("#content").length == 0)   { 
	   $("body").addClass("null-blog");
	} else {	 
	   $("body").removeClass("null-blog");
	}
});

// Sidebar Content Sidebar Blog
jQuery(function ($) { 
	if ( $(".sidebar-right-blog").parents("#content").length == 1 && $(".sidebar-left-blog").parents("#content").length == 1 )     { 
	   $("body").addClass("sidebar-content-sidebar-blog blog");
	} else {	 
	   $("body").removeClass("sidebar-content-sidebar-blog");
	}
});
// Content Sidebar Blog
jQuery(function ($) { 
	if ( $(".sidebar-right-blog").parents("#content").length == 1 && $(".sidebar-left-blog").parents("#content").length == 0 )   { 
	   $("body").addClass("content-sidebar-blog blog");
	} else {	 
	   $("body").removeClass("content-sidebar-blog");
	}
});
//  Sidebar Content Blog
jQuery(function ($) { 
	if ( $(".sidebar-right-blog").parents("#content").length == 0 && $(".sidebar-left-blog").parents("#content").length == 1)   { 
	   $("body").addClass("sidebar-content-blog blog");
	} else {	 
	   $("body").removeClass("sidebar-content-blog");
	}
});

// No Sidebar Full Content ws
jQuery(function ($) { 
	if ( $(".sidebar-right-ws").parents("#content").length == 0 && $(".sidebar-left-ws").parents("#content").length == 0)   { 
	   $("body.woocommerce").addClass("null-ws");
	} else {	 
	   $("body.woocommerce").removeClass("null-ws");
	}
});
// Sidebar Content Sidebar ws
jQuery(function ($) { 
	if ( $(".sidebar-right-ws").parents("#content").length == 1 && $(".sidebar-left-ws").parents("#content").length == 1)   { 
	   $("body").addClass("sidebar-content-sidebar-ws");
	} else {	 
	   $("body").removeClass("sidebar-content-sidebar-ws");
	}
});
// Content Sidebar ws
jQuery(function ($) { 
	if ( $(".sidebar-right-ws").parents("#content").length == 1 && $(".sidebar-left-ws").parents("#content").length == 0)   { 
	   $("body").addClass("content-sidebar-ws");
	} else {	 
	   $("body").removeClass("content-sidebar-ws");
	}
});
//  Sidebar Content ws
jQuery(function ($) { 
	if ( $(".sidebar-right-ws").parents("#content").length == 0 && $(".sidebar-left-ws").parents("#content").length == 1)   { 
	   $("body").addClass("sidebar-content-ws");
	} else {	 
	   $("body").removeClass("sidebar-content-ws");
	}
});
jQuery(function ($) { 
	 $("body.woocommerce").removeClass("sidebar-content-sidebar-blog");
	 $("body.woocommerce").removeClass("content-sidebar-blog");
	 $("body.woocommerce").removeClass("sidebar-content-blog");

	 $("body.category").removeClass("sidebar-content-sidebar-ws");
	 $("body.category").removeClass("content-sidebar-ws");
	 $("body.category").removeClass("sidebar-content-ws");
	 $("body.single-post").removeClass("sidebar-content-sidebar-ws");
	 $("body.single-post").removeClass("content-sidebar-blog");
	 $("body.single-post").removeClass("sidebar-content-blog");
});
// No sidebar full content 
jQuery(function ($) { 
	if ( $(".sidebar-right-blog").parents("#content").length == 0 && $(".sidebar-left-blog").parents("#content").length == 0  && $(".sidebar-right-ws").parents("#content").length == 0 && $(".sidebar-left-ws").parents("#content").length == 0)   { 
	   $("body").addClass("full-content");
	} else {	 
	   $("body").removeClass("full-content");
	}
});
jQuery(function ($) { 
	$("body").addClass("full-content");
});
jQuery(function ($) { 
	if ($("body").hasClass("archive"))    { 
	    $("body").removeClass("full-content");
	} 
});
jQuery(function ($) { 
	if ($("body").hasClass("single-post"))    { 
	    $("body").removeClass("full-content");
	}
});
jQuery(function ($) { 
	if ($("body").hasClass("woocommerce"))    { 
	    $("body").removeClass("full-content");
	}
});
//*** Sticky Menu
jQuery(function($){
  $(window).scroll(function() {
    var winTop = $(window).scrollTop();
    if (winTop >= 1) { 
      $(".site-header").addClass("is-sticky");
    } else {
      $(".site-header").removeClass("is-sticky");
    }
  })
})

/*** LIGHTBOX Video ***/

jQuery(function($){

    // Lightbox Triggers
    $(".open-video-link").videoBox();
});
/* ========================================================================= */
/* FUNCTION TO CREATE LIGHTBOX */
/* ========================================================================= */

jQuery.fn.extend({
    videoBox : function() {
        var self, link, target, video, videoSrc, toggle;
        this.each(function() {
            self = this;
            target = $(self).attr("href");
            video = $(target).find(".popup-video iframe");
            videoSrc = $(video).attr("src");
          
            $(this).on("click", function(event) {
                event.preventDefault ? event.preventDefault() : event.returnValue = false;
                $(target).wrap( "<div class='lightbox'></div>" );
                $(".lightbox").fadeIn(300, function() {
                    $(target).fadeIn(0);
                    $("body").addClass("modal-open");
                    $(video).attr("src",videoSrc+'?autoplay=1');
                    resizeIfame(video);
                });

                $("body").on("click", function(event) {
                    if(($(event.target).hasClass("lightbox") || $(event.target).hasClass("close")) && $(target).parent().hasClass("lightbox") ) {
                        $(".lightbox").fadeOut(300, function() {
                            $(target).hide(0);
                            $(target).unwrap();
                        });
                        $("body").removeClass("modal-open");
                        $(video).attr("src",videoSrc);
                    }
                });
            });
        });
    }
});


/* ========================================================================= */
/* RESIZE IFRAME VIDEO FUNCTION */
/* ========================================================================= */
function resizeIfame(frame) {

    var oldWidth = $(frame).width();
    var oldHeight = $(frame).height();
    var propotion = oldHeight / oldWidth;
    var newHeight;

    $(frame).width('100%');
    newHeight = $(frame).width() * propotion;
    $(frame).height(newHeight);

    $(window).resize(function() {
        $(frame).width('100%');
        newHeight = $(frame).width() * propotion;
        $(frame).height(newHeight);
    });
}



// JS written by Themetide

jQuery(document).ready(function($){

	//set animation timing
	var animationDelay = 2500,
		//loading bar effect
		barAnimationDelay = 3800,
		barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
		//letters effect
		lettersDelay = 50,
		//type effect
		typeLettersDelay = 150,
		selectionDuration = 500,
		typeAnimationDelay = selectionDuration + 800,
		//clip effect 
		revealDuration = 600,
		revealAnimationDelay = 1500,
  	stopAnimation = false;
	
	initHeadline();
	

	function initHeadline() {
		//insert <i> element for each letter of a changing word
		singleLetters($('.cd-headline.letters').find('b'));
		//initialise headline animation
		animateHeadline($('.cd-headline'));
	}

	function singleLetters($words) {
		$words.each(function(){
			var word = $(this),
				letters = word.text().split(''),
				selected = word.hasClass('is-visible');
			for (i in letters) {
				if(word.parents('.rotate-2').length > 0) letters[i] = '<em>' + letters[i] + '</em>';
				letters[i] = (selected) ? '<i class="in">' + letters[i] + '</i>': '<i>' + letters[i] + '</i>';
			}
		    var newLetters = letters.join('');
		    word.html(newLetters);
		});
	}

	function animateHeadline($headlines) {
		var duration = animationDelay;
		$headlines.each(function(){
			var headline = $(this);
			
			if(headline.hasClass('loading-bar')) {
				duration = barAnimationDelay;
				setTimeout(function(){ headline.find('.cd-words-wrapper').addClass('is-loading') }, barWaiting);
			} else if (headline.hasClass('clip')){
				var spanWrapper = headline.find('.cd-words-wrapper'),
					newWidth = spanWrapper.width() + 10
				spanWrapper.css('width', newWidth);
			} else if (!headline.hasClass('type') ) {
				//assign to .cd-words-wrapper the width of its longest word
				var words = headline.find('.cd-words-wrapper b'),
					width = 0;
				words.each(function(){
					var wordWidth = $(this).width();
				    if (wordWidth > width) width = wordWidth;
				});
				headline.find('.cd-words-wrapper').css('width', width);
			};

			//trigger animation
			setTimeout(function(){ hideWord( headline.find('.is-visible').eq(0) ) }, duration);
		});
	}

	function hideWord($word) {
		var nextWord = takeNext($word);
		
    if(stopAnimation){
      return false;
    }
    
		if($word.parents('.cd-headline').hasClass('type')) {
			var parentSpan = $word.parent('.cd-words-wrapper');
			parentSpan.addClass('selected').removeClass('waiting');	
			setTimeout(function(){ 
				parentSpan.removeClass('selected'); 
				$word.removeClass('is-visible').addClass('is-hidden').children('i').removeClass('in').addClass('out');
			}, selectionDuration);
			setTimeout(function(){ showWord(nextWord, typeLettersDelay) }, typeAnimationDelay);
		
		} else if($word.parents('.cd-headline').hasClass('letters')) {
			var bool = ($word.children('i').length >= nextWord.children('i').length) ? true : false;
			hideLetter($word.find('i').eq(0), $word, bool, lettersDelay);
			showLetter(nextWord.find('i').eq(0), nextWord, bool, lettersDelay);

		}  else if($word.parents('.cd-headline').hasClass('clip')) {
			$word.parents('.cd-words-wrapper').animate({ width : '2px' }, revealDuration, function(){
				switchWord($word, nextWord);
				showWord(nextWord);
			});

		} else if ($word.parents('.cd-headline').hasClass('loading-bar')){
			$word.parents('.cd-words-wrapper').removeClass('is-loading');
			switchWord($word, nextWord);
			setTimeout(function(){ hideWord(nextWord) }, barAnimationDelay);
			setTimeout(function(){ $word.parents('.cd-words-wrapper').addClass('is-loading') }, barWaiting);

		} else {
			switchWord($word, nextWord);
			setTimeout(function(){ hideWord(nextWord) }, animationDelay);
		}
	}

	function showWord($word, $duration) {
		if($word.parents('.cd-headline').hasClass('type')) {
			showLetter($word.find('i').eq(0), $word, false, $duration);
			$word.addClass('is-visible').removeClass('is-hidden');

		}  else if($word.parents('.cd-headline').hasClass('clip')) {
			$word.parents('.cd-words-wrapper').animate({ 'width' : $word.width() + 10 }, revealDuration, function(){ 
				setTimeout(function(){ hideWord($word) }, revealAnimationDelay); 
			});
		}
	}

	function hideLetter($letter, $word, $bool, $duration) {
		$letter.removeClass('in').addClass('out');
		
		if(!$letter.is(':last-child')) {
		 	setTimeout(function(){ hideLetter($letter.next(), $word, $bool, $duration); }, $duration);  
		} else if($bool) { 
		 	setTimeout(function(){ hideWord(takeNext($word)) }, animationDelay);
		}

		if($letter.is(':last-child') && $('html').hasClass('no-csstransitions')) {
			var nextWord = takeNext($word);
			switchWord($word, nextWord);
		} 
	}

	function showLetter($letter, $word, $bool, $duration) {
		$letter.addClass('in').removeClass('out');
		
		if(!$letter.is(':last-child')) { 
			setTimeout(function(){ showLetter($letter.next(), $word, $bool, $duration); }, $duration); 
		} else { 
			if($word.parents('.cd-headline').hasClass('type')) { setTimeout(function(){ $word.parents('.cd-words-wrapper').addClass('waiting'); }, 200);}
			if(!$bool) { setTimeout(function(){ hideWord($word) }, animationDelay) }
		}
	}

	function takeNext($word) {
		return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
	}

	function takePrev($word) {
		return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
	}

	function switchWord($oldWord, $newWord) {
		$oldWord.removeClass('is-visible').addClass('is-hidden');
		$newWord.removeClass('is-hidden').addClass('is-visible');
	}
  
  var icon = $('#player');
  icon.click(function() {
    if(icon.hasClass("on")){
    	icon.removeClass("on icon-pause-circle-fill").addClass("off icon-play-circle-fill");
      stopAnimation = true;
    } else {
      icon.removeClass("off icon-play-circle-fill").addClass("on icon-pause-circle-fill");
      stopAnimation = false;
      animationDelay = 1000;
      initHeadline();
    }
    return false;
  });
  
});

