create table mb_product_finishes(
	id				bigint unsigned not null auto_increment primary key,
	code			varchar(128),
	name			varchar(128),
	image_file		varchar(128),
	creation_date	datetime
);
create table mb_product2finish(
	id 				bigint unsigned not null auto_increment primary key,
	product_id		bigint unsigned not null,
	finish_id		bigint unsigned not null,
	creation_date	datetime
);