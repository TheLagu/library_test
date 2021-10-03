## Library API Rest

Gestión de libros de una biblioteca.

- [Instalación](#instalación)
- [Endpoints](#endpoints)
    - [Create book](#create-book)
    - [Edit book](#edit-book)
    - [Delete book](#delete-book)
    - [Get books](#get-books)
- [Anotaciones importantes](#anotaciones-importantes)

## Instalación
### Requisitos:
Tener instalado `docker` (https://www.docker.com/) y `git` (https://git-scm.com/).


Clonar el repositorio y levantar la API.
```
git clone https://github.com/TheLagu/library_test
cd library_test
make library
```
Esto levantará la API en un docker en el puerto 4700, `localhost:47000`.

Este tarjet de makefile ejecuta los siguientes comandos:
```
docker-compose -f docker-compose.yml up -d nginx
docker exec fpm bash -c 'cd /application/ && composer install'
docker exec fpm bash -c 'cd /application/ && vendor/bin/phinx seed:run -e dev --configuration phinx.php -s DatabaseStructure'
```

Para parar los servicios levantados en docker
```
make stop
```

Para ejecutar los tests unitarios y funcionales
```
composer test
```
---

Para poder hacer las pruebas hay una carpeta `postman` donde hay un fichero para poder importar en Postman (https://www.postman.com/) y poder probar las peticiones.

---
## Endpoints

### Create Book
Endpoint para crear un libro en BBDD.

**Endpoint**: 
`POST /books`

**Request:**
- **isbn** (required, string)**:** Código ISBN del libro entre 10 y 13 carácteres alfanuméricos.
- **title** (required, string)**:** Título del libro.
- **pages** (required, integer)**:** Número de páginas del libro mayor que `0`.
- **topic** (optional, string)**:** Temática del libro.
- **description** (optional, string)**:** Descripción del libro.

**Response**

201 (Success)
- **id** (string)**:** Identificador del libro, uuid interno autogenerado.

400
- **error** (array)**:** Array con descripción de los errores.
 
***Ejemplo:***

REQUEST
```
POST http://localhost:47000/books

{
    "isbn":"9780393958041",
    "title":"Alicia en el país de las maravillas",
    "pages":784,
    "topic":"fantasía",
    "description":"Historia de Alicia, una niña que recorre un laberinto..."
}
```
RESPONSE

- 201 (Success)
```
{
    "id": "85246bcb-267e-4a64-a77d-c80b829c4d06"
}
```
- 400 (Inválid parámeters)
```
{
    "error": [
        [
            "pages: int, min 1, required"
        ],
        [
            "isbn: string, length 10 to 13, required"
        ],
        [
            "title: string, min length 1, required"
        ],
        [
            "topic: string, min length 1"
        ],
        [
            "description: string, min length 1"
        ]
    ]
}
```  
- 400 (ISBN already exists)

```
{
    "error": [
        [
            "ISBN already exists"
        ]
    ]
}
```  
---
### Edit Book
Endpoint para editar un libro en BBDD.

**Endpoint**:
`PUT /books/{id}`

**Request:**
- **id** (string)**:** Identificador del libro.


- **isbn** (optional, string)**:** Código ISBN del libro entre 10 y 13 carácteres alfanuméricos.
- **title** (optional, string)**:** Título del libro.
- **pages** (optional, integer)**:** Número de páginas del libro mayor que `0`.
- **topic** (optional, string)**:** Temática del libro.
- **description** (optional, string)**:** Descripción del libro.

**Response**

204 (Success)

400
- **error** (array)**:** Array con descripción de los errores.

***Ejemplo:***

REQUEST
```
PUT http://localhost:47000/books/f7d13f76-4ade-4be7-9a57-2efdb807bcb8

{
    "isbn":"9780393958041",
    "title":"Alicia en el país de las maravillas",
    "pages":784,
    "topic":"fantasía",
    "description":"Historia de Alicia, una niña que recorre un laberinto..."
}
```
RESPONSE

- 204 (Success)

- 400 (Book does not exists)

```
{
    "error": [
        [
            "Book f7d13f76-4ade-4be7-9a57-2efdb807bcb8 does not exists"
        ]
    ]
}
``` 
- 400 (Inválid parámeters)
```
{
    "error": [
        [
            "pages: int, min 1, required"
        ],
        [
            "isbn: string, length 10 to 13, required"
        ],
        [
            "title: string, min length 1, required"
        ],
        [
            "topic: string, min length 1"
        ],
        [
            "description: string, min length 1"
        ]
    ]
}
```  
- 400 (ISBN already exists)

```
{
    "error": [
        [
            "ISBN already exists"
        ]
    ]
}
```  
---
### Delete Book
Endpoint para eliminar un libro en BBDD.

**Endpoint**:
`DELETE /books/{id}`

**Request:**
- **id** (string)**:** Identificador del libro.

**Response**

204 (Success)

400
- **error** (array)**:** Array con descripción de los errores.

***Ejemplo:***

REQUEST
```
DELETE http://localhost:47000/books/f7d13f76-4ade-4be7-9a57-2efdb807bcb8
```
RESPONSE
- 204 (Success)

- 400 (Book does not exists)

```
{
    "error": [
        [
            "Book f7d13f76-4ade-4be7-9a57-2efdb807bcb8 does not exists"
        ]
    ]
}
```  
---
### Get Books
Endpoint para listar libros de BBDD.

**Endpoint**:
`POST /books/search`

**Request:**
- **id** (optional, uuid)**:** Identificador del libro.
- **isbn** (optional, string)**:** Código ISBN del libro entre 10 y 13 carácteres alfanuméricos.
- **title** (optional, string)**:** Título del libro.
- **pages** (optional, integer)**:** Número de páginas del libro mayor que `0`.
- **topic** (optional, string)**:** Temática del libro.
- **description** (optional, string)**:** Descripción del libro.


- **query_page** (optional, integer)**:** Página a listar. Mayor que 0.
- **query_limit** (optional, integer)**:** Límite de elementos por página. Mayor que 0.
- **query_sorting** (optional, array)**:** Ordenación de la lista por un campo específico.

**Response**

200 (Success)

400
- **error** (array)**:** Array con descripción de los errores.

***Ejemplo:***

REQUEST
```
POST http://localhost:47000/books/search

{
    "topic":"fantasía",
    "query_page":"1",
    "query_limit":"2",
    "query_sorting":"['pages' => 'DESC']",
}
```
RESPONSE

- 200 (Success)
```
[
    {
        "isbn":"9780393958041",
        "title":"Alicia en el país de las maravillas",
        "pages":784,
        "topic":"fantasía",
        "description":"Historia de Alicia, una niña que recorre un laberinto..."
        "created_at":"2021-10-04 10:11:12"
    },
    {
        "isbn":"123456789123",
        "title":"El laberinto del fauno",
        "pages":123,
        "topic":"fantasía",
        "description":"Un niño en un laberinto"
        "created_at":"2021-10-03 08:09:10"
    }
]
```

- 400 (Inválid parámeters)
```
{
    "error": [
        [
            "'query_page: int, min 1'"
        ],
        [
            "query_limit: int, min 1"
        ]
    ]
}
```  
### Get Book (NO IMPLEMENTADO)
Endpoint para obtener un libro de BBDD.

**Endpoint**:
`GET /books/{id}`



---


## Anotaciones importantes
- Solo he creado los tests unitarios y funcionales de un endpoint, por falta de tiempo.
- Hubiese estado bien hacer la documentación "chula" con OpenApi y Swagger.
- Estaría bien tener una pipeline que automatizase la ejecución de los tests antes de hacer el push.
- Los errores de validación de parámetros deberían ser más concretos
- Si tubiesemos más endpoints de listado se podría utilizar GraphQl.
- En las busquedas de campos de texto se podrían hacer busquedas parciales.
- No he creado usuarios ni tokens, pero habría que limitar el uso de la API solo a personas autorizadas. (con JWT o lo que sea) Esta parte se puede hacer con un ApiGateway o mediante Middlewares.
- Estaría bien tener PhpStan para tener un código más limpio (namespaces ordenados, código repetido,...) 
- Habría que poner indices en los campos donde se hagan más búsquedas
