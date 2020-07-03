#!/bin/sh

#
# Force docker image to redeploy ngcms on next start with config preservation
#
touch ngcms/.redeploy-preserve
chown www-data.www-data ngcms/.redeploy-preserve
