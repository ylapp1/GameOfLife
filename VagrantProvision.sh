#!/usr/bin/env bash

# Package list
packages=(

  # PHP7
  php7.0-common php7.0-cli

  ## The zip package is needed for composer
  php7.0-zip

  ## phpunit dependencies
  php7.0-xml php7.0-mbstring php-xdebug

  ## ImageOutput dependencies
  php7.0-gd

  # VideoOutput depencies
  ffmpeg

  # doxygen
  doxygen graphviz
)

apt-get update
apt-get install -y "${packages[@]}"


# Install composer
cd /usr/local/bin
EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --quiet --filename=composer
RESULT=$?
rm composer-setup.php
chmod a+x composer
cd


# Fixing the timezone

# Change the system timezone
timedatectl set-timezone Europe/Berlin

# Change the php timezone (fixes time for cli commands)
sed -i "/;date.timezone =/c date.timezone = \"Europe/Berlin\"" /etc/php/7.0/cli/php.ini


# Fixes the bug that backspace prints a character in a CMD vagrant ssh session
echo "stty sane" >> /home/ubuntu/.bashrc
