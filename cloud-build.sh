#!/usr/bin/env bash
set -euo pipefail

export COMPOSER_NO_DEV=1

composer install
npm install
npm run build
