#!/usr/bin/env bash

Reset="\033[0m";
Cyan="\033[0;36m";
Yellow="\033[0;33m";
Red="\033[0;31m";

## No Arguments
if [ -z "$*" ]; then
    printf "${Cyan}Usage:\n";
    printf " $0 build ${Yellow}(build docker image)\n";
    printf " ${Cyan}$0 up    ${Yellow}(start the docker environment)\n";
    printf " ${Cyan}$0 down  ${Yellow}(stop the docker environment)${Reset}\n";
    printf " ${Cyan}$0 bash  ${Yellow}(start bash session in container)${Reset}\n";
    exit 0;
fi

touch docker/.bash_history

if [ "$1" = "build" ]; then
    docker build \
        --build-arg BUILDKIT_INLINE_CACHE=1 \
            --cache-from php_queue_dev \
            --file docker/Dockerfile \
            --tag php_queue_dev \
            .
    exit 0;
fi

if [ "$1" = "up" ]; then
    docker compose -f docker/docker-compose.yml -p php_queue_dev up -d;
    exit 0;
fi

if [ "$1" = "down" ]; then
    docker compose -f docker/docker-compose.yml -p php_queue_dev down;
    exit 0;
fi

if [ "$1" = "bash" ]; then
    docker exec -it php_queue_dev bash;
    exit 0;
fi

printf "${Red}Unrecognized argument${Reset}\n";
exit 1;
