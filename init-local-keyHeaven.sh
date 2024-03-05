docker exec keyheaven_app-php8-1 sh -c 'composer install' && \

docker exec keyheaven_app-php8-1 sh -c 'php bin/console doctrine:migrations:diff' && \

docker exec keyheaven_app-php8-1 sh -c 'php bin/console doc:mig:mig' && \

docker exec keyheaven_app-php8-1 sh -c 'php bin/console doc:fixture:load --no-interaction' && \

docker exec keyheaven_app-php8-1 sh -c 'php bin/console lexik:jwt:generate-keypair' && \

# activate only if you know what you do
# docker exec php8keyheaven sh -c 'cd keyHeaven/ && symfony server:ca:install' && \
# docker exec php8keyheaven sh -c 'cd keyHeaven/ && symfony serve' && \