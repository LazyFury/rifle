#!/bin/bash

# setup 
host="127.0.0.1"
port="3306"
user="rifle"
database="rifle"

read -s -p "Enter MySQL password: " PASSWORD
password=$PASSWORD

backup_dir="./database/backups/"

function backup_table () {
    local table_name=$1
    local backup_file="$backup_dir$table_name.sql"
    echo "Backing up $table_name to $backup_file"
    echo "-h $host -P $port -u $user -p$password $database $table_name > $backup_file"
    # mysqldump -h $host -P $port -u $user -p$password $database $table_name --no-tablespaces > $backup_file
    # only insert
    mysqldump -h $host -P $port -u $user -p$password $database $table_name --no-create-info --no-tablespaces > $backup_file
}

function import_table () {
    local table_name=$1
    local backup_file="$backup_dir$table_name.sql"
    echo "Importing $table_name from $backup_file"
    # echo "-h $host -P $port -u $user -p$password $database $table_name < $backup_file"
    mysql -h $host -P $port -u $user -p$password $database $table_name < $backup_file
}

# $1 is import 
if [ "$1" == "import" ]; then
    import_table "permissions"
    import_table "users"
    import_table "roles"
    import_table "role_has_permissions"
    import_table "menus"
    import_table "dicts"
    import_table "dict_groups"
    import_table "api_manages"
    exit 0    
fi

# $1 is backup
if [ "$1" == "backup" ]; then
    backup_table "permissions"
    backup_table "users"
    backup_table "roles"
    backup_table "role_has_permissions"
    backup_table "menus"
    backup_table "dicts"
    backup_table "dict_groups"
    backup_table "api_manages"
    exit 0
fi

    # backup_table "permissions"
    # backup_table "users"
    # backup_table "roles"
    # backup_table "role_has_permissions"
    # backup_table "menus"
    # backup_table "dicts"
    # backup_table "dict_groups"
    # backup_table "api_manages"
    # exit 0

echo "Usage: backup.db.sh [import|backup]"
exit 1


