#!/usr/bin/env bash
CURRENT_DIR=$(dirname ${BASH_SOURCE[0]})

. $CURRENT_DIR/_functions.sh


CONSOLE="php $CURRENT_DIR/../bin/console"

database_path=$CURRENT_DIR/../var/databases/database.sqlite
if [ -f $database_path ] ; then
    echo "Removing database $database_path"
    rm $database_path
fi

touch $database_path
check_errs $? ${BASH_SOURCE[0]} $LINENO

echo "Creating database $database_path"
$CONSOLE doctrine:database:create -vv
check_errs $? ${BASH_SOURCE[0]} $LINENO

$CONSOLE cache:clear --no-warmup -vv
check_errs $? ${BASH_SOURCE[0]} $LINENO

$CONSOLE cache:warmup -vv
check_errs $? ${BASH_SOURCE[0]} $LINENO

$CONSOLE doctrine:schema:update --dump-sql --force -vvv
check_errs $? ${BASH_SOURCE[0]} $LINENO

test_database_path=$CURRENT_DIR/../var/databases/test_database.sqlite
if [ -f $test_database_path ] ; then
    rm $test_database_path
fi
echo "Making a copy for tests : $test_database_path"
cp $database_path $test_database_path
check_errs $? ${BASH_SOURCE[0]} $LINENO

