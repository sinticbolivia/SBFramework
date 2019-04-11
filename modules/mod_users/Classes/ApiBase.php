<?php
namespace SinticBolivia\SBFramework\Modules\Users\Classes;
use SinticBolivia\SBFramework\Classes\SB_ApiRest;
use SinticBolivia\SBFramework\Classes\SB_ApiRestException;
use Exception;
use Firebase\JWT\SignatureInvalidException;

abstract class ApiBase extends SB_ApiRest
{
	protected $key = "__sbframework_api_key__";
	
	protected function ValidateAuthentication()
	{
		parent::ValidateAuthentication();
		sb_include_lib('php-jwt/JWT.php');
		try
		{
			$jwt = \Firebase\JWT\JWT::decode($this->token, $this->key, array('HS512'));
		}
        catch(SignatureInvalidException $e)
        {
            $error = $e->getMessage() . ", TOKEN: {$this->token}";
            throw new SB_ApiRestException($error, $this);
        }
		catch(Exception $e)
		{
            throw new SB_ApiRestException($e->getMessage(), $this);
			//header('HTTP/1.0 401 Unauthorized');
			//$this->ResponseError($e->getMessage(), 511); //network authentication required (error => 511)
		}
		
	}
}