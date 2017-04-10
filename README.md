# About yaml_cache_and_website
This is a simple application that allows the user to upload YAML documents using a Web interface.
The application is composed by two Docker containers:
 - my-yaml-website (image mkats/yaml_cache_and_website-website)
 - my-yaml-service (image mkats/yaml_cache_and_website-service)
The images for these containers can be downloaded from [hub.docker.com](hub.docker.com).

## my-yaml-website
The container "my-yaml-website" contains a website. The website allows the user to upload YAML
files, which are sent via TCP to "my-yaml-service". Then, the user can view a list of the YAML
documents that are stored in "my-yaml-service" and retrieve download them.

## my-yaml-service
The container "my-yaml-service" contains a PHP script. The PHP script listens on a port for
requests from "my-yaml-website". A request have one of the following 3 types:
 - store a YAML documents
 - get a list of stored YAML documents
 - get a stored YAML document

When a YAML document is received to be stored, the service first checks the validy of the
document's syntax and either responds with an error message, or stores the document in memory cache
using the Memcached service and responds with a JSON message containing a summary of the now stored
YAML document and a handle for identifying it.

When a list of the stored YAML documents is requested, the service responds with a JSON message
containing an array. The array contains one element for each YAML document in cache, and each
element consists of the document handle and document summary.

When a YAML document is requested, the service sends back the entire YAML document.


## Architecture
The website follows the MVC architecture and uses the [PIP framework](http://gilbitron.github.io/PIP/).
The service is implementing from scratch, using a PHP script and some PHP classes.

![architecture](https://cloud.githubusercontent.com/assets/6370036/24865611/73870d7c-1e10-11e7-923f-652e3055a6fa.png)


# Configuration
You can run an instance of this application by running the two containers. This project includes
a docker-compose.yml file in its root directory.
`git clone https://github.com/mkats/yaml_cache_and_website.git`
`cd yaml_cache_and_website`
`docker-compose up`

When the two containers are up and running, you have to configure the environment variable
"SERVICE_LSTN_ADDR" of the container "my-yaml-website".

# Build your own Docker images
To build your own docker images, you can run:
`docker build -t mkats/yaml_cache_and_website-service ./service_php`
`docker build -t mkats/yaml_cache_and_website-website ./pip`

