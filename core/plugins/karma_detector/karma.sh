#!/bin/bash
# Plugin Karma Detector

/usr/sbin/mdk3 wlan0mon p -e prueba__karma1 &

/usr/sbin/tcpdump -l -i wlan0mon -e -s 256 type mgt subtype probe-resp or subtype probe-req 2>/dev/null| grep "prueba__karma1" | while read b; do
    datos=( $b )
    echo [{"entity_id":${datos[7]},"name":${datos[12]},"frequency":0,"bandwidth":40.00,"rssi":0,"extra":{"Attack":"Karma"}]
    break
done
killall -9 mdk3
