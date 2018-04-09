FROM alpine:latest
WORKDIR /app
ENV YOYOID ""
ENV PUBKEYS ""
ENV PASS ""
RUN apk --no-cache add php7 php7-openssl php7-common php7-fileinfo php7-session php7-curl php7-mbstring php7-mcrypt php7-gettext php7-json php7-phar
ADD . /app
RUN php composer.phar install
CMD /app/bot.php
