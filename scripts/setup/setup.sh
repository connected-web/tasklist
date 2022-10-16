#!/bin/bash
SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
PROJECT_ROOT="$SCRIPT_DIR/../../"

$SCRIPT_DIR/installComposer.sh
mv composer.phar $PROJECT_ROOT/web
rm -f $PROJECT_ROOT/web/auth/composer.lock

cd $PROJECT_ROOT/web/
php $PROJECT_ROOT/web/composer.phar install

cd $PROJECT_ROOT/web/auth
php $PROJECT_ROOT/web/composer.phar install

