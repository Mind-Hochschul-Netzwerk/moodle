# moodle

main page: mind-hochschul-netzwerk.de

## build and run

Make sure that the [traefik container](https://github.com/Mind-Hochschul-Netzwerk/traefik) is up and running. Then:

    $ docker-compose up --build -d moodle

Navigate to [https://mhn.docker.localhost](https://mhn.docker.localhost). Tell your browser to accept
the self-signed certificate. You will have to repeat this step whenever you restart your container.

    user: admin
    password: MHNTest0#

## adminer

To start adminer run

    $ docker-compose up -d moodle-adminer

Navigate to [http://moodle-adminer.docker.localhost](http://moodle-adminer.docker.localhost) and login with the credentials from `docker-compose.yml`

## LDAP authentication

Make sure that the [ldap container](https://github.com/Mind-Hochschul-Netzwerk/ldap) is up and running. See [README.md](https://github.com/Mind-Hochschul-Netzwerk/ldap/blob/master/README.md) there.
