Url shortner
-------------------------

Self-hosted urlshortner written with PhpPhalcon
Example - http://u.assorium.ru/

Installation
-------------------------
You need to install PhalconPHP extension http://phalconphp.com/en/, PHP APC extension (?).

Copy this git project. Insert database structure from db folder. Edit **config.ini** file.

**You should check all the fields in config file.**

DB. All the Database settings
- host: host path
- dbname: database name
- user: user name
- password: password
- charset
- persistent

Application settings
- base_uri: Your app uri
- suffix: suffix for cache
- debug: show Exceptions by 1
- cache_apc: 1 - cache to APC (needed extension), else FILES, give permissions to write for /tmp/cache/ directory
