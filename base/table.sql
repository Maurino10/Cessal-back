CREATE DATABASE cessal;
\c cessal;

CREATE SEQUENCE admin_seq;
CREATE TABLE admin (
    id VARCHAR DEFAULT CONCAT('ADMN', LPAD(NEXTVAL('admin_seq')::TEXT, 2, '0')) PRIMARY KEY,
    login VARCHAR UNIQUE NOT NULL,
    password TEXT NOT NULL
);


CREATE SEQUENCE province_seq;
CREATE TABLE province (
    id VARCHAR DEFAULT CONCAT('PRVC', LPAD(NEXTVAL('province_seq')::TEXT, 2, '0')) PRIMARY KEY,
    name VARCHAR NOT NULL
);

CREATE SEQUENCE ca_seq;
CREATE TABLE ca(
    id VARCHAR DEFAULT CONCAT('CA', LPAD(NEXTVAL('ca_seq')::TEXT, 3, '0')) PRIMARY KEY,
    name VARCHAR NOT NULL,

    id_province VARCHAR NOT NULL REFERENCES province(id)
);

CREATE SEQUENCE region_seq;
CREATE TABLE region(
    id VARCHAR DEFAULT CONCAT('RGN', LPAD(NEXTVAL('region_seq')::TEXT, 3, '0')) PRIMARY KEY,
    name VARCHAR NOT NULL,

    id_province VARCHAR NOT NULL REFERENCES province(id)
);


CREATE SEQUENCE district_seq;
CREATE TABLE district(
    id VARCHAR DEFAULT CONCAT('DSTRCT', LPAD(NEXTVAL('district_seq')::TEXT, 4, '0')) PRIMARY KEY,
    name VARCHAR NOT NULL,

    id_region VARCHAR NOT NULL REFERENCES region(id)
);

CREATE SEQUENCE tpi_seq;
CREATE TABLE tpi (
    id VARCHAR DEFAULT CONCAT('TPI', LPAD(NEXTVAL('tpi_seq')::TEXT, 4, '0')) PRIMARY KEY,
    name VARCHAR NOT NULL,
   
    id_ca VARCHAR NOT NULL REFERENCES ca(id),
    id_district VARCHAR NOT NULL REFERENCES district(id)
);


CREATE SEQUENCE post_seq;
CREATE TABLE post (
    id VARCHAR DEFAULT CONCAT('POST', LPAD(NEXTVAL('post_seq')::TEXT, 2, '0')) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    role VARCHAR NOT NULL
);


CREATE SEQUENCE gender_seq;
CREATE TABLE gender (
    id VARCHAR DEFAULT CONCAT('GNR', LPAD(NEXTVAL('gender_seq')::TEXT, 1, '0')) PRIMARY KEY,
    name VARCHAR NOT NULL
);


CREATE TABLE inscription (
    id SERIAL PRIMARY KEY,
    last_name VARCHAR,
    first_name VARCHAR NOT NULL,
    birthday date NOT NULL,
    address TEXT NOT NULL,
    cin NUMERIC(12) UNIQUE NOT NULl,
    immatriculation VARCHAR UNIQUE NOT NULL,
    email VARCHAR UNIQUE NOT NULL,
    password TEXT NOT NULL,

    date_inscription TIMESTAMP NOT NULl,
    date_acceptation TIMESTAMP DEFAULT NULl,
    status NUMERIC(1) DEFAULT 0,


    id_gender VARCHAR NOT NULL REFERENCES gender(id),
    id_tpi VARCHAR NOT NULL REFERENCES tpi(id),
    id_post VARCHAR NOT NULL REFERENCES post(id)
);

CREATE SEQUENCE profil_seq;
CREATE TABLE profil (
    id VARCHAR DEFAULT CONCAT('PR', LPAD(NEXTVAL('profil_seq')::TEXT, 6, '0')) PRIMARY KEY,
    last_name VARCHAR,
    first_name VARCHAR NOT NULL,
    birthday date NOT NULL,
    address TEXT NOT NULL,
    cin NUMERIC(12) UNIQUE NOT NULl,
    immatriculation VARCHAR UNIQUE NOT NULL,
    email VARCHAR UNIQUE NOT NULL,

    id_gender VARCHAR NOT NULL REFERENCES gender(id)
);

CREATE SEQUENCE users_seq;
CREATE TABLE users (
    id VARCHAR DEFAULT CONCAT('USR', LPAD(NEXTVAL('users_seq')::TEXT, 6, '0')) PRIMARY KEY,
    password TEXT NOT NULL,

    id_profil VARCHAR NOT NULL REFERENCES profil(id),
    id_tpi VARCHAR NOT NULL REFERENCES tpi(id),
    id_post VARCHAR NOT NULL REFERENCES post(id)
);


