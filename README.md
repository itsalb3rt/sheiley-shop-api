## Backend

The REST API is made in PHP so a server with PHP 7.1 or higher is required.

```bash
$ git clone https://github.com/itsalb3rt/sheiley-shop-api.git
```

```bash
$ make install-dependencies
```

### Backend production

In the `env` file you have a `API_ENVIRONMENT` variable that can be set to `production` or `development`.

```bash
#make serve
API_ENVIRONMENT=pro 
```

## Docker

The `API` is ready for using with docker, copy the `.env.bak` file and rename it for `.env` and set your configuration.

```bash
# install dependencies
make install-dependencies
# Production
docker-compose up -d
# Development
docker-compose -f docker-compose-dev.yml up -d
```

:warning: If you do not use `Traefik`, remove the `labels` and the `networks` section of the `docker-compose.yml`

[Go back to main documentation](https://github.com/itsalb3rt/sheiley_shop_app)
