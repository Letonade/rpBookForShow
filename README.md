# rpBookForShow
Lack a lot of functionnality volontary. (this is a demo)

# RP Book

## *** INSTALL ***
### Needs:
- the Dockerfile in /docker/php
- the nginx & nginx_phpunit configurations file in their own docker/folder
- the .env
- the docker-compose.yml
++ Makefile and buildspec.yml is a most
  
### Commands :
- 1> docker-compose build
- 2> docker-compose up -d  (-d means to launch it separately)
    - 2+> It can be needed to get the latest docker-compose:
        - docker-compose version 1.29.2, build 5becea4c
        - docker-py version: 5.0.0
        - CPython version: 3.7.10
        - OpenSSL version: OpenSSL 1.1.0l  10 Sep 2019
- 3> sudo apt-get install php-xml
- 4> composer install
- 5> composer update


### Install you'r ubuntu wsl2
We admit that you have wsl2
- 0> in powershell: wsl --unregister Ubuntu
- 1> wsl --install -d Ubuntu
- 2> wsl -s Ubuntu | wsl -l -v
Here ubun should have a *
Then go into you'r Ubuntu

#### DNS resolver (to update apt-get)
Option 1:
- 1> create a workspace in /home/$USER
- 2> first verify you'r : cat /etc/resolv.conf
    - If nameserver 8.8.8.8 don't exist add it with:
    - 2a> sudo echo "nameserver 8.8.8.8" >> /etc/resolv.conf
    - 2b> you also find one in /run/resolvconf/resolv.conf
- 3> You also can do some chmod 644 /etc/resolv.conf

Option 2: (you can have the resolv file autoload every x-min that will erase the problem)
- 1> sudo rm /etc/resolv.conf
- 2> sudo bash -c 'echo "nameserver 8.8.8.8" > /etc/resolv.conf'
- 3> sudo bash -c 'echo "[network]" > /etc/wsl.conf'
- 4> sudo bash -c 'echo "generateResolvConf = false" >> /etc/wsl.conf'
- 5> sudo chattr +i /etc/resolv.conf

#### apt
- 1> sudo apt-get update
- 2> sudo apt update
- 3> sudo apt upgrade

#### git 
- 1> sudo apt install git

#### zsh (optionnal but really usefull)
need apt update and git.
- 1> sudo apt install zsh
- 2> chsh -s $(which zsh)
- 3> close the actual shell then open a new one
    - 3a> now you have what's needed to configure zsh
- 4> q  :yes we quit it to install more thingy
- 5> We install oh-my-zsh with:
sh -c "$(wget https://raw.githubusercontent.com/robbyrussell/oh-my-zsh/master/tools/install.sh -O -)"
- 6> I have a zsh conf for you in  my github
- 7> restart again you'r terminal then you will have everything we want.


#### curl 
need apt
- 1> sudo apt-get install curl
- 2> sudo apt-get install php-curl

#### PHP
need apt
- 1> sudo apt install php7.4
- 2> sudo apt install php7.4-common php7.4-mysql php7.4-xml php7.4-xmlrpc php7.4-curl php7.4-gd php7.4-imagick php7.4-cli php7.4-dev php7.4-imap php7.4-mbstring php7.4-opcache php7.4-soap php7.4-zip php7.4-intl -y
if 7.3 just replace 7.4 in the two commands
- 3> You can switch off and on php ver:
    - 3a> sudo a2dismod php5.6
    - 3b> sudo a2enmod php7.4

#### Zip
- 1> sudo apt install zip

#### composer
Need apt and php
- 1> Do all at one time. 
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
- 2> Set it global:
sudo mv composer.phar /usr/local/bin/composer

#### ntpdate
- 1>  sudo apt-get install ntpdate
- 2> use it like: sudo ntpdate -b time.google.com
It take a little time

#### localtunnel
We need apt
- 1> install node: sudo apt install npm
- 2> get locatunnel global: sudo npm install -g localtunnel
- 3> use it like that: lt --port 80 -s rpbook-local-api

The yargs error need you to update node:
- 4> sudo npm cache clean -f
- 5> sudo npm install -g n
- 6> sudo n stable

#### Docker compose
Just get Docker Desktop you fool
But don't forge those:
- 1> sudo groupadd docker
- 2> sudo usermod -aG docker ${USER}


# Should be okay for Ubuntu

#### Known errors while dockering this
- 1> MongoDB exited with error 14 and mysql with error 1
    - 1a> First do an ntpdate
    - 1b> Go to the docker folder and delete mysql and mongodb folder

- 2> Composer update don't have a mongo extension
    - 2a> look if you have php.ini here: ll /etc/php/7.4/apache2 | grep php.ini
    - 2b> get right : sudo chown -R $USER /etc/php/7.4/apache2/php.ini
    - 2c> Add to him extension = mongodb.so;
you can use : sudo echo "extension = mongodb.so;" >> /etc/php/7.4/apache2/php.ini
- 2d> verify the insertion with : cat /etc/php/7.4/apache2/php.ini |grep mongo
- 2e> can be other things,try: sudo apt-get install php-mongodb 

#### Some useful commands
- > sudo chown -R $USER ./

