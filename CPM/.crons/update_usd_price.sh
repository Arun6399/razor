#!/bin/bash

while true
do wget -qO - https://www.moonexchange.nl/update_usd_price > /dev/null 2>&1
sleep 300
done
