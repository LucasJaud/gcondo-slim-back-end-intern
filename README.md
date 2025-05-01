# Gcondo Slim ⚡️

Este projeto é dedicado à etapa técnica do processo seletivo de novos desenvolvedores back-end.

## Ambiente de desenvolvimento local

Por padrão, os candidatos recebem o projeto como um arquivo compactado.

> [!TIP]
> Você pode usar qualquer sistema operacional, seja ele **Windows** ou **Linux**.\
> Essa é a magia do **Docker** 🐳

### Requisitos

- Uma ferramenta para descompactar o arquivo compactado, como **WinRAR** ou **7-Zip**
- Uma **IDE**, como **Visual Studio Code**
- **Docker** e **Docker Compose**

### Sugestões

- Uma ferramenta para acessar e visualizar o banco de dados do projeto, como **Beekeeper**
  - ![Conectando no banco dados do projeto pelo Beekeeper](assets/beekeeper-database-connection.png)
- Extensões para o **Visual Studio Code**
  - [Docker](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker)
  - [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
  - [Error Lens](https://marketplace.visualstudio.com/items?itemName=usernamehw.errorlens)
  - [Markdown All in One](https://marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one)

### Instalação

1. Descompactar o arquivo compactado em um local de sua escolha
2. Acessar o local escolhido no passo anterior
3. Inicializar os containers
    ```bash
    docker compose up -d
    ```
4. Acessar o container da **API**
    ```bash
    docker compose exec api bash
    ```   
5. Instalar as dependências com **Composer**
    ```bash
    composer install
    ```   
6. Configurar o banco de dados com **Phinx**
    ```bash
    composer run phinx:migrate
    ```

**A API estará disponível em: http://localhost:8080 ⚡️**

#### Como derrubar os containers?

```bash
docker compose stop
```

#### Como subir os containers novamente?

```bash
docker compose up -d
```

### Insomnia

> [!NOTE]
> Você pode usar outras ferramentas, como **Postman**, mas sugerimos **fortemente** que use o **Insomnia**, já que a coleção está pronta e configurada, facilitando muito o seu trabalho.

1. Abra o **Insomnia**
2. Clique em **"Create"** e escolha **"File"** -> **"Import"** -> **"From File"**
3. Selecione o arquivo `insomnia.json` localizado neste diretório.
  
Todas as rotas estarão disponíveis para teste 💫

## Funcionalidades 🧵

O produto possui dois módulos, sendo eles:

### Condomínios 🏘️

Condomínios possuem os campos *Nome*, *CEP* e *URL*, além das seguintes funcionalidades:

- Criar um condomínio
- Buscar um condomínio
- Listar condomínios
- Editar um condomínio
- Excluir um condomínio
  - Não é possível excluir um condomínio que possui unidades

### Unidades 🏠️

Condomínios possuem os campos *Condomínio*, *Nome*, *Metros quadrados (opcional)*, *Quantidade de quartos (Opcional)*, além das seguintes funcionalidades:

- Criar uma unidade
- Buscar uma unidade
- Listar unidades
- Editar uma unidade
- Excluir uma unidade

## Tecnologias

- PHP 8.4
- Slim 4.12
- Phinx 0.15
- Eloquent 12.0

## Tarefas

A ideia do teste é adicionar novas funcionalidades, corrigir alguns *bugs* e melhorar algumas validações.

> [!TIP]
> Imagine que as tarefas abaixo estão sendo passadas para você pelo coordenador da equipe, ou seja, uma pessoa que não é um desenvolvedor, então não espere informações super técnicas vindo dele.
> 
> Faz parte do teste e do próprio dia a dia de um desenvolvedor traduzir informações não técnicas para informações técnicas e agir com base nisso.

### 1. Corrigir inconsistência na validação da URL de um condomínio

Alguns clientes estão relatando que não conseguem mais criar novos condomínios sem o campo **URL**. Isso não deveria estar acontecendo.

### 2. Comportamento inesperado ao repetir a mesma URL em outro condomínio

A nível regra de negócio, não é possível repetir a URL de um condomínio e parece que isso está funcionando, mas a resposta do servidor está retornando informações do banco de dados, de forma que não conseguimos exibir uma mensagem amigável para o usuário no front-end, além de ser uma falha de segurança.

Devemos melhorar a validação.

### 3. Adicionar a possibilidade de criar reservas

Após conversar com o **Product Owner** e com alguns clientes, chegamos na ideia de criar uma forma dos nossos usuários poderem criar reservas dos salões de festa.

Toda reserva está relacionada a uma unidade do condomínio.

> [!NOTE]
> Pensando em condomínios que possuem mais de um salão de festa, pode ser interessante ter a possibilidade de criar salões de festa, ou seja, ter uma funcionalidade extra: **Locais**. Com essa nova funcionalidade, as reservas **podem** ser relacionadas aos locais.
>
> Um local tem os campos **Nome**, **Quantidade máxima de pessoas** e **Metros quadrados (opcional)**
> 
> **Este item é opcional e deve ser feito apenas como um diferencial, não sendo obrigatório.**

Uma reserva deve ter os campos **Nome**, **Unidade**, **Quantidade de pessoas** e **Data**.

> [!TIP]
> O*lá, tudo bem? Eu sou um desenvolvedor back-end Gcondo e vou te dar algumas dicas:*
> 
> Usamos o formato **ISO 8601** para datas, ou seja, para a data da reserva, o back-end **deve** receber algo como: `2025-12-31` ⚡️
>
> Além disso, recomendo que você investigue as funcionalidades de **Condomínios** e **Unidades** e use isso como base para a funcionalidade de **Reservas**. Lembre-se que você não precisa reinventar a roda, porque já temos referências prontas no projeto.
>
> Ah, por último, mas não menos importante: Caso esteja usando o **Insomnia**, por favor, adicione as novas rotas nele e então exporte a coleção. Em relação ao nome, sugiro **fortemente** que use algo como `ticket-456.json`.

## Processo

Como parte do nosso processo de desenvolvimento, você deve usar o arquivo `tasks.md` para a *issue review*, ou seja, escrever o que você deve fazer e como deve fazer para concluir estes itens.

 Um desenvolvedor back-end Gcondo será responsável por revisar, então você pode usar termos técnicos, além de trechos de código.

> [!IMPORTANT]
> Você deve fazer o desenvolvimento dos itens com base no que você mesmo escreveu em `tasks.md`, então cuidado com isso ⚡️

> [!TIP]
> Como é a sua primeira vez, você pode editar o arquivo `tasks.md` a qualquer momento.