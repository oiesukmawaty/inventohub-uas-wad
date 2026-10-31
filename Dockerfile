FROM node:20-alpine

WORKDIR /app

COPY . .

ENV PORT=10000
EXPOSE 10000

CMD ["node", "preview-server.js"]
