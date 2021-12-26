FROM laravelphp/vapor:php81

COPY . /var/task
RUN touch /var/task/database/database.sqlite
