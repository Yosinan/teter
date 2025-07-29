# Fresh Server Setup Guide: Laravel + React/Next + Traefik

This guide walks you through setting up a new Ubuntu server for your full-stack Dockerized application.

---

## 1. System Preparation

Update and install required packages:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install docker.io docker-compose git curl -y
```

---

## 2. Traefik SSL Storage

Create storage for Let's Encrypt certificates:
```bash
sudo mkdir -p /opt/traefik
sudo touch /opt/traefik/acme.json
sudo chmod 600 /opt/traefik/acme.json
```

---

## 3. Clone the Repository

```bash
git clone https://github.com/yourusername/your-repo.git
cd your-repo
```

---

## 4. Environment Configuration

Copy and edit environment files:
```bash
cp .env.example .env
```
- Edit `.env` for backend (Laravel):  
  Set database credentials, app URLs, Redis host, etc.
- (Optional) Edit `.env.local` for frontend (React/Next):  
  Set API endpoint and other variables.

---

## 5. Docker Setup

Build and start all services:
```bash
docker-compose up -d --build
```

---

## 6. Database Migration & Seeding

Run migrations and seed the database:
```bash
docker-compose exec app php artisan migrate --seed
```

---

## 7. Accessing the Applications

- **Laravel API:**  
  Visit `http://api.yourdomain`
- **React Client:**  
  Visit `http://www.yourdomain`
- **Traefik Dashboard:**  
  Visit `http://localhost:8080` (or your server's IP)

---

## 8. Backup & Restore

- **Backup:**  
  Run `./backup.sh` to backup database and files.
- **Restore:**  
  (Add your restore instructions here if needed.)

---

## 9. Useful Docker Commands

- View logs:  
  `docker-compose logs -f`
- Stop all services:  
  `docker-compose down`
- Rebuild containers:  
  `docker-compose build --no-cache`
- Access Laravel container shell:  
  `docker-compose exec app bash`
- Access Frontend container shell:  
  `docker-compose exec frontend bash`

---

## 10. Troubleshooting

- Check container status:  
  `docker ps -a`
- Restart a service:  
  `docker-compose restart app`
- Clear Laravel cache:  
  `docker-compose exec app php artisan config:cache`
- Check Traefik logs:  
  `docker-compose logs traefik`

---

## 11. Security Tips

- Use strong passwords in `.env`
- Keep your server and Docker images up to date
- Restrict Traefik dashboard access in production

---

For deployment and SSL tips, see [`DEPLOY.md`](DEPLOY.md).