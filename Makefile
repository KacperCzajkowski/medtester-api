EXEC_COMMAND ?= docker-compose exec app

bash:
	${EXEC_COMMAND} bash