create table uv_tomador(
	numero_poliza				varchar(30),
	tipo_documento_identidad	char(1),
	numero_documento_identidad	varchar(16) not null primary key,
	extension					char(2),
	primer_apellido				varchar(30),
	segundo_apellido			varchar(30),
	apellido_esposo				varchar(30),
	nombres						varchar(30),
	nacionalidad				char(3),
	fecha_nacimiento			date
);
create table uv_codeudor(
	tipo_documento_identidad	char(1),
	numero_documento_identidad	varchar(16) not null primary key,
	extension					char(2),
	primer_apellido				varchar(30),
	segundo_apellido			varchar(30),
	apellido_esposo				varchar(30),
	nombres						varchar(30),
	nacionalidad				char(3),
	fecha_nacimiento			date
);
create table uv_cobertura(
	numero_poliza				varchar(30) not null primary key,
	tipo_cobertura				char(1),
	cobertura					char(1),
	tasa						decimal(3,2)
);
create table uv_exclusiones(
	numero_poliza				varchar(30) not null primary key,
	tipo_exclusion				tinyint(2)
);
create table uv_plazo_pago_indeminizacion(
	numero_poliza				varchar(30) not null primary key,
	plazo						varchar(160)
);
create table uv_descripcion_exclusion(
	tipo_exclusion				tinyint(2) not null primary key,
	descripcion					text
);
create table uv_descripcion_cobertura(
	cobertura					char(1) not null primary key,
	descripcion					text
);
