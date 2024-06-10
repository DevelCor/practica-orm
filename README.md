# Practica ORM

Descripción del proyecto.

## Requisitos

- PHP >= 7.3
- Composer
- Node.js y NPM
- Base de datos MySQL (o tu base de datos preferida)

## Instalación

1. Clona el repositorio:
    ```
    git clone https://github.com/DevelCor/practica-orm
    ```

2. Navega al directorio del proyecto:
    ```
    cd practica-orm
    ```

3. Instala las dependencias de PHP con Composer:
    ```
    composer install
    ```

4. Instala las dependencias de JavaScript con NPM:
    ```
    npm install
    ```

5. Copia el archivo de entorno de ejemplo y configura tus variables de entorno:
    ```
    cp .env.example .env
    ```
   Edita el archivo `.env` con tus configuraciones de base de datos.

6. Genera una clave de aplicación:
    ```
    php artisan key:generate
    ```

7. Ejecuta las migraciones de la base de datos (asegúrate de que tu configuración de base de datos en `.env` esté correcta):
    ```
    php artisan migrate
    ```

8. (Opcional) Si tienes datos de prueba, puedes ejecutar los seeders de la base de datos:
    ```
    php artisan db:seed
    ```

9. Compila los assets de JavaScript y CSS:
    ```
    npm run dev
    ```

10. Inicia el servidor de desarrollo de Laravel:
    ```
    php artisan serve
    ```
    Ahora deberías poder acceder a la aplicación en `http://localhost:8000`.

## Pruebas

Para ejecutar las pruebas, usa el comando: