set -e
set -x

# check if this is a travis environment
function cleanup {
  echo "Removing build directory ${BUILDENV}"
  rm -rf ${BUILDENV}
}

trap cleanup EXIT

if [ ! -z $TRAVIS_BUILD_DIR ] ; then
  WORKSPACE=$TRAVIS_BUILD_DIR
fi

if [ -z $WORKSPACE ] ; then
  echo "No workspace configured, please set your WORKSPACE environment"
  exit
fi

BUILDENV=`mktemp -d /tmp/mageteststand.XXXXXXXX`

echo "Using build directory ${BUILDENV}"

git clone https://github.com/AOEpeople/MageTestStand.git ${BUILDENV}
cp -rf ${WORKSPACE} ${BUILDENV}/.modman/
${BUILDENV}/install.sh

cd ${BUILDENV}/htdocs/tests
php phpunit.phar --colors -d display_errors=1 ../app/code/community/Codex/Xtest/Test/
