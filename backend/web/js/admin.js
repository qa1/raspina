$(document).ready(function(){

    if(controller_name == 'site' && action_name == 'changepassword')
    {
        $('#m-' + controller_name).addClass('active').addClass('treeview');
    }

    if(controller_name != 'site')
    {
        $('#m-' + controller_name).addClass('active').addClass('treeview');
    }

    var post_status = $('#post-status').val();
    if(post_status && post_status == 2)
    {
        $('#status_2_datepicker').show();
    }

    $('#post-status').change(function(){
       var value = $(this).val();
       if(value == 2)
       {
            $('#status_2_datepicker').show();
       }
        else
       {
           $('#status_2_datepicker').hide();
       }
    });

    $('.sidebar-toggle').click(function(){
        var id = $('.title-mini-create').css('display');

        if(id == 'none')
        {
            $('.title-mini-create').show();
            $('.title-lg-create').hide();
        }
        else
        {
            $('.title-mini-create').fadeOut('slow',function(){
                $('.title-lg-create').fadeIn('slow');
            });

        }
    });

    $('.fa-bulk-button').click(function(){
        var value = $('.fa-bulk-dropdown').val();
        if(value == 'delete' && !confirm('آیا اطمینان به حذف موارد انتخابی را دارید؟'))
        {
            return false;
        }
    });

    $('.upload-box-link').click(function(){
        $(this).select();
    })

});