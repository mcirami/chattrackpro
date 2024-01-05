function checkBoxesInDiv(divID) {

    $("#" + divID).find('input[type=checkbox]').each(function () {
        this.checked = true;
    });

}


function unCheckBoxesInDiv(divID) {

    $("#" + divID).find('input[type=checkbox]').each(function () {
        this.checked = false;
    });

}

function adminLogin(id) {
    window.open('/login/' + id);
}

jQuery(document).ready(function ($) {
 /*   var $trigger = $(".dropdown");


    // Show hide popover
    $(".dropdown > a").click(function (e) {
        e.preventDefault();

        if (!$(this).parent().find(".dropdown-menu").hasClass('open')) {
            $(this).parent().find(".dropdown-menu").slideDown(400).addClass('open');
        } else {
            $(this).parent().find(".dropdown-menu").slideUp(400).removeClass('open');
        }


    });
*/
    /* $(".dropdown").on("click", function(event){
          if($trigger !== event.target && !$trigger.has(event.target).length){
              $(".dropdown-menu").slideUp("fast");
          }
      });
   */
/*
    var activeDropdown = $(".dropdown-menu li").find('.active');

    $(activeDropdown).parent().parent().css('display', 'block').addClass('open');
*/

    $('#default').click(function (e) {
        e.preventDefault();

        $('#valueSpan1').val("484848");
        $('#valueSpan2').val("FFFFFF");
        $('#valueSpan3').val("2A58AD");
        $('#valueSpan4').val("1D4C9E");
        $('#valueSpan5').val("82A7EB");
        $('#valueSpan6').val("FCED16");
        $('#valueSpan7').val("EAEEF1");
        $('#valueSpan8').val("FFFFFF");
        $('#valueSpan9').val("404452");
        $('#valueSpan10').val("999999");
    });

    $('.open_popup').click(function (e) {
        e.preventDefault();
        var popupLink = $(this).attr("href");
        var popup = $('#popup');

        $.ajax({
            url: popupLink,
            success: function (data) {
                popup.addClass("show").html(data);
                $('body').addClass('popup_open');
                $('.popup_wrapper').addClass('magictime spaceInUp');
            }
        });
    });

    $(document).on('click', '.popup_close', function (e) {
        e.preventDefault();
        $('.popup_wrapper').addClass('magictime spaceOutDown');
        window.setTimeout(function () {
                $('#popup').removeClass('show').empty()
                $('body').removeClass('popup_open');
            }
            , 800);


    });

    var notifShowing = false;

    $('#notif_icon').click(function (e) {
        $('#notification_box').toggleClass('open');
    });

    let tabsContainer = document.querySelector("#tabs");
    if(tabsContainer) {
        let tabTogglers = tabsContainer.querySelectorAll("#tabs a");
        tabTogglers.forEach(function(toggler) {
            toggler.addEventListener("click", function(e) {
                e.preventDefault();

                let tabName = this.getAttribute("href");

                let tabContents = document.querySelector("#tab-contents");

                for (let i = 0; i < tabContents.children.length; i++) {

                    tabTogglers[i].parentElement.classList.remove("border-t",
                        "border-r", "border-l", "-mb-px", "value_span6-1");
                    tabTogglers[i].classList.remove("value_span2");
                    tabContents.children[i].classList.remove("hidden");
                    if ("#" + tabContents.children[i].id === tabName) {
                        continue;
                    }
                    tabContents.children[i].classList.add("hidden");

                }

                e.target.parentElement.classList.add("border-t", "border-r",
                    "border-l", "-mb-px", "value_span6-1");
                e.target.classList.add("value_span2");
            });
        });
    }

});