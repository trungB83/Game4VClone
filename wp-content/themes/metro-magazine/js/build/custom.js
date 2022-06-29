jQuery(document).ready(function($) {

    /* Equal Height */
    $('.section-two.top-news .post').matchHeight({
        byRow: true,
        property: 'height',
        target: null,
        remove: false
    });


    // Get the modal
    var modal = document.getElementById('formModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    };

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    //mobile-menu
    $('.menu-opener').click(function() {
        $('body').addClass('menu-open');
    });

    $('<button class="angle-down"></button>').insertAfter($('.mobile-menu  ul .menu-item-has-children > a'));
    $('.mobile-menu ul li .angle-down').click(function() {
        $(this).next().slideToggle();
        $(this).toggleClass('active');
    });

 
    // CustomJSfor Close button

    $('.close-main-nav-toggle').click(function(){
        $('body').toggleClass('menu-open');
    });

    $('.overlay').click(function() {
        $('body').removeClass('menu-open');
    });

    //CustomJS for close of search bar
    $(".close").click(function(){
        $('.modal').css ('display', 'none');
    });

    $('.mobile-header .search-icon .search-btn').click(function() {
        $('.mobile-header .search-icon .header-searh-wrap').show();
    });

    $('.mobile-header .header-searh-wrap .btn-form-close').click(function(){
        $('.header-searh-wrap').hide();
    });

    $('<button class = "angle-down"> </button>').insertAfter($());

    //accessible menu in IE
    $("#site-navigation ul li a").focus(function() {
        $(this).parents("li").addClass("focus");
    }).blur(function() {
        $(this).parents("li").removeClass("focus");
    });

    $(".secondary-menu ul li a").focus(function() {
        $(this).parents("li").addClass("focus");
    }).blur(function() {
        $(this).parents("li").removeClass("focus");
    });

});

