#!/usr/bin/env python
import subprocess
import time
import re
import json

def main():

    with open("/tmp/blescan", "w+") as f:
        btmon_devices=subprocess.Popen(['btmon'], stdout=f) #, shell=True, stdout=subprocess.PIPE,preexec_fn=os.setsid)
        macs=subprocess.Popen(['/usr/bin/hcitool', 'lescan'], shell=False, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        time.sleep(7)
        macs.kill()
        btmon_devices.kill()
        f.close()

    with open("/tmp/blescan", "r+") as f:
        devices=f.read()
        
    partes1 = re.split("HCI Event",devices)
    partesValidas = [parte for parte in partes1 if isValid(parte)]
    devices = [parseDevice(parte) for parte in partesValidas]
    
    # Filtramos duplicados
    map = {}
    for device in devices:
        map[device['entityid']] = device
        
    # Sacamos resultados por stdout
    print(json.dumps(map.values()))
        

def isValid(data):
    datapoints = 0
    for line in data.splitlines():
        if "Address:" in line or \
            "Name (complete):" in line or \
            "RSSI:" in line:
            datapoints += 1
    return datapoints == 3

def parseDevice(data):
    device = {}
    device['frequency'] = 2400
    device['bandwidth'] = 1
    device['extra'] = []
    for line in data.splitlines():
        if "Address:" in line:
            device['entityid'] = line.strip().split()[1]
        if "Name (complete):" in line:
            device['name'] = line.strip().split(":")[1].strip()
        if "RSSI:" in line:
            device['rssi'] = float(line.strip().split()[1])
            
    return device
    
    
if __name__ == '__main__':
    main()


#HCI Event:([\s\S]*)[\s\S]*>
#[\s\S]*Address: ([0-9A-F]{2}(?::[0-9A-F]{2}){5})[\s\S]*Name \(complete\): (.*)[\s\S]*RSSI: (-\d*) dBm

#    f.close()
