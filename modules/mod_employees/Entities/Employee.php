<?php
namespace SinticBolivia\SBFramework\Modules\Employees\Entities;
use SinticBolivia\SBFramework\Database\Classes\SB_DbEntity;

class Employee extends SB_DbEntity
{
	/**
	 * @primaryKey true
	 * @var BIGINT
	 */
	public	$employee_id;
	/**
	 * @var BIGINT
	 */
    public	$extern_id = 0;
	/**
	 * @var VARCHAR
	 */
    public	$code;
	/**
	 * @var INTEGER
	 */
    public	$store_id;
	/**
	 * @var INTEGER
	 */
    public	$pin;
	/**
	 * @var TINYINT
	 */
    public	$total_hours_work;
	/**
	 * @var INTEGER
	 */
    public	$department_id;
	/**
	 * @var VARCHAR
	 */
    public	$first_name;
	/**
	 * @var VARCHAR
	 */
    public	$last_name;
	/**
	 * @var VARCHAR
	 */
    public	$company;
	/**
	 * @var DATE
	 */
    public	$date_of_birth;
	/**
	 * @var TINYINT
	 */
    public	$age;
	/**
	 * @var INTEGER
	 */
    public	$identity_document;
	/**
	 * @var VARCHAR
	 */
    public	$gender;
	/**
	 * @var VARCHAR
	 */
    public	$phone;
	/**
	 * @var VARCHAR
	 */
    public	$mobile;
	/**
	 * @var VARCHAR
	 */
    public	$fax;
	/**
	 * @var VARCHAR
	 */
    public	$email;
	/**
	 * @var VARCHAR
	 */
    public	$address_1;
	/**
	 * @var VARCHAR
	 */
    public	$address_2;
	/**
	 * @var VARCHAR
	 */
    public	$zip_code;
	/**
	 * @var VARCHAR
	 */
    public	$city;
	/**
	 * @var VARCHAR
	 */
    public	$country;
	/**
	 * @var VARCHAR
	 */
    public	$country_code;
	/**
	 * @var VARCHAR
	 */
    public	$status;
	/**
	 * @var TEXT
	 */
    public	$notes;
	/*
    last_modification_date DATETIME,
    creation_date          DATETIME 
	*/
	
	/**
	 * @reference	[store_id]
	 * @belongsTo	SinticBolivia\SBFramework\Modules\Mb\Entities\MB_Store
	 * @var SinticBolivia\SBFramework\Modules\Mb\Entities\MB_Store
	 */
	protected $store;
}