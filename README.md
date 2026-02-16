# Instalando o projeto

O projeto se utiliza de contêineres Docker, através do pacote _Laravel Sail_ para facilitar a configuração do ambiente de desenvolvimento. Portanto, é necessário que já possua o Docker e o Docker Compose instalados na máquina.

Você é livre para rodar o projeto em ambiente local mas esse texto não tratará essa situação.

Links para instalação e configuração de Docker:

- [Windows](https://docs.docker.com/docker-for-windows/install/)
- [Linux (Debian based)](https://docs.docker.com/engine/install/ubuntu/)

### Passos para o rodar o projeto localmente:

- Faça um clone do projeto para sua máquina local
- Crie um arquivo `.env`, recomendamos usar `.env-example` como base | cp .env.example .env
- Adicione ou altere as chaves conforme sua necessidade
- acesse a pasta do projeto via console (terminal/PowerShell/CMD)
- execute o comando:

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

- Após finalizado processamento, execute o comando `./sail up -d`
- Rode as migrate `./vendor/bin/sail migrate --seed`
- Instale as dependencias: `npm install`
- Rode o projeto: `npm run dev` (abrira em http://localhost)
- Rode os teste: `./vendor/bin/sail pest`

# Teste prático - Programador(a) PHP – Laravel
- Foi feito tudo que foi pedido com pequenas diferenças
  1) Autenticaçõa completa: Cadastro (Aluno), Login Unico (2FA opcional), Esqueceu a Senha e Reset de Senha
  2) Ambiente Admin
     2.1) Cadastro de Disciplinas
     2.2) Cadastro de Currsos
     2.3) Cadastro de Usuário (Tela Unica e no cadastro a opção de o usuario é: Aluno, Professor ou Administrador)
     2.4) Gestão de Matriculas
     2.5) Edição de dados do perfil
     2.6) Dashoard com informações relevantes
  3) Ambiente do Aluno
     3.1) Editar os dados do perfil
     3.2) Dashboard simples com algumas informações

