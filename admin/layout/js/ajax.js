$(document).ready(function(){
    $('.add-bar').addClass("fas fa-bars");
    // Send request to get clients
    $('.search-bar').on("keyup",function(){
        document.getElementById('clients').innerHTML = '';
        let search = $(this).val();
        if(search.length > 0){
            let request = new XMLHttpRequest();
            request.open("GET","search.php?required=search-clients&search=on&type=name&q="+search);
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
        }

    })

    // Send request to get disputes
    $('.search-dispute').on("keyup",function(){
        document.getElementById('disputes').innerHTML = '';
        let search = $(this).val();
        let type = $('.searchwith').attr("value");
        if(search.length > 0){
            let request = new XMLHttpRequest();
            request.open("GET","search.php?required=search-disputes&type="+type+"&search=on&q="+search);
            request.setRequestHeader('content-type','application/json; charset=utf-8');
            request.send();
            request.onload = function(){
                let arr = JSON.parse(request.responseText);
                    arr.forEach(element => {

                        var html = `<div class="dispute-view arabicFont">
                        <i class="fas fa-bars"></i>
                        <span class="name">${element[1]}</span>
                        <div class="content">
                        <ul>
                            <li>رقم الدعوي : ${element[2]}</li>
                            <li>محكمة : ${element[3]}</li>
                            <li>العميل : ${element[4]}</li>
                            <li>الخصم : ${element[5]}</li>
                            <li>محامي الخصم : ${element[6]}</li>
                            <li>تاريخ : ${element[7]}</li>
                        </ul>
                            <a href="trace-dispute.php?action=view&dispute_id=${element[0]}">إظهار الملف كامل</a>
                        </div>
                    </div>`;

                    $('.disputes-view').append(html);

                });
                $('.dispute-view > i').on("click",function(){
                    $(this).parent().find(".content").slideToggle();
                })
                
            }
        }
    })

    $('.get-profit').on("click",function(){
        $(".statistics table tbody tr").remove();
        $(".statistics .total-profit span").remove();
        if($('input[name="prefix__date-start__suffix"]').val().length > 0 && $('input[name="prefix__date-start__suffix"]').val().length > 0){
            let d_start = $("input[name='prefix__date-start__suffix']").val();
            let d_end   = $("input[name='prefix__date-end__suffix']").val();
            let request = new XMLHttpRequest();
            request.open("GET","search.php?required=get-profit&d-start="+d_start+"&d-end="+d_end);
            request.setRequestHeader("content-type","application/json; charset=utf-8");
            request.send();
            request.onload = function(){
                let arr = JSON.parse(request.responseText);
                if(arr[0]["type"] != null){
                    arr.forEach(element => {

                        type = (element["type"] == 1 ) ? "أتعاب" : "مصروف";
                        let ref = (element["dispute_id"] != null) ? `trace-dispute.php?action=view&dispute_id=${element["dispute_id"]}` : "#";
                        let title = (element["title"] == null) ? "" : element["title"];
                        
                        let html = `
                            <tr>
                                <td>${element["date"]}</td>
                                <td>${type}</td>
                                <td>${element["amount"]}</td>
                                <td>${element["pay_for"]}</td>
                                <td><a href="${ref}">${title}</a></td>
                            </tr>
                        `;
                        $(".statistics table tbody").append(html);
                    })
                    let profit = `<span class='profit-amount'>${arr[0]["profit"]}ج</span>`;
                    $(".statistics .total-profit").append(profit);
                    // Style the profit
                    if(arr[0]["profit"] > 0){
                        $(".profit-amount").css("color","#00cd65");
                    }else if(arr[0]["profit"] == 0){
                        $(".profit-amount").css("color","#808080");
                    }else{
                        $(".profit-amount").css("color","#d9001e");
                    }
                }
            }
        }
    })
})