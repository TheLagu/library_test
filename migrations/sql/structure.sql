
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- TODO Habría que mirar por que campo se hacen búsquedas y poner índices si es necesario
CREATE TABLE books (
    id bigserial PRIMARY KEY,
    encoded_id uuid NOT NULL UNIQUE DEFAULT uuid_generate_v4(),
    isbn varchar NOT NULL,
    title varchar NOT NULL,
    pages int NOT NULL,
    description varchar NULL,
    topic varchar NULL,
    created_at timestamp NOT NULL DEFAULT now()
);