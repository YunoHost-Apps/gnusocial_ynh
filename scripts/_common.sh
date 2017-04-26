#!/bin/bash

# App package root directory should be the parent folder
PKG_DIR=$(cd ../; pwd)

# Execute a command as another user
# usage: ynh_exec_as USER COMMAND [ARG ...]
ynh_exec_as() {
  local USER=$1
  shift 1

  if [[ $USER = $(whoami) ]]; then
    eval "$@"
  else
    # use sudo twice to be root and be allowed to use another user
    sudo sudo -u "$USER" "$@"
  fi
}

# Patch existing source by all patch in "patches" dir
# Patches should be located in a "patches" dir, they should be
# named like "SOURCE_ID-*.patch".
#
# example: ynh_patch_source "/var/www/gnusocial/" "gnusocial"
#
# usage: ynh_patch_source DEST_DIR [USER [SOURCE_ID]]
ynh_patch_source () {
    local DEST=$1
    local AS_USER=${2:-admin}
    local SOURCE_ID=${3:-app}

    if [ $(find ${PKG_DIR}/patches/ -type f -name "$SOURCE_ID-*.patch"  | wc -l) ]; then
        (cd "$DEST" \
        && for p in ${PKG_DIR}/patches/$SOURCE_ID-*.patch; do \
            ynh_exec_as "$AS_USER" patch -p1 < $p; done) \
            || ynh_die "Unable to apply patches"
    fi

}
