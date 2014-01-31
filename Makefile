all:
	perl parse-vim-options.pl < vim-options.txt
server:
	php -S 127.0.0.1:9090
clean:
	find ./ -name \*~ -delete
