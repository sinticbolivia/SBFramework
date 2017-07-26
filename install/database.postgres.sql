CREATE TABLE IF NOT EXISTS attachments ( 
    attachment_id          bigserial  NOT NULL PRIMARY KEY,
    object_type            varchar(128),
    object_id              integer,
    title                  varchar(128),
    description            text,
    type                   TEXT,
    mime                   TEXT,
    file                   TEXT,
    size                   TEXT,
    parent                 INTEGER DEFAULT 0,
    last_modification_date timestamp,
    creation_date          timestamp
);
CREATE TABLE IF NOT EXISTS modules ( 
    module_id     BIGSERIAL        NOT NULL PRIMARY KEY,
    name          VARCHAR( 256 ),
    description   TEXT,
    module_key    VARCHAR( 128 ),
    library_name  VARCHAR( 128 ),
    file          VARCHAR( 256 ),
    status        VARCHAR( 128 ),
    creation_date timestamp
);
CREATE TABLE IF NOT EXISTS "parameters" ( 
    id            BIGSERIAL NOT NULL PRIMARY KEY,
    "key"         varchar(128),
    "value"         TEXT,
    creation_date timestamp
);
CREATE TABLE IF NOT EXISTS users ( 
    user_id       			BIGSERIAL  NOT NULL       PRIMARY KEY,
    first_name    			VARCHAR( 100 ),
    last_name     			VARCHAR( 100 ),
    username      			varchar(128),
    pwd           			varchar(512),
    email         			varchar(128),
    status        			varchar(32),
    role_id       			INTEGER         NOT NULL,
    store_id				INTEGER,
    last_modification_date	timestamp,
    creation_date 			timestamp
);
CREATE TABLE IF NOT EXISTS user_meta ( 
    meta_id       BIGSERIAL NOT NULL PRIMARY KEY,
    user_id       INTEGER,
    meta_key      varchar(128),
    meta_value    TEXT,
    creation_date timestamp
);

CREATE TABLE IF NOT EXISTS user_roles ( 
    role_id                	BIGSERIAL NOT NULL PRIMARY KEY,
    role_name              	varchar(128),
    role_description       	TEXT,
    role_key				varchar(128),
    last_modification_date 	timestamp,
    creation_date          	timestamp 
);
CREATE TABLE IF NOT EXISTS role2permission ( 
    id            BIGSERIAL NOT NULL PRIMARY KEY,
    role_id       INTEGER NOT NULL,
    permission_id INTEGER NOT NULL,
    creation_date timestamp 
);
CREATE TABLE IF NOT EXISTS permissions ( 
    permission_id			BIGSERIAL NOT NULL PRIMARY KEY,
    `group`					varchar(128),
    permission             	varchar(256),
    attributes             	TEXT,
    label                  	VARCHAR( 100 ),
    last_modification_date 	timestamp,
    creation_date          	timestamp 
);
