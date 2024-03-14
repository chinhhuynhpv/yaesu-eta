#!/bin/bash

# set -x
cd `dirname $0`
[ -f .env ] || cp .env.example .env

source .env &> /dev/null || exit
app=${APP_NAME}-app

# Check Docker service
printf "Checking docker service status ... "
if ! docker info &> /dev/null ; then 
    echo "Error: docker service is not running. Please start Docker."
    exit
fi
echo done

# stop command
if [ "$1" = "stop" ] ; then
    echo "Stopping docker ..."
    docker-compose stop
    echo "Stopping docker ... done"
    exit
fi

# build command
if [ "$1" = "build" ] ; then
    echo "Building containers ..."
    docker-compose build
    echo "Building containers ... done"
    exit
fi

# check if docker is already up
if docker-compose ps | grep "$app.*Up" &> /dev/null  ; then
    echo "project $APP_NAME already started"
    docker-compose ps
    exit
fi

echo "docker-compose up -d ..."
docker-compose up -d || exit

setup(){
    echo "Setting up project ..."
    local docker_exec="docker exec $app"

    echo "composer install ..."
    $docker_exec composer install || _exit
    echo "composer install ... done"

    echo "php artisan key:generate ..."
    $docker_exec php artisan key:generate

    echo "php artisan migrate --seed ..."
    $docker_exec php artisan migrate --seed
    echo "php artisan migrate --seed ... done"

    read -p "Setting up project ... done. Press Enter to continue ..."
}

# run initial setup
docker-compose exec app test -f vendor/autoload.php || setup
if [ "$1" = "setup" ] ; then
    setup
fi


if [ "$SERVER_ENV" = "dev" ] ; then
    echo "$APP_NAME project started!"
else
    # attach to containers
    docker-compose up
fi
