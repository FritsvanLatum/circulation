#!/bin/bash
# This script runs 30 times the instruction ls .
i="0"
while [ $i -lt 360 ]
do
printf "$i"
printf " "
date
sleep 1
ls -rt /var/www/html/circulation/pulllist/tickets/tobeprinted/* &
i=$[$i+1]
sleep 29
done


