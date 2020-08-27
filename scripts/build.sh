#!/bin/bash

#questo script va lanciato cosí:
#./build.sh <releaseType>
#releaseType:
# -M crea una mayor release e fa il commit di tutto,
# -m crea una minor release e fa il commit di tutto,
# se non passo nessun parametro solo fa il build

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

clear

printf "${BLUE}***************************************************************\n"
printf "********************* PROJECT BUILDING SCRIPT ************************\n"
printf "**********************************************************************\n"

echo $1

if [ $1 == "-M" ]
then
  releaseType='mayor'
elif [ $1 == "-m" ]
then
  releaseType='minor';
else
  releaseType='onlybuild';
fi

printf "\n${YELLOW} Version release type:${NC}\n"

printf "${releaseType}\n";

printf "\n${GREEN} - Going to the project folder:${NC}\n"

pwd

printf "\n"

printf "\n${GREEN} - Updating develop branch:${NC}\n"

git checkout develop

git pull

printf "\n${GREEN} - Launch things for cleaning laravel things:${NC}\n"

composer dump-autoload --optimize
php artisan config:cache

printf "\n${GREEN} - Building assets:${NC}\n"

npm run prod

if [ ${releaseType} == "mayor" ] || [ ${releaseType} == "mayor" ]
then
  printf "\n${GREEN} - Changing app version:${NC}\n"
fi

if [ ${releaseType} == "mayor" ]
then
  npm run change-version -- mayor
elif [ ${releaseType} == 'minor' ]
then
  npm run change-version -- minor
fi

appVersion=($(jq -r '.version' composer.json))

php artisan config:cache

if [ ${releaseType} != "onlybuild" ]
then
    scripts/createZipPackage.sh ${appVersion}

    scripts/commitVersionAndTag.sh ${appVersion}

    printf "\n${GREEN} Finished release version ${appVersion}${NC}\n"
fi

printf "${NC}\n"

exit 0
