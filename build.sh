#!/bin/bash

FOLDER=$1

rm -f build.sh
rm -f .gitignore
rm -f .idea
rm -rf .git
rm -rf .github

mkdir libraries
cd libraries
composer require mpdf/mpdf
cd ..

rm -rf cli

zip -r "${FOLDER}".zip .
