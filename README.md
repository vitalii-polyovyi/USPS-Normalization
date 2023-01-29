# How to start dev environment

Rename `.env.dev.example` to `.env`.
Run the following commands:

```shell
docker-compose -f docker-compose.dev.yml up -d
```
```shell
docker-compose -f docker-compose.dev.yml exec backend sh install.dev.sh
```

To stop containers:
```shell
docker-compose -f docker-compose.dev.yml down
```

Open: http://localhost:3001/ in your browser