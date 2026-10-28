# Trabalho DevOps 2

## Integrantes

- **Gladson Coronado dos Santos** — RA: 250293
- **Gustavo Francisco dos Santos** — RA: 250296

## Descrição

Este projeto implementa um CRUD de produtos. A aplicação permite cadastrar, visualizar, editar e excluir produtos, mantendo informações como nome, descrição, preço e estoque.

A solução utiliza:

- **PHP 8.3** com Apache para a aplicação web;
- **MySQL 8.0** para persistência dos dados;
- **phpMyAdmin** para administração do banco de dados;
- **Docker e Docker Compose** para criação e execução dos serviços;
- **GitHub Actions** para automatizar o deploy na máquina virtual;
- **GitHub Actions Runner self-hosted** instalado localmente na VM do laboratório.

## Estrutura dos serviços

O arquivo `docker-compose.yml` inicia três serviços:

- `web`: aplicação PHP acessível pela porta `8080`;
- `db`: banco de dados MySQL, com criação inicial da tabela `produtos`;
- `phpmyadmin`: interface web de administração do MySQL, acessível pela porta `8081`.

## Runner local e deploy automático

O Runner local é um agente do GitHub Actions instalado na VM do laboratório. Ele permanece conectado ao GitHub e aguarda novos trabalhos destinados ao ambiente `self-hosted`. Quando recebe um job, executa os comandos diretamente na VM, utilizando os recursos locais, incluindo o Docker instalado.

A Action está definida em `.github/workflows/deploy.yml` e é executada automaticamente a cada `push` na branch `main`. Também pode ser iniciada manualmente pelo GitHub Actions usando `workflow_dispatch`.

Durante a execução, a Action:

1. Faz o checkout do código da branch `main`;
2. Verifica as versões do Docker e do Docker Compose;
3. Valida a configuração com `docker compose config -q`;
4. Constrói e inicia os containers com `docker compose up -d --build --remove-orphans`;
5. Confere o estado dos containers com `docker compose ps`.

Para o deploy funcionar, o usuário que executa o Runner deve ter permissão para acessar o Docker, normalmente estando no grupo `docker` da VM.

## Execução local

Com Docker e Docker Compose instalados, execute na raiz do projeto:

```bash
docker compose up -d --build
```

Verifique os containers com:

```bash
docker compose ps
```

Para interromper a aplicação:

```bash
docker compose down
```

## Acesso na VM do laboratório

Na própria VM, acesse:

- Aplicação: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

A partir de outro computador da rede do laboratório, substitua `localhost` pelo IP da VM:

- Aplicação: `http://172.16.9.66:8080`|
- phpMyAdmin: `http://172.16.9.66:8081`|

O IP da VM pode ser consultado com:

```bash
hostname -I
```

No phpMyAdmin, utilize as credenciais configuradas no Compose:

- Usuário: `root`
- Senha: `root`
- Servidor: `db`

Os dados iniciais dos produtos são criados automaticamente pelo arquivo `db/init.sql` na primeira inicialização do volume do MySQL.
