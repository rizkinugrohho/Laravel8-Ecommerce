# DEMO
## <a href = "https://www.youtube.com/watch?v=N2I7d__Z1DM" target="_blank"> CLICK HERE </a>

[![WATCH](https://img.youtube.com/vi/N2I7d__Z1DM/0.jpg)](https://www.youtube.com/watch?v=N2I7d__Z1DM)

## Installation
Make sure you have environment setup properly. You will need MySQL, PHP7.4, Node.js and composer.

## Install Laravel Website + API
1. Download the project (or clone using GIT)
2. Copy `.env.example` into `.env` and configure database credentials
3. Navigate to the project's root directory using terminal
4. Run `composer install`
5. Set the encryption key by executing `php artisan key:generate --ansi`
6. Run migrations `php artisan migrate --seed`
7. Start local server by executing `php artisan serve`
8. Open new terminal and navigate to the project root directory
9. Run `npm install`
10. Run `npm run dev` to start vite server for Laravel frontend
