# Ticket #456

<!-- Contexto -->
## Contexto
Como desenvolvedor back-end do Gcondo, estarei responsável por implementar as melhorias e correções solicitadas relacionadas ao sistema de condomínios. Este documento detalha minha análise e plano de implementação para cada tarefa.

<!-- Tarefas -->
## Tasks
1. Corrigir a validação que obriga o preenchimento da URL na criação de condomínios .
2. Tratar o erro de URL duplicada de forma amigável.
3. Criar a funcionalidade de reservas para salões de festa, vinculadas a unidades dos condomínios, com campos validados.
   - Como diferencial opcional, permitir a criação de locais (salões), relacionados às reservas.
<!-- Tarefa 1 -->
## Tarefa 1 - Corrigir obrigatoriedade da URL de Condomínio

Atualmente, o campo de URL está sendo considerado obrigatório e não pode ser vazio, pois existe uma validação antes da criação de qualquer novo condomínio.

No banco de dados, o campo `url` é definido como `UNIQUE` e `NOT NULL`, o que impede múltiplos registros com o valor vazio (`''`), já que o MySQL interpreta isso como uma violação da restrição de unicidade.

Diante disso, existem algumas abordagens possíveis:
- Alterar o banco de dados para permitir valores nulos no campo `url`;
- Remover ou adaptar a restrição `UNIQUE` para permitir múltiplos campos vazios;
- Ou manter a regra atual e formalizar que a `url` é obrigatória.

### Plano de Implementação:
- A abordagem que escolhi foi a de alterar a tabela e permitir que o campo url receba NULL. Assim, quando a URL for opcional, ela ficará como NULL e não interferirá na regra de unicidade, pois, quando não for nula, o próprio banco verifica se existem duplicatas e lança um erro.

- Além disso, alterarei o serviço para transformar o valor da URL em NULL caso ela venha como uma string vazia ("").

> **Observação:** Essa alteração pode impactar diretamente a regra de negócio atual. É possível que a obrigatoriedade da URL tenha sido implementada por um motivo específico. Recomendo validar com um superior ou PO se realmente é desejado que o campo se torne opcional.

<!-- Tarefa 2 -->
## Tarefa 2 - Melhorar tratamento de erro de URL duplicada

Ao tentar cadastrar um condomínio com uma `url` já existente, o sistema retorna uma mensagem de erro do banco de dados, o que pode expor detalhes sensíveis e não permite uma exibição amigável no front-end.

### Plano de Implementação:
- Adicionar o import da classe PDOException no arquivo de tratamento de erros para poder capturar especificamente exceções do PDO

- Implementar uma verificação específica para identificar erros de chave duplicada no banco de dados.
  
- Criar uma resposta personalizada e amigável para o caso de tentativa de cadastro com URL já existente.

- Garantir que informações sensíveis do banco de dados não sejam expostas ao usuário final.

<!-- Tarefa 3 -->
## Tarefa 3 - Criar funcionalidade de Reservas

Será adicionada uma nova funcionalidade para que usuários possam registrar reservas dos salões de festa, associadas a uma unidade do condomínio.

### Plano de Implementação:

- Criar o módulo de **Venue** (Local), que possui relação com o **Condomínio**, e adicionar suas rotas no arquivo de configuração.
  
- Criar e executar a *migration* responsável por criar a tabela venues no banco de dados.
  
- Criar o módulo de **Reservation**, que se relaciona com Venue, e também adicionar suas rotas no arquivo de configuração.
  
- Criar e executar a *migration* responsável por criar a tabela reservations no banco de dados.
  
- Realizar testes utilizando o **Insomnia** e verificar todas as rotas funcionando corretamente.