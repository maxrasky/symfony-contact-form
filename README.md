Contact form API implementation
-
Provided  semi-dockerized application with `docker-compose.yml` to have mysql containerized to simplify application launch:
- run `docker-compose up -d` to have mysql db running in container
- run `composer install`
- run `symfony console doctrine:migrations:migrate`
 
Now you have ready to use app. Run tests with `php ./bin/phpunit`. Launch app  with`symfony serve`.

Endpoints:

- add contact: `POST /contact` with payload `{email: string, message: string}` `Content-Type: application/json`
- get contact: `GET /contact/{id}` where id is a number
- import contacts: `POST /contact/import` with `-F file={path_to_file}`, `Content-Type: application/x-www-form-urlencoded` 

CSV file structure:\
should use `comma` as separator: 1st column is email, 2nd - message

For unix-like environments project is also provided with `makefile` commands, so you can run:

- `make db` launches dockerized mysql container
- `make install` runs `composer install` command
- `make migrate` runs symfony migrations
- `make test` runs unit tests
- `make run` runs symfony server

Notice:
- 
Docker setup will create `symfony-contact-form_dbdata` volume. 
One can view docker volumes with `docker volume ls` command.
In order to remove unneeded volume from the system one needs to run `docker volume rm symfony-contact-form_dbdata` command.
