# dashboard-management

In this Repo consist of Both frontend and backend which is the complete of dashboard-management

START THE PRJECTS

1, Backend
    - Install deps run "composer install" make sure to located on /backend
    - In order to be able render or get image from server "php artisan storage:link" (image can be access {url}/storage/{image path})
    - Prepare the ENV by create ".env" file then copy contents from ".env.example" file, don't forget to adjust the correct database credentails
    - Migrate database
    - Start the project "php artisan serve"

2, Frontend
    - Install package run "npm install"
    - Prepare the ENV by create ".env" file then copy contents from ".env.example" file, adjust the correct ENV value
    - Start the project "npm run dev"