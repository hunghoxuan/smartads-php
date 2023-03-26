# SERVER SETUP

## Software Requirements:
- CentOS/RHEL 6.10 
- PHP 7.1 (not compatible with PHP 7.2 or above)
- MySql 8.0

## Configuration information:
- Apache: 80, 433
- MySql: 3306
- Root folder: /var/www/html
- Database name: stech_smartads
- Database user: stech_smartads / <password>

## Step 0: PREPARE  
###### 01. Install yum-utils and enable EPEL repository  
sudo yum install epel-release yum-utils -y  
sudo yum –y update  
sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-7.rpm  

###### 02. If you are running a firewall, run the following commands to allow HTTP and HTTPS traffic:  
sudo firewall-cmd --permanent --zone=public --add-service=http  
sudo firewall-cmd --permanent --zone=public --add-service=https  
sudo firewall-cmd --reload  

## STEP 1: INSTALL PHP
###### 01. Install PHP 7.1 on CentOS 7  
sudo yum install -y --enablerepo=remi-php71 php php-cli  

###### 02. Install PHP extensions  
sudo yum install -y --enablerepo=remi-php71 php-mysqlnd php-dom php-simplexml php-ssh2 php-xml php-xmlreader php-curl php-date php-exif php-filter php-ftp php-gd php-hash php-iconv php-imagick php-json php-libxml php-mbstring php70w-opcache php-openssl php-pcre php-posix php-sockets php-spl php-tokenizer php-zlib  

## Step 2: Install Apache
###### 01. Install Apache
sudo yum install httpd -y  

###### 02. Verify Apache status
sudo systemctl status httpd

###### 03. Start Apache on your VPS:  
sudo systemctl start httpd.service  

###### 04. Enable Apache to start on boot.  
sudo systemctl enable httpd.service  

###### 05. Restart Apache  
sudo systemctl restart httpd.service  

###### 06. Turn on mode write Apache
sudo vim /etc/httpd/conf/httpd.conf
Find tag /var/www/html
Set AllowOverride All 


## STEP 3: INSTALL MYSQL
###### 01. Install Mysql
sudo yum install mariadb-server mariadb  

###### 02. Start Mysql  
sudo systemctl start mariadb  

###### 03. Setup password for root user. Default password is blank, just press <Enter>. Select <Y> if you want to set password. <Remember your Mysql Root Password>  
sudo mysql_secure_installation  


## STEP 4: SETUP SOURCE CODE  

#### 01. Clone source code from Git  
cd /var/www/html/  
sudo git clone https://gitlab.com/mozasolution/SmartAds-Stech-PHP.git

#### 02. Install PhpMyadmin: https://www.phpmyadmin.net/  
sudo yum -y install phpmyadmin  

#### 03. Setup database  
###### - Enter into MySQL using "root" user identified by password  
mysql -u root -p  

###### - Create the database  
mysql> CREATE DATABASE stech_smartads;  

###### - Create the user  
mysql> CREATE USER 'stech_smartads'@'localhost' IDENTIFIED BY 'newpass';  

###### - Grant ALL privileges onthe new database to the new User  
mysql> GRANT ALL PRIVILEGES ON stech_smartads.* TO 'stech_smartads'@'localhost' IDENTIFIED BY 'newpass';  

###### - Switch to the New Database  
mysql> USE stech_smartads;  

###### - Insert data from /applications/smartads-stech-php/setup/all.sql  
mysql> SOURCE /var/www/html/backend/applications/smartads-stech-php/setup/all.sql  

###### Config
- Make sure the application folder is: smartads-stech-php (required)
The admin url must be like this: http://<ip:port>/smartads-stech-php/backend/web/index.php
- Change config files: /config/main-local.php --> main.php, /config/global-local.php --> global.php
- in main.php, make sure 'db' => 'stech_smartads' db.

###### Set permissions for following folders
- cd smartads-stech-php
- setenforce 0
- chown -R apache backend/web/assets
- chown -R apache backend/runtime
- chmod -R 775 backend/runtime
- chmod -R 775 backend/web/assets
- chmod -R 775 applications/smartads/upload


## STEP 6: REDIS SETUP & CONFIG (OPTIONAL) 
###### 01. Install the Redis package by typing:
sudo yum install redis

###### 02. start the Redis service and enable it to start automatically on boot with:
sudo systemctl start redis  
sudo systemctl enable redis  

###### 03. To check the status of the service enter the following command:      
sudo systemctl status redis

###### 04. Config Redis to accept remote connections open the Redis configuration file with your text editor:      
sudo nano /etc/redis.conf 

###### 05. Locate the line that begins with bind 127.0.0.1 and add your server private IP address after 127.0.0.1.
bind 127.0.0.1 [Your server private IP address]  

###### 06. Restart the Redis service for changes to take effect:
sudo systemctl restart redis

###### 07. Verify that the Redis server is listening on your private interface on port 6379
ss -an | grep 6379

###### 08. Assuming you are using FirewallD to manage your firewall and you want to allow access from the 192.168.121.0/24 subnet you would run the following commands:
sudo firewall-cmd --new-zone=redis --permanent  
sudo firewall-cmd --zone=redis --add-port=6379/tcp --permanent  
sudo firewall-cmd --zone=redis --add-source=192.168.121.0/24 --permanent  
sudo firewall-cmd --reload 

###### 09. Change Config in /config/main.php (line 37)
 'session' =>  ['class' => 'yii\redis\Session' ],














