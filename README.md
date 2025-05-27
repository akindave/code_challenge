# Approval Workflow System

## System Overview
A hierarchical approval workflow application where requests move through multiple approval levels.

## Features
- Department-based approval hierarchies
- Multi-level approval process
- Request tracking
- User role management

## Prerequisites
- Docker
- Docker Compose

## Installation
1. Clone the repository
2. Copy `.env.docker` to `.env`
3. Start container:
   ```bash
   docker-compose up -d --build
4. Generate app key:
   ```bash
   docker-compose run app php artisan key:generate
5. Run migrations and seeders:
   ```bash
   docker-compose exec app php artisan migrate --seed
## Visit the link in your Browser or Postman

# API: http://localhost:8000/api

# Web: http://localhost:8000

## Postman Collection

You can find the Postman API collection for this project here:

👉 [Crown Interactive API Postman Collection](https://github.com/akindave/code_challenge/blob/main/Crown%20Interactive%20Api.postman_collection.json)

## 📊 System Diagrams

### 🧩 Initial ERD

![Initial ERD](https://github.com/akindave/code_challenge/blob/main/public/Initial%20ERD.png?raw=true)

### 🚀 Improved System Diagram

![Advanced Improved System](https://github.com/akindave/code_challenge/blob/main/public/Advance_improved_system.png?raw=true)

