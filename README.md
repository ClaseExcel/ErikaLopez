## Instalation

- Copiar el archivo .env.example y cambiarle el nombre a la copia por .env, luego edítelo con las credenciales de la base de datos y otras configuraciones que desee
- Ejecutar el comando ```composer install``` 
- Ejecutar el comando ```php artisan migrate --seed``` . La semilla es importante porque creará el primer usuario administrador.
- Ejecutar el comando ```php artisan key:generate```

- Opcional: si se realiza carga de archivos/fotos, ejecute el comando ```php artisan storage:link```