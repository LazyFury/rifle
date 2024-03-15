#!/bin/bash

# setup 
host="127.0.0.1"
port="3306"
user="rifle"
password="rifle"
database="rifle"
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

# Backup the database
backup_table "permissions"
backup_table "users"
backup_table "roles"
backup_table "role_has_permissions"
backup_table "menus"
backup_table "dicts"
backup_table "dict_groups"
backup_table "api_manages"

