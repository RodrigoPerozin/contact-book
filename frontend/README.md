# Vue 3 + TypeScript + Vite

This template should help get you started developing with Vue 3 and TypeScript in Vite. The template uses Vue 3 `<script setup>` SFCs, check out the [script setup docs](https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup) to learn more.

Learn more about the recommended Project Setup and IDE Support in the [Vue Docs TypeScript Guide](https://vuejs.org/guide/typescript/overview.html#project-setup).

# Vue.js Project com Docker

Este projeto Vue.js está configurado para rodar em **ambientes de desenvolvimento e produção** usando Docker. Aqui você encontrará instruções para iniciar, construir e gerenciar o projeto em ambos os ambientes.

---

## Pré-requisitos

- [Docker](https://www.docker.com/get-started) instalado
- [Docker Compose](https://docs.docker.com/compose/install/) instalado
- Node.js e npm/yarn não são necessários localmente para rodar dentro do Docker

---

## Estrutura dos arquivos Docker

- `Dockerfile.dev` → Configuração para ambiente de **desenvolvimento**
- `Dockerfile.prod` → Configuração para ambiente de **produção**
- `docker-compose.dev.yml` → Compose para desenvolvimento com hot-reload
- `docker-compose.prod.yml` → Compose para produção (build otimizado)

---

## Rodando em Desenvolvimento

O ambiente de desenvolvimento permite **hot-reload**, ou seja, alterações no código são refletidas automaticamente no navegador.

```bash
docker-compose -f docker-compose.dev.yml -p contact-book-backend up -d --build
```
## Rodando em Produção

```bash
# Subir o container em modo prod
docker-compose -f docker-compose.prod.yml -p contact-book-frontend up -d --build
```