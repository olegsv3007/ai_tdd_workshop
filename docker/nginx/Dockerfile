FROM nginx:latest

COPY ./default.conf /etc/nginx/conf.d/

WORKDIR /var/www/symfony

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
