#!/bin/bash

FOLDER=$1

rm -f build.sh
rm -f .gitignore
rm -f .idea
rm -rf .git
rm -rf .github

cd cli
php update.php
cd ..

rm -rf cli

zip -r "${FOLDER}".zip .
