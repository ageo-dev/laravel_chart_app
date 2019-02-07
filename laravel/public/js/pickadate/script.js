$(function () {
    const pickadateDateIdArray = [
        'charge_date',
        'create_date_s',
        'create_date_m',
        'start_date_s',
        'start_date_m',
        'pickadate_show_datetime'
    ];
    const pickadateTimeIdArray = [
        'charge_time',
    ];

    pickadateDateIdArray.forEach(element => {
        $('#'+element).pickadate({
            format: 'yyyy年m月d日'
        });    
    });

    pickadateTimeIdArray.forEach(element => {
        $('#'+element).pickatime({
            format: 'HH時i分',
            interval: 1,
            min: [00, 00],
            max: [23, 59] 
        });  
    });


    const makeDateTime = function(dateId,timeId){
        let date = $('#'+dateId).val()
        .replace('年','-').replace('月','-').replace('日','');
        let time;
        if(timeId != null){
            time = $('#'+timeId).val()
                .replace('時',':').replace('分','');
        }else{
            time = '00:00';
        }
        let datetime = date + ' ' +  time;
        return datetime;
    }

    const makeDate = function(dateId){
        let date = $('#'+dateId).val()
        .replace('年','-').replace('月','-').replace('日','');
        return date;
    }

    $('#charge_date').change(function (e) {
        let datetime = makeDateTime('charge_date',null);
        $('#charge_datetime').val(datetime);       
    });

    $('#charge_time').change(function (e) { 
        let datetime = makeDateTime('charge_date',null);
        $('#charge_datetime').val(datetime);
    });

    $('#pickadate_show_datetime').change(function (e) {
        let date = makeDate('pickadate_show_datetime');
        $('#show_datetime').val(date);       
    });

});