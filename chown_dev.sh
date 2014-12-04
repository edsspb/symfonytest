#!/bin/sh

CORE_PATH='/var/services/web/sftest2/'

cd ${CORE_PATH}

chown -R http:http web/bundles
chown -R http:http app/cache
chown -R http:http app/logs
