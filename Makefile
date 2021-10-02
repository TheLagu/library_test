
library: library_container
	@docker exec library bash -c 'cd /application/ && composer install';

library_container: requisites
	@if [ "$$(docker ps | awk '{print $NF}' | grep -w 'api_books' |wc -l)" -eq "0" ];then \
		docker-compose -f docker-compose.yml up -d library; \
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