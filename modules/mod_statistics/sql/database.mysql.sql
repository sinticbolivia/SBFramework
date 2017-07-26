CREATE TABLE IF NOT EXISTS user_stats(
	id				bigint not null auto_increment primary key,
	user_id			bigint not null,
	type			varchar(128),
	data			text,
	creation_time	varchar(128),
	creation_date	datetime
);
CREATE TABLE IF NOT EXISTS section_stats(
	id				bigint not null auto_increment primary key,
	user_id			bigint not null,
	section_id		bigint not null,
	type			varchar(128),
	data			text,
	creation_time	varchar(128),
	creation_date	datetime
);
CREATE TABLE IF NOT EXISTS content_stats(
	id				bigint not null auto_increment primary key,
	user_id			bigint not null,
	content_id		bigint not null,
	type			varchar(128),
	data			text,
	creation_time	varchar(128),
	creation_date	datetime
);
