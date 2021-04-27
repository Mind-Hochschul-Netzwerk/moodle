# moodle

main page: mind-hochschul-netzwerk.de

## Build and run

Edit `.env.sample` and save it as `.env`

### Target "dev" (development)

Optional, speeds up `docker build`:

   $ ./get-resources.sh

Then:

    $ make quick-image
    $ make dev

Navigate to [http://moodle.docker.localhost](http://moodle.docker.localhost). Tell your browser to accept the self-signed certificate. You will have to repeat this step whenever you restart your container.

* Benutzername: admin
* Passwort: MHNTest0#

Navigate to [http://moodle-adminer.docker.localhost](http://moodle-adminer.docker.localhost) and login with the credentials from `.env` and `docker-compose.base.yml`.

### Target "prod" (production)

    $ make prod

## LDAP authentication

Make sure that the [ldap container](https://github.com/Mind-Hochschul-Netzwerk/ldap) is up and running.

