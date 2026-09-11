#!/bin/sh
nohup php artisan serve >> /dev/null 2>&1 &
nohup npm run dev >> /dev/null 2>&1 &
nohup php artisan queue:work >> /dev/null 2>&1 &
nohup php artisan reverb:start >> /dev/null 2>&1 &