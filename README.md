# Trabalho DevOps 2 — Deploy automático em VM

## Integrantes

- Gladson Coronado dos Santos — RA 250293
- Gustavo Francisco dos Santos — RA 250296

## Sobre o projeto

Este projeto consiste em uma aplicação CRUD de produtos, permitindo cadastrar, consultar, editar e excluir registros.

A aplicação utiliza PHP e MySQL, com os serviços organizados por meio do Docker Compose. Para automatizar as atualizações, foi configurado um Runner local do GitHub Actions em uma máquina virtual com Ubuntu Server.

Dessa forma, quando uma alteração é enviada para a branch principal do repositório, o GitHub Actions executa o workflow na máquina virtual e realiza a atualização da aplicação.

## 1. Tecnologias utilizadas

- PHP 8.3 e Apache para executar a aplicação.
- MySQL 8.0 para armazenar os dados.
- phpMyAdmin para gerenciamento do banco de dados.
- Docker para executar os serviços em containers.
- Docker Compose para organizar os serviços da aplicação.
- Ubuntu Server como sistema operacional da máquina virtual.
- GitHub Actions e Self-hosted Runner para automatizar o deploy.

## 2. Organização dos serviços

O projeto utiliza o Docker Compose para executar os serviços necessários para a aplicação.

| Serviço | Função | Porta |
|---|---|---|
| web | Aplicação PHP com Apache | 8080 |
| db | Banco de dados MySQL | 3306 (interna) |
| phpmyadmin | Gerenciamento do banco | 8081 |

O serviço `web` disponibiliza a aplicação para acesso pelo navegador. O `db` armazena os dados utilizados pelo CRUD, enquanto o phpMyAdmin permite consultar e administrar o banco de dados.

## 3. Deploy automático — `deploy.yml`

O workflow foi criado para atualizar a aplicação automaticamente na máquina virtual sempre que uma alteração é enviada para a branch `main`.

### Utilização do Runner local

```yaml
runs-on: self-hosted
```

Essa configuração indica que o workflow será executado em um Runner instalado em uma máquina própria. No nosso projeto, utilizamos o Runner instalado na VM Ubuntu, permitindo que os comandos do deploy sejam executados diretamente nela.

### Verificação do Docker

```yaml
run: |
  docker --version
  docker compose version
```

Antes de iniciar a atualização da aplicação, esse trecho verifica se o Docker e o Docker Compose estão disponíveis na máquina virtual. Assim, conseguimos identificar possíveis problemas antes de executar o deploy.

### Validação da configuração

```yaml
run: docker compose config -q
```

Esse comando verifica se o arquivo do Docker Compose está configurado corretamente. Dessa forma, conseguimos identificar erros de configuração antes de tentar iniciar os serviços da aplicação.

### Atualização da aplicação

```yaml
run: |
  echo "Iniciando o deploy..."
  docker compose up -d --build --remove-orphans
  echo "Deploy executado!"
```

Esse é o trecho responsável pela atualização da aplicação. Ele solicita a reconstrução das imagens e inicia os serviços em segundo plano.

A opção `-d` mantém os containers executando sem ocupar o terminal. O `--build` solicita a construção das imagens antes de iniciar os serviços, enquanto `--remove-orphans` remove containers que não fazem mais parte da configuração atual do projeto.

Com isso, quando uma alteração é enviada ao GitHub, o Runner utiliza os arquivos atualizados do repositório para reconstruir e iniciar os serviços da aplicação.

### Conferência dos containers

```yaml
run: docker compose ps
```

Depois de executar o deploy, esse comando apresenta os containers do projeto e seus respectivos estados. Com isso, conseguimos conferir pelo log do GitHub Actions se os serviços foram iniciados.

## 4. Como acessar a aplicação

Para descobrir o endereço IP da máquina virtual, acesse o Ubuntu Server e execute o comando:

```bash
hostname -I
```

O comando exibirá os endereços IP configurados na máquina. Identifique o IP da interface de rede utilizada para acessar a VM pela rede local.

Depois, utilize o endereço encontrado para acessar a aplicação:

**Aplicação CRUD:**

```text
http://IP_DA_VM:8080
```

**phpMyAdmin:**

```text
http://IP_DA_VM:8081
```

Por exemplo, se o IP retornado for `192.168.3.212`, os endereços serão:

- CRUD: `http://192.168.3.212:8080`
- phpMyAdmin: `http://192.168.3.212:8081`

O computador utilizado para acessar a aplicação precisa estar conectado à mesma rede da máquina virtual, e as portas devem estar liberadas para acesso.

## 5. Como executar o projeto manualmente

Caso seja necessário iniciar os serviços diretamente pela VM, entre na pasta do projeto e execute:

```bash
docker compose up -d --build
```

Para conferir os containers:

```bash
docker compose ps
```

Para acompanhar os logs:

```bash
docker compose logs -f
```

## 6. Resultados

Com o Runner local configurado e o workflow ativo, o processo de atualização deixa de depender de um deploy manual.

Quando uma alteração é enviada para a branch `main`, o GitHub Actions inicia a execução no Runner instalado na VM. O workflow prepara os arquivos, verifica a configuração do Docker Compose e executa a atualização dos serviços.

Dessa forma, conseguimos acompanhar o processo pela aba Actions do GitHub e acessar a aplicação pelo navegador para conferir a versão atualizada.
