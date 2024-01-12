#!/bin/bash

# требования: composer, zip, unzip, curl, git, настроенный доступ ssh в гитхабе

rm -f build.sh
rm -f .gitignore
rm -f .idea
rm -rf .git
rm -rf .github

cd cli
php update.php
cd ..

rm -f cli

zip -r "${FOLDER}".zip .
