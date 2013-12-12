$(document).ready(function(){
    $("form#shoutbox_shout").on('submit',function(event){
    event.preventDefault();
        alert("Hello");
    });
});