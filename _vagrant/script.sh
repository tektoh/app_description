#!/bin/sh

VAGRANT_DIR=/vagrant
SHARE_DIR=/share
CAKE_DIR=/var/www/html/cakephp

mkdir -p $CAKE_DIR

#
# iptables off
#
/sbin/iptables -F
/sbin/service iptables stop
/sbin/chkconfig iptables off


#
# yum repository
#
rpm -ivh http://ftp.riken.jp/Linux/fedora/epel/6/i386/epel-release-6-8.noarch.rpm
rpm -ivh http://dl.iuscommunity.org/pub/ius/stable/CentOS/6/x86_64/ius-release-1.0-11.ius.centos6.noarch.rpm
#yum -y update


#
# ntp
#
yum -y install ntp
/sbin/service ntpd start
/sbin/chkconfig ntpd on


#
# php
#
yum -y install php54 php54-cli php54-pdo php54-mbstring php54-mcrypt php54-pecl-memcache php54-mysql php54-devel php54-common php54-pgsql php54-pear php54-gd php54-xml php54-pecl-xdebug php54-pecl-apc
touch /var/log/php.log && chmod 666 /var/log/php.log
cp -a $VAGRANT_DIR/php.ini /etc/php.ini


#
# Apache
#
cp -a $VAGRANT_DIR/httpd.conf /etc/httpd/conf/
/sbin/service httpd restart
/sbin/chkconfig httpd on


#
# MySQL
#
yum -y install http://repo.mysql.com/mysql-community-release-el6-4.noarch.rpm
yum -y install mysql-community-server
/sbin/service mysqld restart
/sbin/chkconfig mysqld on

mysql -u root -e "create database app default charset utf8"
mysql -u root -e "create database test_app default charset utf8"


#
# Composer
#
if [ ! -f /$CAKE_DIR/composer.json ]; then
	cp -a $VAGRANT_DIR/composer.json $CAKE_DIR/composer.json
  cd $CAKE_DIR && curl -s http://getcomposer.org/installer | php
  /usr/bin/php /$CAKE_DIR/composer.phar install --dev
  # cakephp
  yes | php $CAKE_DIR/vendor/cakephp/cakephp/lib/Cake/Console/cake.php bake project app
  cp -a $VAGRANT_DIR/database.php $CAKE_DIR/app/Config/database.php
  cp -a $VAGRANT_DIR/bootstrap.php $CAKE_DIR/app/Config/bootstrap.php
	ln -s -f $SHARE_DIR $CAKE_DIR/app/Plugin/AppDescription
else
	cd $CAKE_DIR && /usr/bin/php $CAKE_DIR/composer.phar update
fi
