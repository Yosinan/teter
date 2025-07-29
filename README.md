# Full-Stack Docker Setup: Laravel + React/Next + Traefik + PostgreSQL + Redis

This repository contains a production-ready, Dockerized stack featuring:

- ✅ Laravel (PHP 8.3) for backend
- ✅ React/Next frontend (Node 20)
- ✅ PostgreSQL for persistent data
- ✅ Redis for caching and queueing
- ✅ Traefik reverse proxy with Let's Encrypt TLS
- ✅ Docker & Docker Compose for orchestration

---

## Project Structure

├── backend/ # Laravel app  
│ └── Dockerfile  
├── client/ # React frontend  
│ └── Dockerfile  
├── docker-compose.yml # Multi-service config  
├── .env.example # Laravel env template  
├── scripts/backup.sh # Backup script  
├── SETUP.md # Fresh server install guide  
├── DEPLOY.md # Deployment + SSL tips  
└── README.md # This file

---

## Tech Stack Overview

| Service    | Purpose             | URL / Port             |
| ---------- | ------------------- | ---------------------- |
| `traefik`  | Reverse proxy + SSL | `:80`, `:443`, `:8080` |
| `app`      | Laravel API         | `api.yourdomain`     |
| `frontend` | React client        | `www.yourdomain`     |
| `db`       | PostgreSQL DB       | Internal only          |
| `redis`    | Redis server        | Internal only          |

---

## Getting Started

For a step-by-step guide to setting up a fresh server, see [`SETUP.md`](SETUP.md).

For deployment, SSL, and production tips, see [`DEPLOY.md`](DEPLOY.md).

---

## Prerequisites

- Ubuntu server (recommended)
- Docker & Docker Compose
- Git

See [`SETUP.md`](SETUP.md) for installation commands and initial server setup.

---

## Setup Instructions (Quick Reference)

1. **Clone the repo**:
   ```bash
   git clone https://github.com/yourusername/your-repo.git
   cd your-repo
   ```
2. **Copy the environment file**:
   ```bash
   cp .env.example .env
   ```
3. **Edit `.env`**:
   - Set your database credentials, app URL, and other environment variables.
4. **Build and start services**:
   ```bash
   docker-compose up -d --build
   ```
5. **Run migrations and seed database**:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```
6. **Access the apps**:
   - Laravel API: `http://api.yourdomain`
   - React Client: `http://www.yourdomain`
7. **Traefik Dashboard** (optional):
   - Access at `http://localhost:8080` (or your server's IP)
8. **SSL Setup**:
   - Traefik will automatically handle Let's Encrypt SSL for your domains.
9. **Backup Script**:
   - Run `./backup.sh` to create a backup of your database and application files.

---

## Environment Variables

See `.env.example` for backend and configure `.env.local` for frontend as needed.

---

## Common Docker Commands

- **Start all services:**  
  `docker-compose up -d --build`
- **Stop all services:**  
  `docker-compose down`
- **View logs:**  
  `docker-compose logs -f`
- **Access Laravel container shell:**  
  `docker-compose exec app bash`
- **Access Frontend container shell:**  
  `docker-compose exec frontend bash`

---

## Backup & Cron Job

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

---

## Troubleshooting

- **Check container status:**  
  `docker ps -a`
- **Rebuild containers:**  
  `docker-compose build --no-cache`
- **Clear Laravel cache:**  
  `docker-compose exec app php artisan config:cache`
- **Check Traefik logs:**  
  `docker-compose logs traefik`

---

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [React Documentation](https://reactjs.org/docs/getting-started.html)
- [Traefik Documentation](https://doc.traefik.io/traefik/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Redis Documentation](https://redis.io