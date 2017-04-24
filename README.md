# About yaml_cache_and_website
This is a simple application that allows the user to upload YAML documents using a Web interface.

# About the git branch "single-container"
Differences from branch "master":
 - This branch contains a version of the application that uses a single-container architecture.
   The my-yaml_cache_and_website container (image mkats/yaml_cache_and_website) contains both the
   website and the PHP service. The image for this container can be downloaded from
   [hub.docker.com](hub.docker.com).
 - This branch implements an additional feature: The user can download the data cached under a
   handle either in YAML or in JSON format.

## Website
The website allows the user to upload YAML
files, which are sent via TCP to "my-yaml-service". Then, the user can view a list of the YAML
documents that are stored in "my-yaml-service" and retrieve download them.

## PHP service
The PHP service listens on a port for requests from "my-yaml-website". A request have one of the
following 3 types:
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

When a YAML document is requested, the service sends back the entire YAML document in YAML or JSON
format.


# Configuration
You can run an instance of this application by running the container. This project includes
a docker-compose.yml file in its root directory.
```
git clone https://github.com/mkats/yaml_cache_and_website.git
cd yaml_cache_and_website
git checkout single-container
docker-compose up
```

# Build your own Docker images
To build your own docker images, you can run:
```
docker build -t mkats/php7-apache-with-exts ./php7-apache-with-exts
docker build -t mkats/yaml_cache_and_website .  # Inherits from mkats/php7-apache-with-exts
```

# Screenshots
![main-upload](https://cloud.githubusercontent.com/assets/6370036/25334536/9e85d2f0-28f7-11e7-9bae-c7280c054802.PNG)

![main-upload--files_uploaded](https://cloud.githubusercontent.com/assets/6370036/25334545/a3bf710e-28f7-11e7-8213-bdd12eb7a605.PNG)

![main-displayallyamls](https://cloud.githubusercontent.com/assets/6370036/25334552/a97c3942-28f7-11e7-8742-31241ec1bace.PNG)

![main-displayyaml--json](https://cloud.githubusercontent.com/assets/6370036/25334557/ae3a70c0-28f7-11e7-8897-737dadf4f74d.PNG)

![main-displayyaml--yaml](https://cloud.githubusercontent.com/assets/6370036/25334561/b1cdf40a-28f7-11e7-887e-027785bdeadd.PNG)

![main-displayyaml--viewing_files](https://cloud.githubusercontent.com/assets/6370036/25334988/a75c792c-28f9-11e7-976a-dd888196e8fd.PNG)