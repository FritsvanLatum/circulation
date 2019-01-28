#!/bin/bash
DESTINY=/var/www/html/circulation/pulllist/tickets/archive/backup-$(date +%Y%m%d%H%S).tgz
sudo find /var/www/html/circulation/pulllist/tickets/printed/*.html -mtime +1 > /var/www/html/circulation/pulllist/tickets/archive/MyListWithFiles
sudo tar -cvzf $DESTINY -T /var/www/html/circulation/pulllist/tickets/archive/MyListWithFiles
find /var/www/html/circulation/pulllist/tickets/printed/*.html -mtime +1 -delete
