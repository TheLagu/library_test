
library: library_container
	@docker exec fpm bash -c 'cd /application/ && composer install';
	@if [ ! -f "./.db_created" ]; then \
		docker exec fpm bash -c 'cd /application/ && vendor/bin/phinx seed:run -e dev --configuration phinx.php -s DatabaseStructure'; \
		if [ $$? -eq 0 ]; then \
			touch .db_created; \
		fi; \
	fi

library_container: requisites
	@if [ "$$(docker ps -a | awk '{print $NF}' | grep -w 'nginx' |wc -l)" -eq "0" ];then \
		docker-compose -f docker-compose.yml up -d nginx; \
	fi

requisites:
	@which git > /dev/null; \
	if [ $$? -ne 0 ]; then \
	    echo "No se ha encontrado el binario de git"; \
	    exit 1; \
	fi
	@which docker > /dev/null; \
	if [ $$? -ne 0 ]; then \
	    echo "No se ha encontrado el binario de docker (https://www.docker.com/community-edition#/download)"; \
	    exit 1; \
	fi

stop:
	@docker-compose -f docker-compose.yml stop;