#!/usr/bin/env bash
#
# Scan wifi devices
nmcli_output=$(nmcli --mode multiline dev wifi)

# filter
network=$(echo "$nmcli_output" | grep -w SSID: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//")
mode=$(echo "$nmcli_output" | grep MODO: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//" )
chan=$(echo "$nmcli_output" | grep FREC: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//" )
rate=$(echo "$nmcli_output" | grep TASA: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//" )
signal=$(echo "$nmcli_output" | grep SEÑAL: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//" )
bssid=$(echo "$nmcli_output" | grep BSSID: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//" )
security=$(echo "$nmcli_output" | grep SEGURIDAD: \
	| cut -d" " -f 2- | sed "s/^[ \t]*//" )


# generate json
#echo "[" > /tmp/gen_json
network_iter=0
for i in $(echo $network | sed "s/,/ /g")
do
  network_iter=$(expr $network_iter + 1)
  
  # Format data
   network_instance=$(echo $network | cut -d"," -f $network_iter)
   mode_instance=$(echo $mode | cut -d" " -f $network_iter)
   chan_instance=$(echo $chan | cut -d" " -f $network_iter)
   rate_instance=$(echo $rate | cut -d" " -f $network_iter)
   signal_instance=$(echo $signal | cut -d" " -f $network_iter)
   bssid_instance=$(echo $bssid | cut -d" " -f $network_iter)
   security_instance=$(echo $security | cut -d" " -f $network_iter)

# echo $network_instance
# echo $mode_instance
# echo $chan_instance
# echo $rate_instance
# echo $signal_instance
# echo $bssid_instance
# echo $security

  # Parse into json
  json="{
    \"bandwidth\": \"$chan_instance\",
    \"entity_id\": \"$bssid_instance\",
    \"extra:\": \"\",
    \"key1\": \"$rate_instance\",
    \"key2\": \"$network_instance\",
    \"key3\": \"Encryption $security_instance\"
    \"frequency\": \"$bars_instance\",
    \"name\": \"Wifi Network $network_iter\",
    \"rssi\": \"-$signal_instance\"
  }"
  echo "$json", >> /tmp/wifi_json
done

# show in console
cat /tmp/wifi_json

#remove after output
#rm /tmp/wifi_json



