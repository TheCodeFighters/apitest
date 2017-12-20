# APItest

APItest is a RESTful API which connects with Twitter in order to retrieve the last tweets of a user.
It is written using PHP 7.1 and Symfony, and virtualized with Docker. It also uses Redis as a cache system.

> Endpoint example: api/apitest/twitter/user/darkkz/messages/5

## Configuration

* Retrieve a Twitter API Key and update your `src/AppBundle/Resources/config/twitter.conf.yml`
