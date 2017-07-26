CREATE TABLE IF NOT EXISTS content(
	content_id				integer not null primary key autoincrement,
	parent_id				integer not null default 0,
	title					varchar(512),
	content					text,
	slug					varchar(256),
	author_id				integer,
	status					varchar(64),
	show_order				integer default 0,
	access_level			text,
	[type]					varchar(128) default 'page',				
	publish_date			datetime,
	end_date				datetime,
	lang_code				varchar(5),
	last_modification_date	datetime,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS content_meta(
	meta_id					integer not null primary key autoincrement,
	content_id				integer not null,
	meta_key				varchar(128),
	meta_value				text,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS section(
	section_id				integer not null primary key autoincrement,
	name					varchar(512),
	parent_id				integer default 0,
	description				text,
	slug					varchar(256),
	access_level			text,
	status					varchar(64),
	show_order				integer default 0,
	lang_code				varchar(5),
	for_object				varchar(64) default 'page',
	last_modification_date	datetime,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS section_meta(
	meta_id					integer not null primary key autoincrement,
	section_id				integer not null,
	meta_key				varchar(128),
	meta_value				text,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS section2content(
	id						integer not null primary key autoincrement,
	section_id				integer not null,
	content_id				integer not null
);
CREATE TABLE IF NOT EXISTS categories(
	category_id				integer not null primary key autoincrement,
	parent_id				integer default 0,
	name					varchar(512),
	description				text,
	slug					varchar(256) unique,
	status					varchar(64),
	show_order				integer default 0,
	lang_code				varchar(5),
	last_modification_date	datetime,
	creation_date			datetime
);
CREATE TABLE IF NOT EXISTS category2content(
	id						integer not null primary key autoincrement,
	category_id				integer not null,
	content_id				integer not null
);
