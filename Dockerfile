FROM ubuntu:latest

MAINTAINER Dillan Wilding <dillanjwilding@gmail.com>

RUN apt-get update && apt-get -y upgrade

# Set timezone (required to install PHP7.3 without interaction)
RUN apt-get install -y tzdata
RUN ln -fs /usr/share/zoneinfo/America/Chicago /etc/localtime
RUN dpkg-reconfigure --frontend noninteractive tzdata

# Install PHP7.3 and Apache2
RUN apt-get install -y software-properties-common
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php7.3 apache2 libapache2-mod-php7.3

# Enable Apache2 modules
RUN a2enmod php7.3 && a2enmod rewrite

# WORKDIR /var/www

# Cleanup
RUN chown -R www-data:www-data /var/www
RUN rm -rf /var/www/html

# Copy code to container
COPY web /var/www
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Restart Apache2 to apply configuration changes
#RUN /etc/init.d/apache2 reload

# Allow Apache2 to listen to default port
EXPOSE 80

# Share volumes for continuous editing
VOLUME ["/var/www", "/etc/apache2/sites-available"]

# Run Apache2
CMD /usr/sbin/apache2ctl -D FOREGROUND