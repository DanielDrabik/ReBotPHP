#!/bin/sh
set -e

composer install --no-dev --optimize-autoloader

php src/index.php

