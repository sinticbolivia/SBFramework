<?php
namespace SinticBolivia\SBFramework\Modules\Users\Controllers;
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_ApiRest;
use SinticBolivia\SBFramework\Modules\Users\Models\UsersModel;
use Exception;
use SinticBolivia\SBFramework\Modules\Users\Classes\ApiBase;

class ApiController extends ApiBase
{
	/**
	 * @namespace SinticBolivia\SBFramework\Modules\Users\Models
	 * @var UsersModel
	 */
	protected $usersModel;
	/**
	 * @access	FREE
	 * @method	POST
	 * @return  string JWT
	 */
	public function TaskAuthenticate()
	{
		
		try
		{
			$body 	= $this->request->ToJSON();
			
			if( !isset($body->username) )
				throw new Exception($this->__('The username is required'));
			if( !isset($body->password) )
				throw new Exception(html_entity_decode ($this->__('The password is required')));
				
			$user = $this->usersModel->GetBy('username', $body->username);
			
			if( !$user )
				throw new Exception(html_entity_decode ($this->__('Invalid username or password')));
			if( $user->pwd != md5($body->password) )
				throw new Exception(html_entity_decode ($this->__('Invalid username or password')));
			
			
			$token = array(
				"iss" => BASEURL,
				"aud" => BASEURL,
				"iat" => time(),
				"nbf" => time(),
				'user' => $user
			);
			sb_include_lib('php-jwt/JWT.php');
			$jwt = \Firebase\JWT\JWT::encode($token, $this->key, 'HS512');
			//var_dump(__METHOD__);die($jwt);
			$this->Response(array('token' => $jwt));
		}
		catch(Exception $e)
		{
			$this->ResponseError($e->getMessage());
		}
	}
	/**
	 * @access	PROTECTED
	 * @method	GET
	 * @return  void
	 */
	public function TaskDefault()
	{
		$users = $this->usersModel->GetAllUsers();
		$this->Response($users);
	}
	/**
	 * 
	 * @access	PROTECTED
	 * @method	GET
	 * @return  SB_User
	 */
	public function TaskGet()
	{
		$id = $this->request->getInt('id');
		try
		{
			$user = $this->usersModel->Get($id);
			$this->Response($user);
		}
		catch(Exception $e)
		{
			$this->ResponseError($e->getMessage());
		}
		
	}
}