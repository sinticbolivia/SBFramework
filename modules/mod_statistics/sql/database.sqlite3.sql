CREATE TABLE IF NOT EXISTS user_stats(
	id				integer not null primary key autoincrement,
	user_id			integer not null,
	type			varchar(128),
	data			text,
	creation_time	varchar(128),
	creation_date	datetime
);
CREATE TABLE IF NOT EXISTS section_stats(
	id				integer not null primary key autoincrement,
	user_id			integer not null,
	section_id		integer not null,
	type			varchar(128),
	data			text,
	creation_time	varchar(128),
	creation_date	datetime
);
CREATE TABLE IF NOT EXISTS content_stats(
	id				integer not null primary key autoincrement,
	user_id			integer not null,
	content_id		integer not null,
	type			varchar(128),
	data			text,
	creation_time	varchar(128),
	creation_date	datetime
);
