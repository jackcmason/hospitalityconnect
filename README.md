# Setup
## Note
Assuming a fresh install of Ubuntu
## Instructions
1. Do `apt` installs
```
sudo apt install php php-cli php-common php-mbstring php-xml php-zip curl php-curl php-sqlite3 git
```
2. Install Composer
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

php -r "unlink('composer-setup.php');"
```
3. Install Node & NPM
```
curl -fsSL https://deb.nodesource.com/setup_21.x | sudo -E bash - &&sudo apt-get install -y nodejs
```
# How
## Frontend
In the `frontend/` directory:
```
npm install
npm run dev
```
Then you will want to navigate to http://localhost:5173/ to access the frontend
## Backend
In the `backend/` directory:
```
cp .env.example .env
composer install
php artisan key:generate
sudo chmod -R 777 storage bootstrap/cache
php artisan migrate
php artisan serve
```
The `php artisan migrate` command will prompt to create a new SQLite database, select yes
# Brief
## FrontEnd & Backend
I chose to develop a separate frontend & backend for the project, as I thought that this would allow me to develop the application faster
## Vue
I chose to use Vue as the frontend framework for 2 reasons:
- Vue appears to be the most commonly used Javascript framework used with Laravel
- Out of React, Angular & Vue, Vue is the closest to the framework which I have the most experience in (Svelte/SvelteKit)
## CSV Form Submission
For the `.csv` upload, I initially had the the form `action` as the API endpoint (rather than a more typical override of the default form submission behaviour, which is used for the GET route ). As the `.csv` was submitted, the end user would view the response of the POST request in the browser. I decided that this wasn't desirable behaviour for the end user, the form still uses the API endpoint as the form action, but the result of the POST request is sent to an `<iframe>` with the `display: none` CSS property, "discarding" it from the view of the end user
## RegEx
I use RegEx to make sure that the latitude & longitude values are in the right format, since these values are given to the program as strings (in both the form and the `.csv` file)
## Forms, Toasts, Components & Long Term Development
I would have made greater use of components if I was developing for a longer term, more complex project. In particular:
- I would have developed a component to handle the creation of HTML forms to avoid repetition
- I would have developed a toast component to provide better feedback to end users

I have developed these components in previous projects, but these were left out of this project due to time and scope
## SQLite
I chose to use SQLite, as I thought it was appropriate for the scope of the project
## StudentController.php
Most of the implementation details for the API are in the `./backend/app/Http/Controllers/Api/StudentController.php` file
## Decimal for Latitude & Longitude
I chose a `DECIMAL` datatype to store the latitude and longitude as I thought this was the best fit for this data (`FLOAT`,`DOUBLE`,`TEXT` & `VARCHAR` are alternative possabilities)
