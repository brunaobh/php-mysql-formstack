A web application based on PHP + MySQL stack that allows you to store a set of key/value pairs of different types (strings, numbers, dates) as documents

## Instructions

### Running locally using Docker

Use docker-compose to build the service and mysql containers (takes a few minutes the first time):
```bash
> docker-compose build
```

Then start the the containers:
```bash
> docker-compose up
```

Set laravel application:
```bash
> composer install --ignore-platform-reqs --prefer-dist
> cp .env.example .env
> chmod 777 storage/ -R
> ./console key:generate
> ./console migrate
```

### How to use - web interface

Verify the app by navigating to your server address in your preferred browser.
```bash
http://localhost:8051/public/
```

## How to use - RestFul Api

Store new document:

```bash
curl -X POST \
  http://localhost:8051/public/api/v1/documents \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/json' \
  -d '{
	"document":[{
		"key": "Event name",
		"type": "string",
		"value": "My Happy Birthday Party"	
	}, {
		"key": "Event date",
		"type": "date",
		"value": "11/08/2018"
	}]
}'
```

Get all documents:

```bash
curl -X GET \
  http://localhost:8051/public/api/v1/documents \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/json'
```

Get document by id:

```bash
curl -X GET \
  http://localhost:8051/public/api/v1/documents/{id} \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/json'
```

Update a document by id:

```bash
curl -X PUT \
  http://localhost:8051/public/api/v1/documents/{id} \
  -H 'Accept: application/json' \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/json' \
  -d '{
	"document":[{
		"key": "Event name",
		"type": "string",
		"value": "My Best Happy Birthday Party Ever"	
	}, {
		"key": "Event date",
		"type": "date",
		"value": "11/08/2018"
	}]
}'
```

Export document by id to a CSV file:

```bash
curl -X GET \
  'http://localhost:8051/public/documents/{id}?format=csv' \
  -H 'Cache-Control: no-cache'
```

Export document by id to a CSV file and storage in a cloud storage (s3):

```bash
curl -X GET \
  'http://localhost:8051/public/documents/{id}?format=cloud' \
  -H 'Cache-Control: no-cache'
```

### Testing


Then start the the containers:
```bash
> ./runtests
```
