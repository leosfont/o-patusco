# O Patusco

![O Patusco](image.png)

**O Patusco** é um sistema de gerenciamento de consultas veterinárias. O projeto inclui funcionalidades para médicos, recepcionistas e clientes, com controle de permissões detalhado. Ele foi desenvolvido utilizando **Laravel 11** no backend e **Vue 3 com Pinia** no frontend, oferecendo uma interface simples e intuitiva para o gerenciamento de consultas.

## Requisitos

Antes de começar, certifique-se de ter os seguintes requisitos instalados:

- **PHP 8.3.12**
- **Composer**
- **Node.js 20.16.0**
- **NPM ou Yarn**
- **Sqlite** (ou outro banco de dados compatível)

---

## Instalação

### Backend (Laravel 11)

1. **Clonar o repositório do backend**:

    ```bash
    git clone https://github.com/usuario/o-patusco.git
    cd o-patusco/backend
    ```

2. **Instalar as dependências do PHP**:

    ```bash
    composer install
    ```

3. **Configurar o arquivo `.env`**:

    ```bash
    cp .env.example .env
    ```

    Atualize as configurações do banco de dados e outras variáveis no arquivo `.env`.

4. **Gerar a chave da aplicação**:

    ```bash
    php artisan key:generate
    ```

5. **Rodar as migrations e seeders**:

    ```bash
    php artisan migrate --seed
    ```

6. **Iniciar o servidor**:

    ```bash
    php artisan serve
    ```

    O backend estará disponível em [http://localhost:8000](http://localhost:8000).

---

### Frontend (Vue 3 com Pinia)

1. **Instalar as dependências do Node.js**:

    ```bash
    cd ../frontend
    npm install
    # ou
    yarn install
    ```

3. **Compilar os assets para desenvolvimento**:

    ```bash
    npm run dev
    # ou
    yarn dev
    ```

    O frontend estará disponível em [http://localhost:3000](http://localhost:3000).

---

## Principais Dependências

- **Laravel 11**: Framework PHP utilizado no backend.
- **Vue 3**: Framework JavaScript utilizado no frontend.
- **Pinia**: Gerenciador de estado para Vue 3.
- **Sanctum**: Autenticação baseada em tokens no Laravel.
- **Spatie Laravel Permission**: Pacote para controle de permissões baseado em roles.

---

## Testes

### Backend

Execute os testes no backend com o PHPUnit:

```bash
php artisan test
