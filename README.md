# Tray Vendas

**Tray Vendas** é uma aplicação web desenvolvida para um processo seletivo. Seu objetivo é permitir o cadastro de vendedores, o registro de vendas e o envio automatizado de relatórios diários por e-mail, tanto para os vendedores quanto para os administradores.

Este projeto está dividido em duas partes: uma API construída com Laravel e uma interface administrativa desenvolvida com Blade + Vue.js. A aplicação ainda se encontra em estágio de MVP.

---

## 💪 Instruções para rodar localmente

### Pré-requisitos

* PHP >= 8.1
* Composer
* Docker + Docker Compose
* Laravel Sail

### Passos para rodar

1. Clone o repositório:

   ```bash
   git clone https://github.com/seu-usuario/tray-vendas.git
   cd tray-vendas
   ```

2. Copie o arquivo `.env.example`:

   ```bash
   cp .env.example .env
   ```

3. Instale as dependências do Laravel:

   ```bash
   composer install
   ```

4. Suba os containers com o Sail:

   ```bash
   ./vendor/bin/sail up -d
   ```

5. Instale as dependências do frontend e rode o build:

   ```bash
   ./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
    ```
6. Gere a laravel key
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

7. Rode as migrations e seeders:

   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

8. Rode o listener da fila para envio de e-mails:

   ```bash
   ./vendor/bin/sail artisan queue:listen
   ```

9. Acesse no navegador:

   ```
   http://localhost
   ```
### Admin

A aplicação posui um administrador original, pode logar com em em /admin/login com
- email:admin@example.com
- senha:admin123

### Serviços disponíveis

* **MySQL**: `localhost:3306`
* **Redis**
* **Mailpit**: `http://localhost:8025`

---

## 🧐 Processo de desenvolvimento

O projeto foi desenvolvido em 3 dias com foco em entregar um MVP funcional. O maior desafio técnico foi implementar a autenticação para múltiplos tipos de usuários (`admin` e `vendedor`) com `guards` separados. Essa decisão trouxe complexidade, mas proporcionou aprendizado valioso.

A arquitetura inicialmente proposta foi um **Clean MVC** com uso de **Service Layer**, **Repositories**, injeção de dependência e controllers enxutos. Porém, com a limitação de tempo, nem todos os padrões puderam ser rigorosamente aplicados.

A aplicação foi escrita com:

* Laravel (API e web)
* Inertia e Vue.js (interface)
* Redis (cache e filas)
* Mailpit (simulação de e-mails)

---

## ⚙️ Funcionalidades implementadas

### API (Laravel Sanctum)

* [x] Cadastro de vendedores (`POST /api/sellers`)
* [x] Listagem de vendedores (sem filtros)
* [x] Cadastro de vendas (`POST /api/orders`)
* [x] Listagem de vendas (sem filtros funcionais)
* [ ] Listagem de vendas por vendedor (incompleta)
* [x] Autenticação básica com tokens (em progresso)

### Aplicação Web

* [x] Dashboard de administrador (`/admin/dashboard`)
* [x] Dashboard de vendedor (`/dashboard`)
* [x] Envio de relatório diário por e-mail para vendedor (via Job)
* [x] Envio de relatório diário por e-mail para administrador (via Job)
* [x] Reenvio manual de relatório via botão no painel admin
* [x] Gerador de tokens de API para admin
* [x] Landing page e páginas de login para vendedor/admin

> ✅ O envio de e-mails está funcionando e pode ser testado via painel admin. Os e-mails são direcionados ao Mailpit e enviados via Jobs.
> ⚠️ O agendamento via cronjob está registrado no Laravel Scheduler, mas ainda não está sendo executado automaticamente.

---

## 🧰 Testes

* [ ] Testes unitários e de integração

  * A estrutura de testes foi iniciada, mas por falta de tempo, não há cobertura efetiva no momento.

---

## 🛠️ Conhecidos e Limitadores

* ⚠️ Autenticação multi-guard ainda incompleta
* ⚠️ Listagem de vendas e filtros por vendedor incompletos
* ⚠️ Testes não implementados
* ⚠️ Algumas páginas frontend com layout provisório
* ⚠️ A arquitetura inicialmente planejada foi comprometida por limitações de tempo
* ⚠️ Necessário rodar manualmente `sail artisan queue:listen` para processar os e-mails na fila
* ⚠️ O comando `./vendor/bin/sail up -d` não está disponível imediatamente após o clone (necessário rodar `composer install` antes)

---

## 📦 Tecnologias utilizadas

* PHP + Laravel 10
* Vue.js + Blade
* MySQL
* Redis
* Docker + Laravel Sail
* Mailpit

---

## 🚀 Considerações finais

Este projeto visa demonstrar habilidades em back-end com Laravel, front-end com Vue.js e integração entre múltiplos serviços (cache, fila, e-mail, autenticação). Embora o projeto esteja em MVP, serve como base sólida para expansão e aprimoramento.
