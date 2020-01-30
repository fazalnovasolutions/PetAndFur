$(document).ready(function(){

    /////////////////Search order Feild/////////////


    $(".search_field").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });



    ///////////////////filter order status///////////////

    // $('.order_status').click(function () {
    $('body').on('click','.order_status',function () {
        var value=$(this).text();
        $('.order_status_button').text(value);
        value= value.split(" ");

       if($(this).text() !== 'All Orders'){
           $("#myTable tr td .dropdown span").filter(function() {
               var dropdown=$(this).parent();
               var td=$(dropdown).parent();
               $($(td).parent()).toggle($(this).text().toLowerCase().indexOf(value[0].toLowerCase()) > -1)
           });
       }
       else{
           $("#myTable tr").each(function() {
               $(this).show();
           });
       }


    });
    ///////////change order status//////////////////


    // $(".change_status").click(function () {
    $('body').on('click','.change_status',function () {
        var text=$(this).text();
        text=text.split(" ");

        var drop_menu=$(this).parent();
        drop_menu=$(drop_menu).parent();
        var td =$(drop_menu).parent();

        drop_menu=$(drop_menu)[0].children
        var span =$(drop_menu)[0];

        if(text[0] == "New"){
            $(span).text(text[0])
            td.css("background","#0066CC");
            td.closest('tr').css("background","#FFFFFF");
            td.closest('tr').css("color","#67757c")

        }else if (text[0] == "In-process") {
            $(span).text(text[0])
            td.css("background","#a53838");
            td.closest('tr').css("background","#FFFFFF");
            td.closest('tr').css("color","#67757c")

        }else if(text[0] == "Completed" ){
            $(span).text(text[0])
            td.css("background","#449d44");
            td.closest('tr').css("background","#c8bfdf");
            td.closest('tr').css("color","White")
        }
    })


    ////////filter designer//////////////

    // $(".change_designer").click(function () {
    $('body').on('click','.change_designer',function () {
        var value=$(this).text();
        $('.designer-text').text(value);
        if(value !== 'All Designers'){
            $("#myTable tr td .designer").filter(function() {
                var td=$(this).parent();
                $($(td).parent()).toggle($(this).text().toLowerCase().indexOf(value.toLowerCase()) > -1)
            });
        }
        else{
            $("#myTable tr").each(function() {
               $(this).show();
            });

        }

    });


    ////////////filter of columns////////////


    // $('.icon_down').click(function () {
    $('body').on('click','.icon_down',function () {

        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("order_table");
        if($(this).hasClass('mdi-menu-down')){
            $(this).removeClass('mdi-menu-down');
            $(this).addClass('mdi-menu-up');
        }else if($(this).hasClass('mdi-menu-up')){
            $(this).removeClass('mdi-menu-up');
            $(this).addClass('mdi-menu-down');
        }

        var n=$(this).prop("id");
        switching = true;
        //Set the sorting direction to ascending:
        dir = "asc";
        /*Make a loop that will continue until
        no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /*Loop through all table rows (except the
            first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                one from current row and one from the next:*/
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /*check if the two rows should switch place,
                based on the direction, asc or desc:*/
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch= true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                //Each time a switch is done, increase this count by 1:
                switchcount ++;
            } else {
                /*If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again.*/
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    })





    /////////////mark orders all //////////////////
    $('.actionbox').hide();

    // $('#check-all').click(function () {
    $('body').on('click','#check-all',function () {
        if (this.checked) {
            $(".checkSingle").each(function() {
                this.checked=true;

            });

            var countchecked = $("table input[type=checkbox]:checked").length;
            countchecked = countchecked - 1;
            $('.actionbox').show();
            // $('#selecteditems').text(countchecked +' Items Selected');

        } else {
            // $('#selecteditems').text('');

            $(".checkSingle").each(function() {
                this.checked=false;
                $('.actionbox').hide();

            });
        }

    })

    ///////////////single order check///////////

    // $(".checkSingle").click(function () {
    $('body').on('click','.checkSingle',function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;

            $(".checkSingle").each(function() {
                if (!this.checked)
                    isAllChecked = 1;

            });

            if (isAllChecked == 0) {
                $("#check-all").prop("checked", true);

            }
        }
        else {
            $("#check-all").prop("checked", false);
            // $('#actionbox').hide();
        }
    });

    /*Filter Orders*/
    $('body').on('keyup','.filter-search',function () {

        $.ajax({
            url: $(this).data('route'),
            method: 'GET',
            data:{
                search : $(this).val(),
            },
            success:function (response){
                $('#order_table').find('#myTable').empty();
                $('#order_table').find('#myTable').html($(response).find('#myTable').html());
            },
        });

    });
    $('body').on('click','.change_product',function () {
        $('.product-filter-button').text($(this).text());
        if($(this).text() === 'All Products'){
            var $product = null;
        }
        else{
            var $product = $(this).text();
        }

        $.ajax({
            url: $(this).data('route'),
            method: 'GET',
            data:{
                product : $product,
            },
            success:function (response){
                $('#order_table').find('#myTable').empty();
                $('#order_table').find('#myTable').html($(response).find('#myTable').html());
            },
        });
    });

    /*Filter Asc Desc Column*/
    // $('body').on('click','.th-table',function () {
    //
    // });


});
