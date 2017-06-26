help:
	@echo 'make (help|build|server|clean)'
install-fc25:
	# Installation for fedora 25 box
	@sudo dnf install mariadb-server php php-mysqlnd perl-DBI
build:
	perl parse-vim-options.pl < vim-options.txt
server:
	php -S 127.0.0.1:9090
clean:
	find ./ -name \*~ -delete
