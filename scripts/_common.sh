#!/bin/bash

# App package root directory should be the parent folder
PKG_DIR=$(cd ../; pwd)

# Create a system user
#
# usage: ynh_system_user_create user_name [home_dir]
# | arg: user_name - Name of the system user that will be create
# | arg: home_dir - Path of the home dir for the user. Usually the final path of the app. If this argument is omitted, the user will be created without home
ynh_system_user_create () {
    if ! ynh_system_user_exists "$1"    # Check if the user exists on the system
    then    # If the user doesn't exist
        if [ $# -ge 2 ]; then   # If a home dir is mentioned
            user_home_dir="-d $2"
        else
            user_home_dir="--no-create-home"
        fi
        sudo useradd $user_home_dir --system --user-group $1 --shell /usr/sbin/nologin || ynh_die "Unable to create $1 system account"
    fi
}

# Delete a system user
#
# usage: ynh_system_user_delete user_name
# | arg: user_name - Name of the system user that will be create
ynh_system_user_delete () {
    if ynh_system_user_exists "$1"  # Check if the user exists on the system
    then
        sudo userdel $1
    else
        echo "The user $1 was not found" >&2
    fi
}

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
# Download all sources files
# source must bt ID-SourceName.src
# Sources fies should be included src file in "sources" dir
# named like NUMBER-SourceName.src
# src file must set SOURCE_URL, SOURCE_FILE and SOURCE_DEST
# @todo : local source
# @todo : other file type
# @todo : more separation (adding function)
# example: ynh_get_source "/var/www/gnusocial/"
#
# usage: ynh_get_source DEST_DIR [USER]
ynh_get_source () {
    local DEST=$1
    local AS_USER=${2:-admin}
    mkdir -p "${DEST}"
    sudo chown $AS_USER: "${DEST}"
    # @todo local LOCAL_SOURCE="/opt/yunohost-apps-src/$YNH_APP_ID/"
    if [ $(find ${PKG_DIR}/sources/ -type f -name "*.src"  | wc -l) ]; then
        (for appsource in ${PKG_DIR}/sources/*.src; do \
            source "${appsource}"; \
            ynh_exec_as "$AS_USER" mkdir -p "${DEST}${SOURCE_DEST}"; \
            wget -nv $SOURCE_URL -O "${SOURCE_FILE}"; \
            ynh_exec_as "$AS_USER" tar xf "${SOURCE_FILE}" -C "${DEST}${SOURCE_DEST}" --strip-components 1; \
            rm "${SOURCE_FILE}"; \
        done) || ynh_die "Unable to dowload source"
    fi
}
