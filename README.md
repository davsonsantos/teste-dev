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

Foi entregue tudo o que foi solicitado, incluindo melhorias e implementações extras de arquitetura:

1. **Autenticação Completa**
   * Cadastro (Aluno)
   * Login Único com suporte a 2FA (opcional)
   * Recuperação e Reset de Senha

2. **Ambiente do Administrador**
   1. Cadastro de Disciplinas
   2. Cadastro de Cursos
   3. Gestão de Usuários (Tela única com atribuição de papéis: Aluno, Professor ou Administrador)
   4. Gestão de Matrículas (Controle de status e vínculos)
   5. Edição de dados do perfil (Avatar, Informações e Segurança)
   6. Dashboard administrativo com métricas e gráficos relevantes

3. **Ambiente do Aluno**
   1. Edição de dados do perfil (Avatar, Informações e Segurança)
   2. Dashboard focada no estudante, com progresso de cursos e conquistas
  

