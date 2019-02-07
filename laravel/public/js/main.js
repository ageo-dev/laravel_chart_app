'use strict';
$(function () {
    $('.del_btn').click(function () {
        if (confirm('Deleted data can not be restored. \nDo you want to delete it?')) {
            $('#form_' + this.dataset.id).submit();
        }
    });

    $('.del_array_check_btn').click(function(){
        if($('.del_array_check_btn').text() == 'Select all'){
            $('.del_array_check_btn').text('Release all');
            $(".del_array").each(function(){
                $('#'+this.id).prop('checked', true);
            });
        }else{
            $('.del_array_check_btn').text('Select all');
            $(".del_array").each(function(){
                $('#'+this.id).prop('checked', false);
            });
        }
    });

    $('.del_array_btn').click(function () {
        let data = [];
        for (let i = 0; i < $('.del_array').length; i++) {
            if ($('.del_array').eq(i).prop('checked')) {
                data.push($('.del_array').eq(i).data().id);
            }
        }
        if(data.length >0){
            if (confirm(data.length + 'data in total. \nDo you want to delete it?')) {
                $('#form_data_array').val(data);
                $('#form_del_array').submit();
            }
        }
    });

    let startDate = $('input[name="start_date"]').val();
    let firstFund = $('input[name="first_fund"]').val();

    $('#prof_edit_btn').click(function(){
        let nowStartDate = $('input[name="start_date"]').val();
        let nowFirstFund = $('input[name="first_fund"]').val();

        if(startDate != nowStartDate){
            if(confirm('Changing the operation start date will overwrite the past chart, will it be OK?\n\n* It is not possible to restore past charts after rewriting.')){
                $('#prof_edit').submit();
            }
        }
        else if(firstFund != nowFirstFund){
            if(confirm('Changing the initial investment will overwrite the past chart, will it be okay?\n\n* It is not possible to restore past charts after rewriting.')){
                $('#prof_edit').submit();
            }
        }
        else{
            $('#prof_edit').submit();
        }
    });

    $('#fund_reset_btn').click(function (e) { 
        if(confirm('I will return the investment situation to the initial investment state, is it OK?\n\n* Charts can be rewritten.\n* It is not possible to restore past charts after rewriting.')){
            $('#fund_reset_form').submit();
        }
    });
    
    $('.chart_change_btn_l').click(function(){
        $('#chart_type').val('circle');
        $('#chart_form').submit();
    });
        
    $('.chart_change_btn_r').click(function(){
        $('#chart_type').val('bar');
        $('#chart_form').submit();
    });

    $('#user_search_btn').click(function () { 
        $('input[name="create_date_s"]').val(
            $('input[name="create_date_s"]').val().replace('年','-').replace('月','-').replace('日','')
        );
        $('input[name="create_date_m"]').val(
            $('input[name="create_date_m"]').val().replace('年','-').replace('月','-').replace('日','')
        );
        $('input[name="start_date_s"]').val(
            $('input[name="start_date_s"]').val().replace('年','-').replace('月','-').replace('日','')
        );
        $('input[name="start_date_m"]').val(
            $('input[name="start_date_m"]').val().replace('年','-').replace('月','-').replace('日','')
        );

        $('#user_search_form').submit();
    });

    $('#bulk_send_btn').click(function(){
        if(confirm('If you open another page during batch processing, processing will be interrupted.\nPlease wait until processing is complete since there is a possibility that it may cause an error.\n\nWould you like to start processing?')){
            $('#bulk_update_form').submit();            
        }
    });

    $('#prof_bulk_del_btn').click(function(){
        if(confirm('Delete all information of the specified user.\nYou can not restore deleted user information.\n\nIf the number of users is large, processing may become heavy.\nWould you like to start processing?')){
            $('#bulk_del_form').submit();            
        }
    });
});
