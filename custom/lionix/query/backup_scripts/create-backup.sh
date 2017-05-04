#! /bin/bash

#Prompt for server, username, password and database.
read -e -p "Server: " -i "localhost" SERVER
read -e -p "MySQL user: " -i "root" USERNAME
echo "MySQL pass: "
stty_orig=`stty -g` # save original terminal setting.
stty -echo          # turn-off echoing.
read PASSWORD       # read the password
stty $stty_orig     # restore terminal setting.
read -e -p "MySQL database: " -i "lionixcrm" DATABASE
read -e -p "Don't make backup of these tables: " -i "'',''" DONTBACKUP

#Creating backup directory
DATE=`date +%Y-%m-%d-%H-%M`
mkdir -p backup-${DATABASE}-${DATE}/tables
cd backup-${DATABASE}-${DATE}

#About 1 hour before performing the backup run this SQL command
echo "Cleaning dirty pages, this process could take 60 minutes..."
mysql -h${SERVER} -u${USERNAME} -p"${PASSWORD}" -e"SET GLOBAL innodb_max_dirty_pages_pct = 0" &
wait

#Table definitions
echo "Backing up table definitions..."
mysqldump -h${SERVER} -u${USERNAME} -p"${PASSWORD}" --no-data ${DATABASE} > ${DATABASE}_tableDefs.sql &
wait

#Save the stored procedures
echo "Backing up store procedures..."
mysqldump -h${SERVER} -u${USERNAME} -p"${PASSWORD}" --no-data --no-create-info --routines ${DATABASE} > ${DATABASE}_storedProcedures.sql &

#Here is the generic way to dump the SQL Grants for users that is readble and more portable
echo "Backing up SQL grants..."
mysql -h${SERVER} -u${USERNAME} -p"${PASSWORD}" --skip-column-names -A -e"SELECT CONCAT('SHOW GRANTS FOR ''',user,'''@''',host,''';') FROM mysql.user WHERE user<>''" | mysql -h${SERVER} -u${USERNAME} -p"${PASSWORD}" --skip-column-names -A | sed 's/$/;/g' > ${DATABASE}_grantsMySQL.sql

#Start by creating a list of tables and views
cd tables/
echo "Backing up tables individually..."
mysql -h${SERVER} -u${USERNAME} -p"${PASSWORD}" -A --skip-column-names -e"SELECT CONCAT(table_schema, '.', table_name) FROM information_schema.tables WHERE table_schema NOT IN ('information_schema' , 'mysql') AND table_schema = '${DATABASE}' AND table_name NOT IN ('',${DONTBACKUP}) ORDER BY table_rows ASC" > ${DATABASE}_listOfTables.txt

#Then dump all tables and views in groups of 10
COMMIT_COUNT=0
COMMIT_LIMIT=10
SEQUENCE=1000
for DBTB in `cat ${DATABASE}_listOfTables.txt`
do
    DB=`echo ${DBTB} | sed 's/\./ /g' | awk '{print $1}'`
    TB=`echo ${DBTB} | sed 's/\./ /g' | awk '{print $2}'`
    (( SEQUENCE++ ))
    mysqldump -h${SERVER} -u${USERNAME} -p"${PASSWORD}" --single-transaction --quick --hex-blob --triggers ${DB} ${TB} | bzip2 -cq9 > ${SEQUENCE}-${DB}-${TB}.sql.bz2 &
    (( COMMIT_COUNT++ ))
    if [ ${COMMIT_COUNT} -eq ${COMMIT_LIMIT} ]
    then
        COMMIT_COUNT=0
        wait
    fi
done
if [ ${COMMIT_COUNT} -gt 0 ]
then
    wait
fi

cd ../..
tar -czvf backup-${DATABASE}-${DATE}.tar.gz backup-${DATABASE}-${DATE}/
echo "Backup for database ${DATABASE} completed."
echo "To restore files you can do:"
echo 'for f in *.sql; do echo $f && mysql -uanuser -papassword adatabase < "$f" ; done'
echo "In MySQL: UPDATE \`mysql\`.\`proc\` p SET definer = 'root@localhost' WHERE definer LIKE 'root@%';"
echo ""
echo "To unzip table files:"
echo 'for f in *.bz2; do bunzip2 "$f" ; done'
#In MySQL in another process: FLUSH TABLES WITH READ LOCK; SELECT SLEEP(86400);
# All tables on view definitions must exists on target database contrary the view won't be recreated.
#fin