CREATE SEQUENCE cession_seq;
CREATE TABLE cession (
    id VARCHAR DEFAULT CONCAT('CES', LPAD(NEXTVAL('cession_seq')::TEXT, 12, '0')) PRIMARY KEY,
    numero_dossier VARCHAR UNIQUE NOT NULL,
    date_contrat TIMESTAMP NOT NULL,
    request_subject TEXT NOT NULL,
    reimbursed_amount NUMERIC(10, 2) NOT NULL,
    date_cession TIMESTAMP NOT NULL,
    status_cession NUMERIC(1) DEFAULT 0,

    id_tpi VARCHAR NOT NULL REFERENCES tpi(id),
    id_user VARCHAR NOT NULL REFERENCES users(id) -- Greffier
);
-- ALTER TABLE cession ADD COLUMN date_contrat TIMESTAMP;
-- ALTER TABLE cession ALTER COLUMN date_contrat SET NOT NULL;

CREATE TABLE cession_magistrat (
    id SERIAL PRIMARY KEY,

    id_cession VARCHAR NOT NULL REFERENCES cession(id),
    id_user VARCHAR NOT NULL REFERENCES users(id) -- Magistrat
);

CREATE TABLE cession_ordonnance (
    id SERIAL PRIMARY KEY,
    numero_ordonnance VARCHAR UNIQUE,

    id_cession VARCHAR NOT NULL REFERENCES cession(id)
);

CREATE TABLE cession_party (
    id SERIAL PRIMARY KEY,
    last_name VARCHAR,
    first_name VARCHAR,
    -- address VARCHAR NOT NULL,
    cin NUMERIC(12) UNIQUE,

    id_gender VARCHAR NOT NULL REFERENCES gender(id)
);  

CREATE TABLE cession_party_address (
    id SERIAL PRIMARY KEY,
    address VARCHAR NOT NULL,
    date_address TIMESTAMP NOT NULL,

    id_cession_party INT NOT NULL REFERENCES cession_party(id)
);

CREATE TABLE cession_entity (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    address VARCHAR NOT NULL

    id_tpi VARCHAR NOT NULL REFERENCES tpi(id)
);
ALTER TABLE cession_entity
ADD CONSTRAINT unique_name_address_per_tpi UNIQUE (name, address, id_tpi);


CREATE TABLE cession_lender (
    id SERIAL PRIMARY KEY,
    
    id_cession VARCHAR NOT NULL REFERENCES cession(id),
    id_cession_party INT NOT NULL REFERENCES cession_party(id),
    id_cession_entity INT REFERENCES cession_entity(id),
    type VARCHAR(8),

    CONSTRAINT check_party_or_entity CHECK (
        (id_cession_party IS NOT NULL) OR (id_cession_entity IS NOT NULL)
    )
);

CREATE TABLE cession_borrower (
    id SERIAL PRIMARY KEY,
    salary_amount NUMERIC(10, 2) NOT NULL, -- montant revenu
    remark TEXT, -- Observation

    id_cession VARCHAR NOT NULL REFERENCES cession(id),
    id_cession_party INT NOT NULL REFERENCES cession_party(id)
);

CREATE TABLE cession_justificatif (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,      -- nom du fichier uploadé
    path VARCHAR NOT NULL,            -- chemin dans storage
    type VARCHAR,                       -- mime type (pdf, jpg, png...)
    size BIGINT,                      -- taille en octets

    id_cession VARCHAR NOT NULL REFERENCES cession(id)
);

CREATE TABLE cession_borrower_quota (
    id SERIAL PRIMARY KEY,

    granted_amount NUMERIC(10, 2) NOT NULL, -- Montant accordé

    id_cession_borrower INT NOT NULL REFERENCES cession_borrower(id)
);

CREATE TABLE cession_provision (
    id SERIAL PRIMARY KEY,
    provision_amount NUMERIC(6, 2) NOT NULL 
);

CREATE TABLE cession_reference (
    id SERIAL PRIMARY KEY,
    numero_recu VARCHAR(15) NOT NULL UNIQUE,
    numero_feuillet VARCHAR(15) NOT NULL UNIQUE,
    numero_repertoire VARCHAR(15) NOT NULL UNIQUE,
    date TIMESTAMP NOT NULL,
    provision NUMERIC(10, 2) NOT NULL,

    id_cession_borrower INT NOT NULL REFERENCES cession_borrower(id)
);
ALTER TABLE cession_reference ADD COLUMN provision NUMERIC(10, 2);

CREATE TABLE temp_tpi (
    id SERIAL PRIMARY KEY,
    structure_parente VARCHAR,
    structure_fille VARCHAR,
    province VARCHAR,
    region VARCHAR,
    district VARCHAR
);