FROM node:22

WORKDIR /usr/src/app

COPY ./interface/package*.json ./

RUN npm install

COPY ./interface .

EXPOSE 8080

CMD ["npm", "run", "serve"]
