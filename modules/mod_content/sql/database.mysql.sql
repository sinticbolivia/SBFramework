CREATE TABLE IF NOT EXISTS content(
	content_id				bigint not null auto_increment primary key,
	parent_id				bigint not null default 0,
	title					varchar(128),
	content					text,
	slug					varchar(128),
	author_id				bigint,
	status					varchar(64),
	show_order				integer default 0,
	access_level			text,
	`type`					varchar(128) default 'page',				
	publish_date			datetime,
	end_date				datetime,
	lang_code				varchar(5),
	last_modification_date	datetime,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS content_meta(
	meta_id					bigint not null auto_increment primary key,
	content_id				bigint not null,
	meta_key				varchar(128),
	meta_value				text,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS section(
	section_id				bigint not null auto_increment primary key,
	name					varchar(128),
	parent_id				integer default 0,
	description				text,
	slug					varchar(128),
	access_level			text,
	status					varchar(64),
	show_order				integer default 0,
	lang_code				varchar(5),
	for_object				varchar(64) default 'page',
	last_modification_date	datetime,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS section_meta(
	meta_id					bigint not null auto_increment primary key,
	section_id				bigint not null,
	meta_key				varchar(128),
	meta_value				text,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS section2content(
	id						bigint not null auto_increment primary key,
	section_id				bigint not null,
	content_id				bigint not null
);
CREATE TABLE IF NOT EXISTS categories(
	category_id				bigint not null auto_increment primary key,
	parent_id				bigint default 0,
	name					varchar(128),
	description				text,
	slug					varchar(128) unique,
	status					varchar(64),
	show_order				integer default 0,
	lang_code				varchar(5),
	last_modification_date	datetime,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS category2content(
	id						bigint not null auto_increment primary key,
	category_id				bigint not null,
	content_id				bigint not null
);
CREATE TABLE IF NOT EXISTS tags(
	id 						bigint not null auto_increment primary key,
	object_type				varchar(128),
	object_id				bigint not null,
	str						varchar(128),
	creation_date			datetime
);