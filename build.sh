#!/bin/bash

# требования: composer, zip, unzip, curl, git, настроенный доступ ssh в гитхабе

rm -rf tmpbuild
mkdir tmpbuild
cd tmpbuild


REPO=$1
BRANCH=$2
FOLDER=$3

if [[ "${BRANCH}" == "master" ]]
then
  BRANCH=""
fi

if [[ "${BRANCH}" == "main" ]]
then
  BRANCH=""
fi

git clone ${BRANCH} git@github.com:"${REPO}".git
cd "${FOLDER}"

rm -f README.md
rm -f .gitignore
rm -f examples
rm -f .idea
rm -rf .git

mkdir libraries
cd cli
php update.php

cd ../../

zip -r "${FOLDER}".zip .
mv "${FOLDER}".zip ../..
cd ..
rm -rf "${FOLDER}"
cd ..
rm -rf tmpbuild