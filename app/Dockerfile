FROM node:12.13.0-alpine

ARG APP_HOST
ARG APP_PORT
ARG API_HOST
ARG API_PORT
ARG JWT_SECRET

ENV APP_HOST ${APP_HOST}
ENV APP_PORT ${APP_PORT}
ENV API_HOST ${API_HOST}
ENV API_PORT ${API_PORT}
ENV JWT_SECRET ${JWT_SECRET}

RUN apk add --no-cache bash yarn

COPY entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /usr/src/app

COPY package.json ./
COPY yarn.lock ./

COPY . .

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]