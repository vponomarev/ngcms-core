#!/bin/sh

# Remove old build
rm -rf build/

# Create new build dir and download sources
mkdir build
git clone https://github.com/vponomarev/ngcms-core build/
git clone -b transfer_utf8 https://github.com/vponomarev/ngcms-plugins build/engine/plugins/
rm build.tgz
cd build
tar czf ../build.tgz .
cd ..

docker build -t ng-dev00 .
#docker run -it --rm -p 9000:9000 --name ng-dev00-deploy ng-dev00
