# RFMap - Radio Frequency Mapper
----------

# Table of Contents
* [Description](https://github.com/onticsoluciones/RFMap#description)
* [Technology](https://github.com/onticsoluciones/RFMap#technology-used)
* [Features](https://github.com/onticsoluciones/RFMap#features)
* [Installation](https://github.com/onticsoluciones/RFMap#manual-installation)
* [Docker Image](https://github.com/onticsoluciones/RFMap#docker-image)
* [Plugins](https://github.com/onticsoluciones/RFMap#available-plugins)
* [Prerequisites](https://github.com/onticsoluciones/RFMap#prerequisites)
* [License](https://github.com/onticsoluciones/RFMap/blob/master/LICENSE)

# Description

RFMap is a tool for detection and monitoring of networks and wireless devices such Wifi, Bluetooth, IoT devices, GSM and other RF devices.
It uses a combination of software and low-cost hardware (SDR) which allows to make scans in diverse frequencies and to show it in a GUI to analyze the collected data and find malicious devices, avoid data leaking without consent, find vulnerabilities of discovered devices and hardware inventory. 

# Technology used

- [x] PHP
- [x] JQuery
- [x] GnuRadio
- [x] RTL-SDR
- [x] Python
- [x] SQLite
- [x] Phinx
- [x] BootStrap

# Features

 - Detection of malicious devices (e.g. WiFi Pineapple, hidden webcams, spy microphones). 
 - Vulnerability /Attack detection.
 - Avoid data filtration.
 - Detection of networks and devices.
 - Inventory and monitoring.
 - Identification of protocols and services.     
 - New devices notification/ Alerts.      
 - Optimization of spectrum use.     

# Manual Installation

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
# Docker Image
Build the image with:
```bash
docker build -t rfmap:latest 
```

Run it with:
```bash
docker run -p 8080:8080 -p 9200:9200 rfmap
```
And the web interface should be accesible from http://localhost:8080

# Available Plugins

## pmr_scan

PMR 446 MHz power channel analyzer: scans the 8 PMR channels and returns its power (DBi).

## karma_detector
Detect if anything is making Karma Attack.

## wifi_scanner
Wifi 2.4/5Ghz analyzer: scans and returns  ssid, bssid, rssi, frequency and other relevant information.

## ble_scanner
Bluetooth analyzer: scans BLE devices in enviroment and provide information like bssid, name and rssi.

## gsm_scan
Scan GMS devices like BTS

### Prerequisites

In order to prepare your environment to build from the sources you have to first install a couple of development libraries.
This step is needed only the first time.

    sudo apt-get install libfftw3-dev libtclap-dev librtlsdr-dev

### Installation

    git clone https://github.com/AD-Vega/rtl-power-fftw.git
    cd rtl-power-fftw
    mkdir build
    cd build
    cmake ..
    make
    make install

# Contributors

* [Alfonso Moratalla](https://github.com/alfonsomoratalla) -> [Twitter](https://twitter.com/alfonso_ng)
* [Alejandro Sánchez](https://github.com/alsanchez) -> [Twitter](https://twitter.com/alsanchez_)
* [Ricardo Monsalve](https://github.com/ricarmon) -> [Twitter](https://twitter.com/ricarmonsalve)
* [Germán Sánchez](https://github.com/yercito) -> [Twitter](https://twitter.com/yeroncio)
