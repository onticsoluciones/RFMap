
#RFMap - Radio Frequency Mapper

----------


#Table of Contents
* [Description](https://github.com/onticsoluciones/RFMap#description)
* [Technology](https://github.com/onticsoluciones/RFMap#technology-used)
* [Features](https://github.com/onticsoluciones/RFMap#features)
* [Installation](https://github.com/onticsoluciones/RFMap#installation)
* [Usage](https://github.com/onticsoluciones/RFMap#usage)
* [License](https://github.com/onticsoluciones/RFMap/blob/master/LICENSE)
* [Credits](https://github.com/onticsoluciones/RFMap#credits)

#Description

RFMap is a tool for detection and monitoring of networks and wireless devices such Wifi, Bluetooth, IoT devices, GSM and other RF devices. 
It uses a combination of software and low-cost hardware (SDR) which allows to make scans in diverse frequencies and to show it in a GUI to analyze the collected data.

#Technology used

- [x] PHP
- [x] GnuRadio
- [x] RTL-SDR
- [x] Python
- [x] SQLite

#Features

 - Detection of networks and devices 
 - Inventory 
 - Monitoring / Time conditions
 - Identification of protocols and services     
 - New devices notification/ Alerts      
 - Optimization of spectrum use     
 - Deployment planning

#Installation

After cloning the repository, download Composer:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Install the dependencies:

```bash
php composer.phar install
```

Copy phinx.yml.dist to phinx.yml:

```bash
cp phinx.yml.dist phinx.yml
```

You can edit the database path if desired, by default it will be created in:

```
./data/rfmap.sqlite
```

Run the schema migration tool to create the database:

```bash
vendor/bin/phinx migrate
```

#Usage

#Credits

- Python -> https://www.python.org/
- PHP -> https://php.net/images/logo.php

#Contributors

* [Alfonso Moratalla](https://github.com/alfonsomoratalla) -> [Twitter](https://twitter.com/alfonso_ng)
* [Alejandro Sánchez](https://github.com/alsanchez) -> [Twitter](https://twitter.com/alsanchez_)
* [Ricardo Monsalve](https://github.com/ricarmon) -> [Twitter](https://twitter.com/ricarmonsalve)
* [Germán Sánchez](https://github.com/yercito) -> [Twitter](https://twitter.com/yeroncio)
