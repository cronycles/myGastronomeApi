#! /bin/bash

#vedere build.sh

GREEN='\033[0;32m'
NC='\033[0m' # No Color

clear
appVersion=$1;

export PUBLICFOLDERNAME=public_html
export BUILDFOLDERNAME=build

printf "\n${GREEN} - Create version ${appVersion} zip package:${NC}\n"

mkdir -p $BUILDFOLDERNAME

/bin/rm -rf $BUILDFOLDERNAME/*

/bin/cp -R $PUBLICFOLDERNAME $BUILDFOLDERNAME
/bin/rm -rf $BUILDFOLDERNAME/$PUBLICFOLDERNAME/cdn.*
/bin/cp -R scripts/production/.htaccess $BUILDFOLDERNAME/$PUBLICFOLDERNAME/

/bin/cp -R app $BUILDFOLDERNAME
/bin/cp -R bootstrap $BUILDFOLDERNAME
/bin/rm -rf $BUILDFOLDERNAME/bootstrap/cache/*
/bin/cp -R config $BUILDFOLDERNAME
/bin/cp -R resources $BUILDFOLDERNAME
/bin/cp -R routes $BUILDFOLDERNAME
/bin/cp -R vendor $BUILDFOLDERNAME

tar -czf build_v$appVersion.tar.gz $BUILDFOLDERNAME

/bin/rm -rf $BUILDFOLDERNAME/*

/bin/mv build_v$appVersion.tar.gz $BUILDFOLDERNAME

printf "${NC}\n"

exit 0
