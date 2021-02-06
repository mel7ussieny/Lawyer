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

})