# Microsserviço de Pedidos de Viagem

Este projeto é um microsserviço desenvolvido em Laravel com o objetivo de gerenciar pedidos de viagem corporativa. Ele expõe uma API RESTful com funcionalidades completas para criar, consultar, atualizar e listar pedidos de viagem, incluindo autenticação e notificações.

## Composer

Rode `composer install` para instalação de dependencias

---

## Docker

Rode `docker-compose up -d --build` para iniciar o projeto

Rode `docker exec -it onfly_app php artisan migrate:fresh --seed` Para migrations e seeders

---
## 📚 Documentação via Swagger

Após subir o projeto, acesse a documentação da API:

http://localhost:8000/api/documentation

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

## Configurações de Autenticação

Rode o comando abaixo para gerar o segredo JWT:

```bash
php artisan jwt:secret
```

Adicione no seu `.env`:

```
# JWT
JWT_SECRET=gerado_pelo_comando_jwt:secret

# Autenticação
AUTH_GUARD=api
```

---

## Notificações

As notificações de status são enviadas por e-mail. Neste projeto, elas são registradas no log.

Acesse:
```
storage/logs/laravel.log
```

---

## Controle de Progresso

| Etapa                                                              | Status          | Observações                              |
|--------------------------------------------------------------------|-----------------|-------------------------------------------|
| Inicialização do projeto Laravel                                   | ✅ Concluído     | Projeto criado com Laravel mais recente   |
| Setup Docker e banco de dados                                      | ✅ Concluído     | Estrutura de containers sendo definida     |
| Criação das migrations e modelos                                   | ✅ Concluído     | -                                         |
| Implementação da autenticação JWT                                  | ✅ Concluído     | -                                         |
| CRUD de pedidos de viagem                                          | ✅ Concluído     | -                                         |
| Atualização de status (com regras de permissão)                    | ✅ Concluído     | -                                         |
| Filtros por período e destino                                      | ✅ Concluído     | -                                         |
| Notificações de status                                             | ✅ Concluído     | -                                         |
| Testes unitários com PHPUnit                                       | 🔲 A fazer       | -                                         |
| Documentação final no README.md                                    | 🔲 A fazer       | Incluir instruções de uso e testes        |

> Legenda:
> - 🔲 A fazer
> - ⏳ Em andamento
> - ✅ Concluído
