#!/bin/bash

# Usage: ./mysql-tree.sh nom_base utilisateur mot_de_passe

DB="$1"
USER="$2"
PASS="$3"

if [ -z "$DB" ] || [ -z "$USER" ] || [ -z "$PASS" ]; then
    echo "Usage: $0 <database> <user> <password>"
    exit 1
fi

echo "$DB/"

# Récupération des tables
tables=$(mysql -N -u"$USER" -p"$PASS" -D "$DB" -e "SHOW TABLES;")

for t in $tables; do
    echo "├── $t"

    # Récupération des colonnes
    cols=$(mysql -N -u"$USER" -p"$PASS" -D "$DB" -e "SHOW COLUMNS FROM \`$t\`;")

    while read -r col _; do
        echo "│   └── $col"
    done <<< "$cols"
done
