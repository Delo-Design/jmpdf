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

pwd

git clone ${BRANCH} git@github.com:"${REPO}".git

ls -l
