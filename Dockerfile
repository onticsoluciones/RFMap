FROM ubuntu:latest
RUN apt update
RUN apt install git php7.0-zip composer nginx php-sqlite3 -y
RUN cd /home && git clone https://github.com/onticsoluciones/RFMap.git
RUN cd /home/RFMap && composer install && cp phinx.yml.dist phinx.yml && vendor/bin/phinx migrate && vendor/bin/phinx migrate
RUN cd /home/RFMap/core && composer install
RUN cd /home/RFMap && git pull --ff-only
RUN cd /home/RFMap/webservices && composer install
RUN apt install network-manager -y
EXPOSE 8080 9200
ENTRYPOINT bash -c '/home/RFMap/core/rfmap.php & php -S 0.0.0.0:9200 -t /home/RFMap/webservices & php -S 0.0.0.0:8080 -t /home/RFMap/ui'
