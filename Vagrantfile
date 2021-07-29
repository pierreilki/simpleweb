$nfs = <<-SCRIPT
apt update
apt install nfs-kernel-server -yqq
mkdir -p /mnt/data
chown nobody:nogroup /mnt/data
chmod 777 /mnt/data
echo "/mnt/data *(rw,insecure,sync,no_subtree_check,no_root_squash)" > /etc/exports
exportfs -a
systemctl restart nfs-kernel-server
cd /mnt/data
git clone https://github.com/pierreilki/simpleweb.git
chmod 777 -R /mnt/data/simpleweb
SCRIPT

$simpleweb = <<-SCRIPT
apt update
apt-get install nfs-common -yqq
mkdir -p /mnt/data
mount 10.100.20.50:/mnt/data /mnt/data
apt install apache2 php -yqq
export BDD_HOST=10.100.20.53
export BDD_USERNAME=simpleweb
export BDD_PASSWORD=mysecretpass
export BDD_DATABASE=simpleweb

echo "
export BDD_HOST=10.100.20.53
export BDD_USERNAME=simpleweb
export BDD_PASSWORD=mysecretpass
export BDD_DATABASE=simpleweb
" >> /etc/bash.bashrc

apt install apache2 php php-mysql -yqq

echo "
<VirtualHost *:80>
  ServerAdmin webmaster@ilki.fr
  DocumentRoot /mnt/data/simpleweb
  ErrorLog \${APACHE_LOG_DIR}/error.log
  CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
" > /etc/apache2/sites-available/000-default.conf

echo "
<Directory /mnt/data/simpleweb>
        Options Indexes FollowSymLinks
		AllowOverride All
		Require all granted
</Directory>
<Directory /mnt/data/simpleweb/config>
        Options Indexes FollowSymLinks
		AllowOverride All
		Require all granted
</Directory>
<Directory /mnt/data/simpleweb/css>
        Options Indexes FollowSymLinks
		AllowOverride All
		Require all granted
</Directory>
<Directory /mnt/data/simpleweb/templates>
        Options Indexes FollowSymLinks
		AllowOverride All
		Require all granted
</Directory>
" >> /etc/apache2/apache2.conf

service apache2 restart

SCRIPT


$bdd = <<-SCRIPT
apt update
apt install mysql-server -yqq
sed -i 's/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf
service mysql restart
mysql -e 'CREATE DATABASE simpleweb;'
mysql -e 'GRANT ALL PRIVILEGES ON simpleweb.* TO "simpleweb"@"localhost" IDENTIFIED BY "mysecretpass";'
mysql -e 'GRANT ALL PRIVILEGES ON simpleweb.* TO "simpleweb"@"%" IDENTIFIED BY "mysecretpass";'
cd /tmp
wget https://raw.githubusercontent.com/pierreilki/simpleweb/master/dump-simpleweb.sql
mysql simpleweb < dump-simpleweb.sql

SCRIPT

$lb = <<-SCRIPT
apt update
apt install nginx -yqq
cd /etc/nginx/conf.d
wget https://raw.githubusercontent.com/pierreilki/simpleweb/master/lb.conf
rm /etc/nginx/sites-enabled/default
service nginx restart
SCRIPT

Vagrant.configure("2") do |config|
    config.vm.box = "bento/ubuntu-18.04"
	config.vm.define "nfs" do |nfs|
		nfs.vm.provision "shell", inline: $nfs
		nfs.vm.hostname = "nfs"
		nfs.vm.network "private_network", ip: "10.100.20.50"
		nfs.vm.provider "virtualbox" do |v|
			v.memory = 1096
			v.cpus = 1
			v.name = "nfs"
		end
	end
	config.vm.define "bdd" do |bdd|
		bdd.vm.provision "shell", inline: $bdd
		bdd.vm.hostname = "bdd"
		bdd.vm.network "private_network", ip: "10.100.20.53"
		bdd.vm.provider "virtualbox" do |v|
			v.memory = 1096
			v.cpus = 1
			v.name = "bdd"
		end
	end
	config.vm.define "simpleweb1" do |simpleweb1|
		simpleweb1.vm.provision "shell", inline: $simpleweb
		simpleweb1.vm.hostname = "simpleweb1"
		simpleweb1.vm.network "private_network", ip: "10.100.20.70"
		simpleweb1.vm.provider "virtualbox" do |v|
			v.memory = 1096
			v.cpus = 1
			v.name = "simpleweb1"
		end
	end
	config.vm.define "simpleweb2" do |simpleweb2|
		simpleweb2.vm.provision "shell", inline: $simpleweb
		simpleweb2.vm.hostname = "simpleweb2"
		simpleweb2.vm.network "private_network", ip: "10.100.20.71"
		simpleweb2.vm.provider "virtualbox" do |v|
			v.memory = 1096
			v.cpus = 1
			v.name = "simpleweb2"
		end
	end
	config.vm.define "simpleweb3" do |simpleweb3|
		simpleweb3.vm.provision "shell", inline: $simpleweb
		simpleweb3.vm.hostname = "simpleweb3"
		simpleweb3.vm.network "private_network", ip: "10.100.20.72"
		simpleweb3.vm.provider "virtualbox" do |v|
			v.memory = 1096
			v.cpus = 1
			v.name = "simpleweb3"
		end
	end
	config.vm.define "lb" do |lb|
		lb.vm.provision "shell", inline: $lb
		lb.vm.hostname = "lb"
		lb.vm.network "private_network", ip: "10.100.20.60"
		lb.vm.provider "virtualbox" do |v|
			v.memory = 1096
			v.cpus = 1
			v.name = "lb"
		end
	end
end
