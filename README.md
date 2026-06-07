# CRUD Productos — API PHP + MySQL con Docker

API REST en PHP con interfaz web para gestionar un catálogo de productos. Usa MySQL como base de datos y Docker para el despliegue.

## Requisitos

- Docker Desktop instalado y en ejecución
- Puerto 8080 libre

## Levantar el proyecto

```bash
# Clonar el repositorio
git clone <url-del-repo>
cd crud-php

# Levantar los contenedores
docker compose up -d

# Acceder en el navegador
http://localhost:8080
```

## Endpoints de la API

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api.php` | Listar todos los productos |
| GET | `/api.php?id=1` | Obtener un producto por ID |
| POST | `/api.php` | Crear un nuevo producto |
| PUT | `/api.php?id=1` | Actualizar un producto |
| DELETE | `/api.php?id=1` | Eliminar un producto |

## Ejemplo de uso (curl)

```bash
# Listar productos
curl http://localhost:8080/api.php

# Crear producto
curl -X POST http://localhost:8080/api.php \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Webcam HD","descripcion":"720p USB","precio":29.99,"stock":20}'

# Actualizar producto
curl -X PUT http://localhost:8080/api.php?id=1 \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Laptop Lenovo V2","descripcion":"Actualizado","precio":649.99,"stock":8}'

# Eliminar producto
curl -X DELETE http://localhost:8080/api.php?id=1
```

## Estructura del proyecto

```
crud-php/
├── docker-compose.yml
├── Dockerfile
├── README.md
├── docker/
│   └── mysql/
│       └── init.sql       # Esquema y datos iniciales
└── src/
    ├── index.php           # Interfaz web
    ├── api.php             # API REST (CRUD completo)
    └── db.php              # Conexión a la base de datos
```

## Tecnologías

- PHP 8.1 + Apache
- MySQL 8.0
- Docker + Docker Compose



## Colaboradores

- [Garciaadev](https://github.com/Garciaadev) — Owner
- [garciaadevAlejandro](https://github.com/garciaadevAlejandro) — Colaborador