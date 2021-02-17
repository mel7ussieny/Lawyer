$(document).ready(function(){

    $('[placeholder]').focus(function(){
        $(this).attr('data-holder',$(this).attr('placeholder'));
        $(this).removeAttr("placeholder");
    }).blur(function(){
        $(this).attr("placeholder",$(this).attr("data-holder"))
    })

    /* Menubar */
        // Toggle Items inside the menubar
    $('.dashboard-icon').on('click',function(){
       $('.menu-item').toggle("slide",{direction: "right"},400);
       $("body").css("width",100);
    })
            // mouse hover on items 
    $('.menubar ul li').hover(function(){
        $(this).css("backgroundColor","#E3093F")
    }).on("mouseleave",function(){
        $(this).css("backgroundColor","Transparent")
    })

    
    /* Clients Page */
    // Add more than input field
   $('.add-phone').on("click",function(){
       $(this).parent().append($('<input type="text" name="phones[]" class="form-control" placeholder="رقم الهاتف" autocomplete="off" required="">'));
   })
   $('.add-file').on("click",function(){
    $(this).parent().append($('<input type="file" class="form-control" name="files[]" required>'));
})

$('.myImg').on("click",function(){
    $(this).parent().children(".modal").css("display","block");
    $('.modal-content').attr("src",$(this).attr("src"));
    $(this).parent(".modal .caption").text($(this).attr("alt"));
})
// Close the pic with span
$(".modal span").on("click",function(){
    $(this).parent().css("display","none");
})

    // SELECT BOX
    $('select').selectize({
        create: true,
    });

    // Tags for input
    $('#input-tags').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });


    // ViewPage show dispute details
    $('.dispute-header .title').on("click",function(){
        $(this).parent().parent().children(".content").slideToggle();
    })


    // advanced search
        // Toggle The Slider
    $(".search-advanced .advanced i").on("click",function(){
        $(".search-types").toggleClass("active");
        if(!$(".search-types").hasClass("active")){
            $(".search-types").slideUp();
            $(this).removeClass();
            $(this).addClass('fas fa-caret-left');
        }else{
            $(".search-types").slideDown();
            $(this).removeClass();
            $(this).addClass('fas fa-caret-down');

        }
    })

   $('.search-types ul li input[type="radio"]').on("click",function(){
        $('.search-dispute').attr("placeholder",$(this).attr("custom-display"));
        $('.search-dispute').val("");
        $('.searchwith').attr("value",$(this).attr("value"));
        
    })



    // Data Picker
    $('.date-picker-exchange').pickadate({
        monthsFull: [ 'يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر' ],
        monthsShort: [ 'يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر' ],
        weekdaysFull: [ 'الاحد', 'الاثنين', 'الثلاثاء', 'الاربعاء', 'الخميس', 'الجمعة', 'السبت' ],
        weekdaysShort: [ 'الاحد', 'الاثنين', 'الثلاثاء', 'الاربعاء', 'الخميس', 'الجمعة', 'السبت' ],
        today: 'اليوم',
        clear: 'مسح',
        selectYears: true,
        formatSubmit: 'yyyy-mm-dd',
        hiddenPrefix: 'prefix__',
        hiddenSuffix: '__suffix'
    });
    

})