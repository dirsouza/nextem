#!/bin/bash

rm .env
printf "VUE_APP_API_HOST=${API_HOST}\nVUE_APP_API_PORT=${API_PORT}\nVUE_APP_JWT_SECRET=${JWT_SECRET}" >> .env
chmod -R 777 .env
yarn install
yarn serve --host="${APP_HOST}" --port="${APP_PORT}"
