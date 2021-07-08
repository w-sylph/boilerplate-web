export const setup = {
    init() {
        let closeBtn = $('.user-profile-sidebar-close-btn');
        let sidebar = $('.user-profile-sidebar');
        $('.user-profile-sidebar-btn').on('click', () => {
            sidebar.addClass('active');
            closeBtn.addClass('active');
        });

        closeBtn.on('click', () => {
            sidebar.removeClass('active');
            closeBtn.removeClass('active');
        });



        var $window = $(window);
          $window.scroll(function() {

              if ($window.scrollTop() > 0) {
                  $('.public--hdr').addClass('theme--border');
                } else {
                  $('.public--hdr').removeClass('theme--border');
                }
          });



        // mycart side panel
        // $('.open--cart').click(function() {

        //  $('.mycart__side-panel').addClass('show');
        // });

        // // hide mycart side panel
        // $('.hide').click(function() {
        //  $('.mycart__side-panel').removeClass('show');
        // });


        // header search bar
        // $('.search--trigger').click(function() {
        //  $('.search--holder').toggleClass('show');
        //  $(this).toggleClass('fa-times');
        // });


        // mobile header
        $('.m-public--menu').click(function() {

            $('.m-side--menu').addClass('show');
        });

        // hide mycart side panel
        $('.close--menu').click(function() {
            $('.m-side--menu').removeClass('show');
        });


        // $('.show--password').click(function() {
        //  myFunction();
        //  $(this).toggleClass('show');
        // });


        // function myFunction() {
        //   var x = document.getElementById("password");
        //   if (x.type === "password") {
        //     x.type = "text";
        //   } else {
        //     x.type = "password";
        //   }
        // }


        // password info popup
        // $('.show--info').click(function() {
        //  $('.lgn--popup').addClass('show');


        //  setTimeout( function() {
        //      $('.lgn--popup').removeClass('show');
        //  }, 1500);
        // });

        // show verification input

        // $('.send--btn').click(function() {
        //  $('.verify--holder').addClass('show');
        //  countDown();
        // })

        // function countDown() {

        //  var counter = 59;
        //  var span;

        //      setInterval(function() {
        //      counter--;
        //      if (counter >= 0) {
        //        span = document.getElementById("count");
        //        span.innerHTML = counter;
        //      }
        //      // Display 'counter' wherever you want to display it.
        //      if (counter === 0) {
        //      //    alert('this is where it happens');
        //          clearInterval(counter);
        //      }

        //   }, 1000);

        // }

        // var controller = new ScrollMagic.Controller();

        // $(function () { // wait for document ready
        //     // build scene
        //     var scene = new ScrollMagic.Scene({triggerElement: "#pin2"})
        //                     .setPin("#pin2")
        //                     .addIndicators({name: "2 (duration: 0)"}) // add indicators (requires plugin)
        //                     .addTo(controller);
        // });


        // new ScrollMagic.Scene({triggerElement: ".related-productHolder"})
        // .setClassToggle("#pin2", "stop") // add class toggle
        // // .addIndicators() // add indicators (requires plugin)
        // .addTo(controller)


    }

};