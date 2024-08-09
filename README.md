# Pasos para ejecutar
    La carpeta ROOT esta en public/index.php
1- Copiar el .env.exemple y renombrarlo a .env
2- Configurar los credenciales de la BD
3- Ejecutar composer install
4- Hay un SQL en el root del proyecto para crear las tablas. "database.sql"

# Explicación del código
1- Esta la estructura del código con MVC
2- Archivo index con una simulación de rutas para no usar archivos (Un archivo muy básico no se deberia hacer asi en proyectos grandes)
3- Uso del composer. En el proyecto se usa composer pero unicamente para los namespaces y la libreria de Dotenv (Ayuda a no subir credenciales o datos sensibles por error en código para usar un archivo .env)
4 - En el front es muy simple donde permite crear y listar todas las citas.

Autor: Alex Madrigal
