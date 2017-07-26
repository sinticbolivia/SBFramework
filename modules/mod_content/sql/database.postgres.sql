CREATE TABLE IF NOT EXISTS content(
	content_id				bigserial not null primary key,
	parent_id				bigint not null default 0,
	title					varchar(512),
	content					text,
	slug					varchar(256),
	author_id				bigint,
	status					varchar(64),
	show_order				integer default 0,
	access_level			text,
	"type"					varchar(128) default 'page',				
	publish_date			timestamp,
	end_date				timestamp,
	lang_code				varchar(5),
	last_modification_date	timestamp,
	creation_date			timestamp
);
CREATE TABLE IF NOT EXISTS content_meta(
	meta_id					bigserial not null primary key,
	content_id				bigint not null,
	meta_key				varchar(128),
	meta_value				text,
	creation_date			timestamp
);
CREATE TABLE IF NOT EXISTS section(
	section_id				bigserial not null primary key,
	name					varchar(512),
	parent_id				integer default 0,
	description				text,
	slug					varchar(256),
	access_level			text,
	status					varchar(64),
	show_order				integer default 0,
	lang_code				varchar(5),
	last_modification_date	timestamp,
	creation_date			timestamp
);
CREATE TABLE IF NOT EXISTS section_meta(
	meta_id					bigserial not null primary key,
	section_id				bigint not null,
	meta_key				varchar(128),
	meta_value				text,
	creation_date			timestamp
);
CREATE TABLE IF NOT EXISTS section2content(
	id						bigserial not null primary key,
	section_id				bigint not null,
	content_id				bigint not null
);
CREATE TABLE IF NOT EXISTS categories(
	category_id				bigserial not null primary key,
	parent_id				bigint default 0,
	name					varchar(512),
	description				text,
	slug					varchar(256) unique,
	status					varchar(64),
	show_order				integer default 0,
	lang_code				varchar(5),
	last_modification_date	timestamp,
	creation_date			timestamp
);
CREATE TABLE IF NOT EXISTS category2content(
	id						bigserial not null primary key,
	category_id				bigint not null,
	content_id				bigint not null
);
