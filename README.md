# moodle

main page: mind-hochschul-netzwerk.de

## Build and run

Edit `.env.sample` and save it as `.env`

### Target "dev" (development)

```bash
$ make rebuild
$ make dev
```

Navigate to [http://www.docker.localhost](http://www.docker.localhost). Tell your browser to accept the self-signed certificate. You will have to repeat this step whenever you restart your container.

* username: admin
* password: MHNTest0#

Navigate to [http://moodle-adminer.docker.localhost](http://moodle-adminer.docker.localhost) and login with the credentials from `.env` and `docker-compose.yml`.

### Target "prod" (production)

```bash
$ make prod
```
## LDAP authentication

Make sure that the [ldap container](https://github.com/Mind-Hochschul-Netzwerk/ldap) is up and running.
