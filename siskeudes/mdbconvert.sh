#!/bin/bash
# Convert a MDB file (Access) to SQL
# Needs mdbtools[http://mdbtools.sourceforge.net/]
#  run 'aptitude install mdbtools' on Debian/Ubuntu
# Created by Álvaro Justen <https://github.com/turicas>
# License: GPLv2

mdb=$1
sql=$2

if [ -z "$2" ]; then
    echo 'This script convert a MDB file to SQL file. You need to specify the name of both'
    echo "Usage: $0 <mdb_file> <sql_folder>"
    exit 1
fi

if [ -z "$(which mdb-tables)" ]; then
    echo 'You need mdbtools installed.'
    echo 'Learn more at http://mdbtools.sourceforge.net/'
    echo 'If you use Debian/Ubuntu, just execute:'
    echo '  sudo aptitude install mdbtools'
    exit 2
fi

#mdb-schema $mdb > $sql
#sed -i 's/Long Integer/INT(11)/g; s/Text /VARCHAR/g' $sql
for table in $(mdb-tables $mdb); do
#    mdb-export -I -R ';' $mdb $table >> $sql
    mkdir -p $sql
    mdb-export $mdb $table >> $sql/$table.csv
    echo 'file '$sql/$table.csv' telah dibuat'
done
#sed -i '/^-\{2,\}/d; s/DROP TABLE /DROP TABLE IF EXISTS /' $sql