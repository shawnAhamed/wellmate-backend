#!/bin/bash
# Azure App Service (Linux) resets /etc/nginx on every container start, so
# this re-applies our custom config (document root -> public/, plus the
# try_files rewrite Laravel's routing needs) before nginx serves traffic.
cp /home/site/wwwroot/azure-nginx.conf /etc/nginx/sites-available/default
service nginx reload
