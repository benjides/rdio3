# Rdio3 API

Restful API to get the contents of the different programs of [Radio 3](http://www.rtve.es/radio/radio3/) web.


# Table of Contents

- [Installation](#installation)
- [How to Use](#)
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

## Contributing
Feel free to fork the original repository and make changes on the code or the interface.
If you make some interesting changes, fixed a bug or updated performance your contribution is appreciated.
Before making any pull request make sure:
* Your code works.
* Avoid obfuscating code.
* It is a major change.

For any minor changes or requests use:
* The dedicated forum thread. [Link](#)
* GitHub issues' tracker. [Link](https://github.com/benjides/terrabattlez/issues)
* Twitter official account. [Link](https://twitter.com/TerraBattleZ)
* Mail account. [Link](mailto:zterrabattle@gmail.com)

Thanks for your collaboration. Without you this would be nothing.


## Dependencies
 - [Laravel](https://laravel.com/)
 - [Bootstrap](http://getbootstrap.com/)
 - [Composer](https://getcomposer.org/)
 - [OpenShift](https://openshift.redhat.com/)

## License
TerraBattleZ is released under the MIT Licence.
