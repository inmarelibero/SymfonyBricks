/**
 * call the rainbox plugin to apply code highlight
 *
 * options:
 *  - "container": a jquery object to limit the scope to search for "pre code" elements
 */
function syntaxHighlight(options) {

    var defaults = {
        'container': $('body')
    }
    options = $.extend( {}, defaults, options );

    /**
     * apply the syntax highlight
     */
    options.container.find('pre code').each(function() {

        // code content
        var html = $(this).html();

        // php => begin with '<?php'
        if (html.substring(0, 8) == '&lt;?php') {
            $(this).attr('data-language', 'php');
        }

        // javascipt => begin with '<script'
        else if (html.substring(0, 10) == '&lt;script') {
            $(this).attr('data-language', 'javascript');
        }

        /*
        // html => begin with '<'
        else if (html.substring(0, 4) == '&lt;') {
            $(this).attr('data-language', 'html');
        }
        */

        // generic highlight
        else {
            $(this).attr('data-language', 'generic');
        }
    });

    Rainbow.color();
}