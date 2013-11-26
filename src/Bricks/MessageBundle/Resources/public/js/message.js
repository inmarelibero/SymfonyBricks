/**
 * Send a message via ajax
 * Function called when submitting a form with class .button-modal-message-form
 *
 * @return {Boolean}
 */
function messageAjaxSend(form) {

    // form submit button
    var buttonSubmit = form.find('button[type="submit"]');
    buttonSubmit.originalHtml = buttonSubmit.html();

    // ajax messages container
    var ajaxMessages = form.find('#ajax-messages');

    $.ajax({
        type:       'POST',
        url:        form.attr('action'),
        data:       form.serialize(),
        dataType:   'json',

        beforeSend: function() {
            // change button state
            buttonSubmit.attr("disabled", "disabled");
            buttonSubmit.html('<img src="/img/ajax-loader-snake.gif" />');

            // restore empty content
            ajaxMessages.find('.success').hide();
            ajaxMessages.find('.errors').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // ajax messages container: errors messages
            var ajaxMessagesErrors = ajaxMessages.find('.errors');

            // empty previous errors
            ajaxMessagesErrors.html();

            // json response
            var json = jQuery.parseJSON(jqXHR.responseText);

            if (json.errors != undefined) {

                var errors = json.errors;

                for (var error in errors) {
                    var arrayErrors = errors[error];

                    // add errors to display
                    for (var i = 0; i < arrayErrors.length; i++) {
                        ajaxMessagesErrors.html(arrayErrors[i]);
                    }
                }
            } else {
                ajaxMessagesErrors.html('Errors occurred');
            }

            // display errors container
            ajaxMessagesErrors.show();
        },
        success: function(data, textStatus, jqXHR) {
            // ajax messages container: success message
            var ajaxMessagesSuccess = ajaxMessages.find('.success');

            ajaxMessagesSuccess.show();
        },
        complete: function() {
            // restore button state
            buttonSubmit.html(buttonSubmit.originalHtml);
            buttonSubmit.removeAttr("disabled");

            // empty the textarea
            form.find('textarea').val('');
        }
    });

    return false;
};