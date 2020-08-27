#! /bin/bash

#vedere build.sh

GREEN='\033[0;32m'
NC='\033[0m' # No Color

clear
appVersion=$1;

printf "\n${GREEN} - Commit the new app version ${appVersion} change:${NC}\n"

git add .

git commit -m "changing release version to: v${appVersion}"

git tag -a v${appVersion} -m "v${appVersion}"

git push

git push origin --tags

git checkout master

git pull

git merge develop

git push

git push origin --tags

git checkout develop

git merge master

git push

printf "${NC}\n"

exit 0
