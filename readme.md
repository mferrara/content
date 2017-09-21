# Content

## Queue/Workers

###### Tubes

    tube = redditscrape (2)
    Currently running 2 of these with 6000 second timeouts and 30 second sleep cycles. 2 Attempts. Non-daemon.

    tube = redditprocessing (1)
    Currently running 1 of these with 6000 second timeouts and 30 second sleep cycles. 2 Attempts. Non-daemon.

## API (ghetto)

### Endpoints

##### /api/search (GET)

###### Parameters

    query (required) - url encoded string, used to search for content
    webhook_url (required) - url encoded url, when results are collected they will be POST'ed to this url
    max_words (optional) - integer to represent the maximum number of words provided in the text content (default: 600)

###### Examples

    http://content.app/api/search?query=stars&webhook_url=http%3A%2F%2Fexample.com%2Fwebhook%2Fpage%2F17%2Fcontent%2Fstore&max_words=1200