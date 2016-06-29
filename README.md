# Rdio3 API

Restful API to get the contents of the different programs of [Radio 3](http://www.rtve.es/radio/radio3/) web.


# Table of Contents

- [Installation](#installation)
- [How to Use](#howtouse)
- [API Verbs](#apiverbs)
- [Contributing](#contributing)
- [Dependencies](#dependencies)
- [License](#license)

## Installation
Clone the repository to your local machine and run.
```sh
  composer install
```
Change the ```.env``` file attributes to match your local database credentials.
Create the ```rdio3``` database.
Then don't forget to seed the database with initial data by running.
```sh
  php artisan migrate --seed
```
## How to Use
To start filling up the database with podcasts make sure the programs table is filled with some initial data. Then run the command:
```sh
  php artisan queue:listen --timeout=180 --tries=3 --queue="podcast,table,page"
```
The following command sets a queue which will listen to new requests.
Then go to to

`http:\\path\init\{program}`

Where program is an optional parameter which specifies the program to be crawled.

To update the podcasts you can manually use:
```sh
  php artisan podcasts:update
```
Go to the route
`http:\\path\update\{program}`

Or setting a cron job in the server with the following code:
```sh
  * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```
## API Verbs

`GET`

| Route                | Meaning                                                    |
| -------------        |-------------                                               |
| `\program`           | Get a list of available programs                           |
| `\program\{program}` | Get the list of podcasts sorted by date and paginated      |
| `\init\{program}`    | Populates the tables with initial data                     |
| `update\{program}`   | Updates the tables with the lastest data                   |

## Contributing
Feel free to fork the original repository and make changes on the code or the interface.
If you make some interesting changes, fixed a bug or updated performance your contribution is appreciated.
Before making any pull request make sure:
* Your code works.
* Avoid obfuscating code.
* It is a major change.

Thanks for your collaboration.


## Dependencies
 - [Laravel 5.2](https://laravel.com/)
 - [Composer](https://getcomposer.org/)


## License
Rdio3 is released under the MIT Licence.
