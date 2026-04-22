#!/usr/bin/env bash
set -euo pipefail

php artisan storage:link --force
php artisan migrate --force
php artisan db:seed --force
