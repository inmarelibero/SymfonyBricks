(function($) {
    var displayGithubIssues = function($container, json) {
        // reset the container
        $container.html('');

        // adds the issues
        $.each( json.data, function ( i, item ) {

            var issueNode = $('<li class="issue" />').append(
                $('<a href="'+ item.html_url +'" target="_blank" />').html( '#'+item.number+' - '+item.title ),
                '<br>',
                $('<span class="info" />').html(
                    'by <a href="'+item.user.url+'">'+item.user.login+'</a> | comments: '+item.comments
                )
            );

            $container.append(issueNode);
        });
    };

    $.fn.githubIssues = function() {
        return this.each(function() {
            var $this = $(this);

            $.ajax({
                url:        "https://api.github.com/repos/inmarelibero/Symfonybricks/issues?state=open",
                dataType :  "jsonp",
                success:    function(data) {
                    displayGithubIssues($this, data);
                }
            });
        })
    };
}(jQuery));
