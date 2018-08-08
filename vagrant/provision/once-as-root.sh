#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Allocate swap for MySQL"
fallocate -l 2048M /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap defaults 0 0' >> /etc/fstab

info "Configure locales"
update-locale LC_ALL="C"
dpkg-reconfigure locales

info "Configure timezone"
echo ${timezone} | tee /etc/timezone
dpkg-reconfigure --frontend noninteractive tzdata

info "Update OS software"
add-apt-repository -y ppa:ondrej/php
apt-get update -y -qq
apt-get install -y python-software-properties openssl libssl-dev libssl-dev build-essential
echo "Done!"

info "Install additional software"
apt-get install -y git vim screen curl unzip memcached redis-server supervisor mc grc wget swftools poppler-utils htop cron
echo "Done!"

info "Install Nginx"
apt-get install -y nginx
echo "Done!"

info "Install MySQL"
debconf-set-selections <<< "mariadb-server mysql-server/root_password password \"'vagrant'\""
debconf-set-selections <<< "mariadb-server mysql-server/root_password_again password \"'vagrant'\""
apt-get install -y mariadb-server
echo "Done!"

info "Install PHP"
apt-get install -y php5.6 php5.6-fpm php5.6-cli php5.6-common php5.6-intl php5.6-json php5.6-mysql php5.6-gd php5.6-imagick php5.6-curl php5.6-mcrypt php5.6-xdebug php5.6-redis php5.6-memcache php5.6-imap php5.6-mbstring php5.6-dom php5.6-soap php5.6-zip
echo "Done!"

info "Install NodeJS and NPM"
apt-get -y install npm
curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
apt-get install -y nodejs
echo "Done!"

info "Install PhantomJs"
npm install -g phantomjs
echo "Done!"

info "Install CasperJs"
npm install -g casperjs
echo "Done!"

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
echo "Done!"

info "Configure PHP-FPM"
mv /etc/php/5.6/fpm/php.ini /etc/php/5.6/fpm/php.ini.dmp
ln -s /var/www/intellias/vagrant/php/fpm/php.ini /etc/php/5.6/fpm/php.ini
mv /etc/php/5.6/fpm/pool.d/www.conf /etc/php/5.6/fpm/pool.d/www.conf.dmp
ln -s /var/www/intellias/vagrant/php/fpm/pool.d/www.conf /etc/php/5.6/fpm/pool.d/www.conf
sed -i 's/user = www-data/user = vagrant/g' /etc/php/5.6/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/5.6/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/5.6/fpm/pool.d/www.conf
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /var/www/intellias/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Enabling xdebug configuration"
mv /etc/php/5.6/mods-available/xdebug.ini /etc/php/5.6/mods-available/xdebug.ini.dmp
cp /var/www/intellias/vagrant/php/mods-available/xdebug.ini /var/www/intellias/vagrant/php/mods-available/xdebug-local.ini
ln -s /var/www/intellias/vagrant/php/mods-available/xdebug-local.ini /etc/php/5.6/mods-available/xdebug.ini
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
