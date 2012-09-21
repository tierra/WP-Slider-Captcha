jQuery(function($) {
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
            console.log('Error occurred: ' +e.message);
        }
    }
    else {
        var $form      = $('#' + wpsc_settings['wpsc_form_id']),
            threshold  = wpsc_settings['wpsc_threshold'],
            $captcha   = $('<div id="wp-slider-captcha"><p id="wp-slider-info">Drag to the right more than '+threshold+'%</p></div>');

        $form.find(':submit').remove();

        $captcha.slider({
            
            // whenever it's value is changed (after button release)
            change: function(evt, ui) {
                
                // if over given value...
                if(ui.value > threshold) {
                    $form.submit();
                }
                
                else {
                    ui.value = 0;
                }
            }
        });

        $form.append($captcha);
    }
});
