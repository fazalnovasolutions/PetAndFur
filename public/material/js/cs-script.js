
$(document).ready(function() {

    $(".custom-slider").slick({
        infinite: true,
        centerMode: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        // arrows: true
    });

    $('body').on('click','.btn-choose',function () {
        $('.new_photo_input').trigger('click');
    });
    $('body').on('change','.new_photo_input',function (e) {
        var fileName = e.target.files[0].name;
       $(this).next().val(fileName);
    });
    $('body').on('click','.new_photo_modal_button',function () {
        var modal = $(this).data('target');
        if($(modal).length > 0){
            $(modal).find('.order_product_id').val($(this).data('product'));
            $(modal).modal({
                show: true,
                focus:true
            });
        }
    });
    $('body').on('click','.new_photo_upload_button',function () {
        if($(".new_photo_input").val() !== ''){
            $('#new_photo_upload_form').submit();
        }
        else{
            alert('Upload A File FIrst !')
        }
    });


    $('body').on('click','.request_upload_button',function () {
        if($(".request_fix").val().length > 0){
            $('#fix_request_form').submit();
        }
    });

    $('body').on('click','.background-div',function () {
        $('#design_background').attr('src',$(this).find('img').attr('src'));
        if($('#background-category').val() == ''){
            $('#background-category').val($(this).find('img').data('id'));
        }

    });

    $('body').on('click','.background_save_button',function () {
       $('#background_save_form').submit();
    });

    if(!$('.rating-stars').hasClass('disabled')){
        /* 1. Visualizing things on Hover - See next part for action on click */
        $('#stars li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

// Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });

        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });


        /* 2. Action to perform on click */
        $('#stars li').on('click', function(){
            $('#rating_input').val($(this).data('value'));
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected

            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
        });
    }



        $('body').on('click','.review-submit',function () {
        if($('#review_form').find('input[name=review]').val() !== ''){
            $('#review_form').submit();
        }
        else{
            alert('write review !');
        }

    });

    $('body').on('click','.set-approved',function(){
        var current = $(this);
        var form = $('#background_save_form');
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success:function (response) {
                $.ajax({
                    url:'/customer/order/save',
                    method:'get',
                    data:{
                        product : current.data('id'),
                    },
                    success:function (response) {
                        if(response.status !== 'error'){
                            var modal = current.data('target');
                            if($(modal).length > 0){
                                $(modal).modal({
                                    show: true,
                                    focus:true
                                });
                            }

                        }
                        else{
                            alert(response.status);
                        }
                    },

                });
            },
        });


    });

});
