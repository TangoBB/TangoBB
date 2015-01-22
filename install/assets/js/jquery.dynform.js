/*
 * dynForm JS
 * Created By: Jian Ting (http://jtpox.com/)
 * License: MIT License
 */
(function ($) {

    $.fn.dynForm = function (options) {

        var settings = $.extend({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            feedback: $(this).attr('class'),
            crossDomain: false,
            dataType: 'text',
            cache: false
        }, options);

        var formClass = '.' + $(this).attr('class');

        if ($(formClass + ' input:submit, ' + formClass + ' button').val()) {
            $(formClass).append('<input type="hidden" name="' + $(formClass + ' input[type="submit"]').attr('name') + '" value="' + $(formClass + ' input[type="submit"]').attr('value') + '" />');
        }
        var inputs = $(this).serialize();

        var feedback = '.' + settings.feedback;
        $(this).submit(function (event) {
            event.preventDefault();

            //Console Logging Data
            console.log('Sending data to: ' + settings.url);
            console.log('Cross Domain:' + settings.crossDomain);
            console.log('Information Sent:' + inputs);

            $.ajax({
                type: settings.type,
                crossDomain: settings.crossDomain,
                url: settings.url,
                data: inputs,
                dataType: settings.dataType,
                cache: settings.cache,
                success: function (data) {
                    $(feedback).html(data);
                },
                error: function (xhr, status) {
                    $(feedback).html(status);
                }
            });

        });

        return this;

    }

}(jQuery));
