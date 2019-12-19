FROM node:12.13.0-alpine

ARG APP_HOST
    APP_PORT
    API_HOST
    API_PORT
    JWT_SECRET

ENV APP_HOST ${APP_HOST:-"0.0.0.0"}
    APP_PORT ${APP_PORT:-"8080"}
    API_HOST ${API_HOST:-"0.0.0.0"}
    API_PORT ${API_PORT:-"8000"}
    JWT_SECRET ${JWT_SECRET}

RUN apk add --no-cache bash yarn

COPY entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /usr/src/app

COPY package.json ./
COPY yarn.lock ./

COPY . .

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
