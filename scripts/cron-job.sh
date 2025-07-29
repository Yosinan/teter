# set up a cron job to automate the backup script
(crontab -l 2>/dev/null; echo "0 3 * * * /home/deployer/scripts/backup.sh") | crontab -