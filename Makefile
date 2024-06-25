up:
	docker compose up --pull always -d --wait

down:
	docker compose down --remove-orphans

build:
	docker compose build --no-cache