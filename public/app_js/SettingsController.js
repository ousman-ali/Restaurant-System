/**
 * Created by rifat on 8/18/17.
 */
$(document).ready(function () {
   console.log("Settings Controller is ready");


    $("#driverSelection").on('change',function (e) {
        if($("#driverSelection").val() == "smtp"){
            $("#smtpDetails").show();
            $("#smtpDetails").prop('disable',false);
        }else{
            $("#smtpDetails").hide();
            $("#smtpDetails").prop('disable',true);
        }
    });

    $("#smtpMailSetting").on('submit',function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $(this).speedPost('/save-mail-conf', formData, message = {
            success: {header: 'SMTP Mail saved successfully', body: 'SMTP Mail saved successfully'},
            error: {header: 'Internal Server Error', body: 'Internal Server Error'},
            warning: {header: 'Internal Server Error', body: 'Internal server error'}
        });
    });

    $("#pusherSettings").on('submit',function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $(this).speedPost('/save-pusher-conf', formData, message = {
            success: {header: 'Pusher saved successfully', body: 'Pusher saved successfully'},
            error: {header: 'Internal Server Error', body: 'Internal Server Error'},
            warning: {header: 'Internal Server Error', body: 'Internal server error'}
        });
    });

    $("#timeZoneSettings").on('submit',function (e) {
        e.preventDefault();
        console.log('Timezone form submit');
        var formData = new FormData(this);
        $(this).speedPost('/save-timezone',formData,message ={
            success: {header: 'Timezone saved successfully', body: 'Timezone saved successfully'},
            error: {header: 'Timezone format not correct', body: 'Timezone format not correct'},
            warning: {header: 'Internal Server Error', body: 'Internal server error'}
        });
    });

    $("#currencySettings").on('submit',function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $(this).speedPost('/save-currency',formData,message ={
            success: {header: 'Currency saved successfully', body: 'Currency saved successfully'},
            error: {header: 'Currency format not correct', body: 'Currency format not correct'},
            warning: {header: 'Internal Server Error', body: 'Internal server error'}
        });
    })

});