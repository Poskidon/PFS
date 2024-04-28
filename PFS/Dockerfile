# Utiliser l'image HTTPD officielle comme base
FROM httpd:latest

# Mettre à jour les paquets et installer PHP
RUN apt-get update && \
    apt-get install -y php libapache2-mod-php && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Activer le module PHP dans Apache
RUN a2enmod php

# Installer le support MySQLi pour PHP
RUN apt-get update && \
    apt-get install -y php-mysql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copier les fichiers PHP dans le répertoire du serveur web
COPY index.php /usr/local/apache2/htdocs/
