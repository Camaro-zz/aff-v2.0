

```
lnmp下：增加步骤
在lnmp的解压目录下的src中找到当前版本的php压缩包，并解压
cd至php5.6安装文件夹下的ext下的fileinfo
cd /root/lnmp1.3-full/php-5.6.22/ext/fileinfo
/usr/local/php/bin/phpize
./configure --with-php-config=/usr/local/php/bin/php-config
make && make install

修改php.ini 新增extension = fileinfo.so
-------------------------------
Linux（Ubuntu）下安装NodeJs
安装nodeJS之前，如果没有安装g++及 libssl-dev，则先要安装好，安装方法如下：
$ sudo apt-get install g++
$ sudo apt-get install libssl-dev

$ wget https://nodejs.org/dist/v4.4.7/node-v4.4.7-linux-x64.tar.xz
$ tar xvf node-v4.4.7-linux-x64.tar.xz
$ ./configure
$ make && make install
-------------------------------
composer install
npm install
php artisan migrate
php artisan db:seed
gulp
php artisan serve
```

