$(document).ready(function() {

    $('#authForm').bind('submit', function(){

       if($('#password').val() != "" && $('#email').val() != "") {  
           $('#md5').attr('value', MD5($('#password').val()));
           $('#password').attr('value', '');  
       } else {
           return false;   
       }        
    
    });

});