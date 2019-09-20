FROM adminer

MAINTAINER Marek Hučík

COPY AdminerForeignKeysPlugin.php /var/www/html/plugins-enabled/

EXPOSE 8080
