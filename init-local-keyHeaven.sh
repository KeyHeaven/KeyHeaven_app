docker exec php8keyheaven sh -c 'cd keyHeaven/'
docker exec php8keyheaven sh -c 'cd keyHeaven/ && composer install' && \
docker exec php8keyheaven sh -c 'cd keyHeaven/ && php bin/console doc:mig:mig' && \

# activate only if you know what you do
docker exec php8keyheaven sh -c 'cd keyHeaven/ && npm install' && \
docker exec php8keyheaven sh -c 'cd keyHeaven/ && symfony server:ca:install' && \
docker exec php8keyheaven sh -c 'cd keyHeaven/ && symfony serve' && \