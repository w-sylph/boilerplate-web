# Laravel Boilerplate

### Installation Guide
Copy .env.example for values basis

Run the following commands

```bash
# Install Laravel Sail
composer require laravel/sail --dev --ignore-platform-reqs

# Start and setup Docker
./vendor/bin/sail up

# Install node modules
npm install

# Compile assets for development
npm run dev

# Run custom laravel installation process
php artisan app:install
```

## Dependencies
Check ```composer.json``` & ```package.json``` for more details

### CSS Dependencies
Components are mostly base on Bootstrap 4 it is suggested to create custom CSS upon Bootstrap 4
- [Bootstrap 4](https://getbootstrap.com/docs/4.3/getting-started/introduction/)
- [AdminLTE 3](https://adminlte.io/themes/dev/AdminLTE/index.html)
- [Fontawesome 4 Web Icons](https://fontawesome.com/icons?d=gallery&s=solid&m=free)

### JS Dependencies
- [Vue.js](https://vuetifyjs.com/)
- [Vue Confirm Dialog](https://www.npmjs.com/package/vuejs-dialog)
- [Chart.js](https://www.chartjs.org/docs/latest/)
- [Moment JS](https://momentjs.com/)
- [Sortable JS](https://github.com/SortableJS/Sortable)

### PHP Dependencies
- [Laravel Activity Log](https://docs.spatie.be/laravel-activitylog/v2/introduction)
- [Laravel Roles & Permissions](https://github.com/spatie/laravel-permission)
- [Importer & Exporter](https://docs.laravel-excel.com/3.1/getting-started/)
- [Socialite](https://laravel.com/docs/5.8/socialite)
    - [Facebook](https://www.tutsmake.com/laravel-5-facebook-login-with-socialite)
- [Sortable](https://github.com/boxfrommars/rutorika-sortable)
- [Sluggable](https://github.com/spatie/laravel-sluggable)

### Websockets
- [Websocket Server](https://github.com/tlaverdure/laravel-echo-server)
    - [Broadcasting](https://laravel.com/docs/5.8/broadcasting)

### Assets Reference
- [Favicon Generator](https://www.favicon-generator.org)