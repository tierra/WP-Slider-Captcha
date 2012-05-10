$(function() {
    if (!window.jQuery) {
        console.log('jQuery was not included!');
    }
    else if (!window.jQuery.ui) {
        console.log('jQuery UI was not loaded!');
    }
    else {
        var $form = $('#commentform');
        
        $form.find('input[type="submit"]').remove();
        
        var $captcha = $('<div id="wp-slider-captcha"><p id="wp-slider-info">Drag to the right more than 60%</p></div>');
        $captcha.slider({
            
            // whenever it's value is changed (after button release)
            change: function(evt, ui) {
                
                // if over given value...
                if(ui.value > 60) {
                    $form.submit();
                }
                
                else {
                    ui.value = 0;
                }
            }
        });
        $('#commentform').append($captcha);
    }
});
