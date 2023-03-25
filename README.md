## To run the application

Go to root directory via terminal

- run the following code
```
docker-compose up -d
```
TAKE NOTE!!!
Once its build successful please wait 2-3 mins before the web server propagated.

access the api via http://localhost:8080/api/weather/{city}


cron run every 15 minutes so initially if there is no data and if you call the api. it will get the data you need and it sync on the weather api you provided.
