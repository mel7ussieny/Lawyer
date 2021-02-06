$(document).ready(function(){
    $('.add-bar').addClass("fas fa-bars");
    $('.search-bar').on("keyup",function(){
        document.getElementById('clients').innerHTML = '';
        let search = $(this).val();
        let request = new XMLHttpRequest();
        request.open("GET","search.php?search=on&type=name&q="+search);
        request.setRequestHeader('content-type','application/json; charset=utf-8');
        request.send();
        request.onload = function(){
            let arr = JSON.parse(request.responseText);
                arr.forEach(element => {

                    var html = `<div class="client arabicFont">
                    <i class="fas fa-bars"></i>
                    <span class="name">${element[3]}</span>
                    <div class="content">
                    <ul>'
                    <li>العنوان : ${element[1]}</li>
                    <li>رقم التوكيل : ${element[2]}</li>
                        </ul>
                        <a href="view.php?id=${element[0]}">إظهار الملف كامل</a>

                    </div>
                </div>`;

                $('.clients').append(html);

            });
            $('.client > i').on("click",function(){
                $(this).parent().find(".content").slideToggle();
            })
            
        }
    })

})