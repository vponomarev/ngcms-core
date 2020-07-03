#!/bin/sh

#
# Script for building docker image using PULL REQUEST ID
#

if [ "${PULL_REQUEST}" = "" ]; then
    echo "Pull Request ID is not specified!"
    exit;
fi

echo "Fetching PULL REQUEST: ${PULL_REQUEST}"

# Remove old build
rm -rf build/

# Create new build dir and download sources
mkdir build
git clone https://github.com/vponomarev/ngcms-core build/

# Switch to pull request data
cd build
git fetch origin pull/${PULL_REQUEST}/head
git checkout -b pullrequest FETCH_HEAD
cd ..

git clone -b transfer_utf8 https://github.com/vponomarev/ngcms-plugins build/engine/plugins/
rm build.tgz
cd build
tar czf ../build.tgz .
cd ..

docker build -t ng-dev00 .
#docker run -it --rm -p 9000:9000 --name ng-dev00-deploy ng-dev00
