# Database writer config
Config definition and validation for database writer

## Usage
Require with composer:

```bash

    composer require keboola/db-writer-config

```

## Development

Clone this repository and init the workspace with following command:
```bash
    git clone git@github.com:keboola/db-writer-config.git
    cd db-writer-config
    docker-compose build
    docker-compose run --rm dev composer install --no-scripts
```

Run the test suite using this command:
```bash
    docker-compose run --rm dev composer tests
```

## License

MIT licensed, see [LICENSE](./LICENSE) file.