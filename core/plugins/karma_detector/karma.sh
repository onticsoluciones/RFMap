#!/bin/bash
# Plugin Karma Detector

probe=`shuf -n 1 frutas.lst``shuf -n 1 frutas.lst` # Generate random probe from file

/usr/sbin/mdk3 wlan0mon p -e $probe &

/usr/sbin/tcpdump -l -i wlan0mon -e -s 256 type mgt subtype probe-resp  2>/dev/null| grep $probe | while read b; do
    datos=( $b )
    echo [{"entity_id":${datos[10]},"name":${datos[21]},"frequency":0,"bandwidth":20.00,"rssi":0,"extra":{"Karma":"1"}]
    break
done
killall -9 mdk3
