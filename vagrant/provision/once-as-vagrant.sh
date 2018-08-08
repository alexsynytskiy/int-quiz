#!/usr/bin/env bash

#== Import script args ==

github_token=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Add link to project in home directory"
ln -s /var/www/intellias /home/vagrant/intellias
echo "Done!"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install codeception"
composer global require "codeception/codeception=2.0.*" "codeception/specify=*" "codeception/verify=*" --no-progress
echo 'export PATH=/home/vagrant/.config/composer/vendor/bin:$PATH' | tee -a /home/vagrant/.profile

info "Install project dependencies"
cd /var/www/intellias

info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /var/www/intellias"' | tee /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc

info "Install phpMyAdmin"
cd /var/www/
sudo -H composer create-project phpmyadmin/phpmyadmin --repository-url=https://www.phpmyadmin.net/packages.json --no-dev
sudo chown -R vagrant:vagrant phpmyadmin
