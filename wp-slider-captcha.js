$(function() {
    if (!window.jQuery) {
        try {
            document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></scr'+'ipt>');
        }
        catch (e) {
            console.log('Error occurred: ' +e.message);
        }
    }
    else if (!window.jQuery.ui) {
        try {
            document.write('<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></scr'+'ipt>');
        }
        catch (e) {
            console.log('jQuery UI was not loaded!');
        }
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
