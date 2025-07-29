# Deployment & SSL Guide: Laravel + React/Next + Traefik

## 1. DNS Setup

- Point your domain(s) to your server’s public IP:
  - `api.yourdomain` → API (Laravel)
  - `www.yourdomain` → Frontend (React/Next)

## 2. Traefik & SSL

- Traefik automatically provisions SSL certificates via Let’s Encrypt.
- Make sure ports 80 and 443 are open on your server.
- Traefik stores certificates in `/opt/traefik/acme.json` (set permissions to 600).

## 3. Environment Variables

- Edit `.env` and `.env.local` with your production values.
- Use strong passwords for DB and Redis.

## 4. Building & Running

```bash
docker-compose up -d --build
```

- Traefik will route traffic and handle SSL.
- Access Traefik dashboard at `http://your-server-ip:8080` (optional).

## 5. Backup & Cron Job

- **Backup:**  
  Inside `scripts/`, run `./backup.sh` to backup your database and files.
- **Cron Job:**  
  Set up a cron job to automate backups:
  ```bash
  (crontab -l 2>/dev/null; echo "0 3 * * * * /home/deployer/scripts/backup.sh") | crontab -
  ```
- **Backup Location:**  
  Backups are stored in `/backups` directory on your server.
- **Backup Cleanup:**  
  The script keeps backups for the last 7 days and deletes older files.


## 6. Updating the Stack

- Pull latest code:  
  `git pull origin main`
- Rebuild containers:  
  `docker-compose up -d --build`

## 7. Logs & Monitoring

- View logs:  
  `docker-compose logs -f`
- Monitor Traefik dashboard for routing/SSL issues.

## 8. Troubleshooting

- Check container health:  
  `docker ps -a`
- Restart a service:  
  `docker-compose restart app`
- Check SSL:  
  `docker-compose logs traefik`

## 9. Security Tips

- Keep your server and Docker images up to date.
- Use strong secrets in `.env`.
- Restrict Traefik dashboard access in production.

---

For more details, see [Traefik Documentation](https://doc.traefik.io/traefik/) and [Laravel Deployment](https://laravel.com/docs/deployment).