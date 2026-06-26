# Lovingshopz

An e-commerce web application for a stationery shop. The motivation behind this project was to redesign my parents' old website. 

## Live Demo

Website: https://lovingshopz-production.up.railway.app

## Notes

* Payments are processed using Midtrans Sandbox. No real transactions will be charged.
* This project was developed for educational and portfolio purposes.

## Product Dataset

The initial product catalog was generated using a custom Selenium scraper.

Product scraper:
https://github.com/kennethdavis7/scraper-lovingshopz

## Features

### Customer Features

* User registration and authentication
* User profile management
* Browse products by category
* Product search with Meilisearch
* Product sorting
* Shopping cart
* Buy Now functionality
* Midtrans payment integration
* Transaction history

### Admin Features

* Dashboard with sales statistics
* Product management (Create, Read, Update, Delete)
* Category management
* PDF product report generation

## Technologies Used

### Backend

* Laravel
* PHP
* MySQL

### Frontend

* Vue 3
* Inertia
* Tailwind CSS

### Services
- Meilisearch (Product Search)
- Cloudinary (Image Storage)
- Midtrans Sandbox (Payment Gateway)
- Railway (Deployment)
