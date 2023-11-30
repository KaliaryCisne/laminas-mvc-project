CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE EXTENSION citext;

ALTER TABLE users ALTER COLUMN name TYPE citext;
ALTER TABLE users ALTER COLUMN email TYPE citext;

INSERT INTO users (name, email, password) VALUES ('teste 1', 'teste1@email.com', '$2y$10$vx8zKRtkhLNWxIKEbyo5aOcnzWmOpx7ggZxvTauzH5I8knvu8LTDi');
INSERT INTO users (name, email, password) VALUES ('teste 2', 'teste2@email.com', '$2y$10$vx8zKRtkhLNWxIKEbyo5aOcnzWmOpx7ggZxvTauzH5I8knvu8LTDi');
INSERT INTO users (name, email, password) VALUES ('teste 3', 'teste3@email.com', '$2y$10$vx8zKRtkhLNWxIKEbyo5aOcnzWmOpx7ggZxvTauzH5I8knvu8LTDi');
