# Microsserviço de Pedidos de Viagem Corporativa

Este projeto é um microsserviço desenvolvido em Laravel com o objetivo de gerenciar pedidos de viagem corporativa. Ele expõe uma API RESTful com funcionalidades completas para criar, consultar, atualizar e listar pedidos de viagem, incluindo autenticação e notificações.

---

## Descrição do Desafio

O microsserviço deve permitir:

- **Criar** um pedido de viagem com:
    - ID do pedido
    - Nome do solicitante
    - Destino
    - Data de ida
    - Data de volta
    - Status (`solicitado`, `aprovado`, `cancelado`)

- **Atualizar** o status de um pedido (apenas administradores podem alterar para `aprovado` ou `cancelado`).

- **Consultar** os detalhes de um pedido específico.

- **Listar** todos os pedidos cadastrados com filtro opcional por status.

- **Cancelar pedido após aprovação**, com validações específicas.

- **Filtrar pedidos** por período e/ou destino.

- **Notificar o solicitante** em caso de aprovação ou cancelamento.

---

## Tecnologias Utilizadas

- Laravel (última versão)
- MySQL
- Docker + Docker Compose
- JWT Authentication (via `tymon/jwt-auth`)
- PHPUnit para testes automatizados
- Laravel Notifications

---

##  Controle de Progresso

| Etapa                                                              | Status        | Observações                              |
|--------------------------------------------------------------------|---------------|-------------------------------------------|
| Inicialização do projeto Laravel                                   | ✅ Concluído  | Projeto criado com Laravel mais recente   |
| Setup Docker e banco de dados                                      | ⏳ Em andamento | Estrutura de containers sendo definida     |
| Criação das migrations e modelos                                   | 🔲 A fazer     | -                                         |
| Implementação da autenticação JWT                                  | 🔲 A fazer     | -                                         |
| CRUD de pedidos de viagem                                          | 🔲 A fazer     | -                                         |
| Atualização de status (com regras de permissão)                    | 🔲 A fazer     | -                                         |
| Filtros por período e destino                                      | 🔲 A fazer     | -                                         |
| Notificações de status                                             | 🔲 A fazer     | -                                         |
| Testes unitários com PHPUnit                                       | 🔲 A fazer     | -                                         |
| Documentação final no README.md                                    | 🔲 A fazer     | Incluir instruções de uso e testes        |

> Legenda:
> - 🔲 A fazer
> - ⏳ Em andamento
> - ✅ Concluído


---
