FROM node:current-alpine

WORKDIR /app

COPY . .

RUN mv config.example.json config.json

CMD ["npx", "http-server"]
