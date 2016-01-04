#!/bin/bash

echo ''
echo 'Selenium Server is starting. Close Shell to stop it ;) '
echo ''
echo ''
echo ''

if [ ! -f selenium-server-standalone.jar ]
then
    curl http://selenium-release.storage.googleapis.com/2.48/selenium-server-standalone-2.48.2.jar > selenium-server-standalone.jar
fi

if [ ! -f chromedriver ]
then
    # TODO: Support other OS
    curl http://chromedriver.storage.googleapis.com/2.14/chromedriver_mac32.zip > chromedriver.zip
    unzip chromedriver.zip
    rm chromedriver.zip
fi

java -jar selenium-server-standalone.jar
