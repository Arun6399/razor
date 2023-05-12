#!/bin/bash
while true
do wget -qO - https://www.moonexchange.nl/api_orders > /dev/null 2>&1
sleep 600
done
