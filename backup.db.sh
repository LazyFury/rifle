#!/bin/bash

# setup 
host="127.0.0.1"
port="3306"
user="rifle"
database="rifle"

read -s -p "Enter MySQL password: " PASSWORD
password=$PASSWORD

backup_dir="./database/backups/"



# get all args from command line
excludes=(
    "telescope_entries"
    "teloscope_entry_tags"
    "teloscope_tags"
)
exclude_str=""
for exclude in "${excludes[@]}"; do
    exclude_str+=" --ignore-table=${database}.${exclude}"
done

echo "Excludes: ${exclude_str}"


echo "-h $host -P $port -u $user -p$password $exclude_str  $database> $backup_dir$database-$(date +%Y-%m-%d-%H-%M-%S).sql"
mysqldump -h $host -P $port -u $user -p$password $exclude_str  $database> $backup_dir$database-$(date +%Y-%m-%d-%H-%M-%S).sql