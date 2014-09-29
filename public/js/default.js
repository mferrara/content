$(document).ready(function()
{

    $('#subreddit-search-submit').on('click', function()
    {
        event.preventDefault();

        search_submit();
    });

    $('#subreddit-search').keypress(function(e)
    {
        if(e.which == 13)
        {
            event.preventDefault();

            search_submit();
        }

    });

    function search_submit()
    {
        var subreddit = $('#subreddit-search').val();

        window.location = "sub/"+subreddit;
    }

});