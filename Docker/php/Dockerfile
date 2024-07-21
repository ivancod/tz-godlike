FROM php:8.1-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# Setup shell as default command terminal
RUN ln -sf /bin/bash /bin/sh

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#set work dirdcoty
WORKDIR /usr/share/nginx/html
RUN chown -R 777 /usr/share/nginx/html

# @TODO: Add user and group to run the container as non-root user

# # Define build arguments
# ARG USER_NAME
# ARG USER_ID
# ARG GROUP_ID

# # Define environment variables
# ENV USER_NAME=$USER_NAME
# ENV GROUP_NAME=$USER_NAME
# ENV USER_ID=$USER_ID
# ENV GROUP_ID=$GROUP_ID
# ENV USER_ID=${USER_ID:-1001}
# ENV GROUP_ID=${GROUP_ID:-1001}

# # Add group and user based on build arguments
# RUN addgroup --gid ${GROUP_ID} ${GROUP_NAME}
# RUN adduser --disabled-password --gecos '' --uid ${USER_ID} --ingroup ${GROUP_NAME} ${USER_NAME}

# # Set user and group of working directory
# RUN chown -R ${USER_NAME}:${GROUP_NAME} /var/www/html