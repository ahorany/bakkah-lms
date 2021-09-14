$('.number input').keydown(function(e){
    keydown_text(e, this);
});
$('.digit input').keydown(function(e){
    keydown_text_with_dot(e, this);
});
$('.maxlength9 input').attr('maxlength', 9);
$('.maxlength10 input').attr('maxlength', 10);
$('.maxlength15 input').attr('maxlength', 15);

function keydown_text(event,text_name){
    // Allow: backspace, delete, tab, escape, and enter
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 40
        ||
        // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        (event.keyCode == 86 && event.ctrlKey === true) ||
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        // let it happen, don't do anything
        $(text_name).css('border-color','#DDDDDD');
        return;
    }
    else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || event.keyCode == 66 || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105
        ) && (event.keyCode != 17) ) {// && (event.keyCode != 86)
            $(text_name).css('border-color','#FB626D');//red
            event.preventDefault();
        }else{$(text_name).css('border-color','#DDDDDD');}
        ///
        /*$(text_name).bind("cut copy paste",function(e) {
        e.preventDefault();
    });  */
    }
}

function keydown_text_with_dot(event,text_name){
    // Allow: backspace, delete, tab, escape, and enter
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 40
        || event.keyCode == 110 ||
        // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        (event.keyCode == 86 && event.ctrlKey === true) ||
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        // let it happen, don't do anything
        $(text_name).css('border-color','#DDDDDD');
        return;
    }
    else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || event.keyCode == 66 || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105
        ) && (event.keyCode != 17) ) {// && (event.keyCode != 86)
            $(text_name).css('border-color','#FB626D');//red
            event.preventDefault();
        }else{$(text_name).css('border-color','#DDDDDD');}
        ///
        /*$(text_name).bind("cut copy paste",function(e) {
        e.preventDefault();
    });  */
    }
}
