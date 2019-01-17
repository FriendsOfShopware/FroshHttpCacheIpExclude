#!/usr/bin/env bash

commit=$1
if [ -z ${commit} ]; then
    commit=$(git tag | tail -n 1)
    if [ -z ${commit} ]; then
        commit="master";
    fi
fi

# Remove old release
rm -rf FroshHttpCacheIpExclude FroshHttpCacheIpExclude-*.zip

# Build new release
mkdir -p FroshHttpCacheIpExclude
git archive ${commit} | tar -x -C FroshHttpCacheIpExclude
composer install --no-dev -n -o -d FroshHttpCacheIpExclude
zip -x "*build.sh*" -x "*.MD" -r FroshHttpCacheIpExclude-${commit}.zip FroshHttpCacheIpExclude