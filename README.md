## Installation

First, make sure you have installed [Composer](https://getcomposer.org/download/) on your environment. 
And then, we need to install all the application dependencies by executing:

`composer install`

After this, we need to setup the application by:

`php artisan enrollment-rest:install --fresh ---with-test-data`


Available Options for enrollment-rest:install

| Name  | Description  |
| :---- | :----------- |
| --fresh | this option will drop all the database tables then running up all the migration again. If not provided migration will only run those that are not yet executed(You can see the list of executed migrations on the table **migrations)  |
| ---with-test-data | this option provides a test data for the application development. If not provided no test data will be added to the tables |


## Environment Variables
If these environment variables do not exist on your .env file, we need to add them. You can check also the .env.example for the updated environment variables that the application needed.

| Name  | Description  |
| :---- | :----------- |
| CLIENT_ID | this is the id that identifies the consumer application. We need to copy the client id (the second one) that will prompt on the terminal to .env file. |
| CLIENT_SECRET | this is the key that secures the consumer application. We need to copy the client secret (the second one) that will prompt on the terminal to .env file. |


## Sample Endpoints
| Endpoint  | Params/Payload | Method | Description  |
| :-------- | :------------- | :----- | :----------- |
| api/v1/students | ?paginate=true/false&perPage=N&page=N | GET | returns the list of students. |
| api/v1/login | { username: value, password: value }  | POST | returns tokens after successfull authentication(if credentials are corrent). access token from here will the be one to include on every http request. Secured Http Request should have **Authorization header with **Bearer {Token} |
| api/v1/personnel/login | { username: value, password: value }  | POST | returns tokens after successfull authentication(if credentials are corrent). access token from here will the be one to include on every http request. Secured Http Request should have **Authorization header with **Bearer {Token} |