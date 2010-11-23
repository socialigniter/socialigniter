<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name: 		Auth Model
* 
* Author:  		Brennan Novak severely hacked Ben Edmunds 'Ion Auth Model' which was based on Redux Auth 2
* 		   		contact@social-igniter.com
				@brennannovak
* 
* Added Awesomeness: Phil Sturgeon
* 
* Location: http://github.com/socialigniter/core
*          
* Created:  10.01.2009 
* Modified: 04.01.2010 Brennan Novak
* 
* Description:  Modified auth system based on redux_auth with extensive customization.
*/ 

class Auth_model extends CI_Model
{
	public $tables = array();	
	public $activation_code;	
	public $forgotten_password_code;	
	public $new_password;	
	public $identity;
	
	public function __construct()
	{
		parent::__construct();

		$this->columns 			= $this->config->item('columns');
		$this->identity_column 	= $this->config->item('identity');
	    $this->store_salt      	= $this->config->item('store_salt');
	    $this->salt_length     	= $this->config->item('salt_length');
	}
	
	/* Hash password : Hashes the password to be stored in the database.
     * Hash password db : This function takes a password and validates it
     * against an entry in the users table.
     * Salt : Generates a random salt value.
	 * @author Mathew
	 * Hashes the password to be stored in the database.
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password($password, $salt=false)
	{
	    if (empty($password))
	    {
	    	return FALSE;
	    }
	    
	    if ($this->store_salt && $salt) 
		{
			return  sha1($password . $salt);
		}
		else 
		{
	    	$salt = $this->salt();
	    	return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}		
	}
	
	/* This function takes a password and validates it
     * against an entry in the users table.
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password_db($identity, $password)
	{
	   if (empty($identity) || empty($password))
	   {
	        return FALSE;
	   }
	   
	   $query = $this->db->select('password')
	   					 ->select('salt')
						 ->where($this->identity_column, $identity)
						 ->where($this->social_auth->_extra_where)
						 ->limit(1)
						 ->get('users');
            
        $result = $query->row();
        
		if ($query->num_rows() !== 1)
		{
		    return FALSE;
		}

		if ($this->store_salt) 
		{
			return sha1($password . $result->salt);
		}
		else 
		{
			$salt = substr($result->password, 0, $this->salt_length);
	
			return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}
	
	// Generates a random salt value. @return void @author Mathew
	function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}

	/* Activation functions
     * Activate : Validates and removes activation code.
     * Deactivae : Updates a users row with an activation code.
	 * @author Mathew	
	 * activate @return void @author Mathew */
	function activate($id, $code = false)
	{	    
	    if ($code != false) 
	    {  
		    $query = $this->db->select($this->identity_column)
	        	->where('activation_code', $code)
	        	->limit(1)
	        	->get('users');
	                	      
			$result = $query->row();
	        
			if ($query->num_rows() !== 1)
			{
				return FALSE;
			}
		    
			$identity = $result->{$this->identity_column};
			
			$data = array(
				'activation_code' => '',
				'active'          => 1
			);
	        
			$this->db->where($this->social_auth->_extra_where);
			$this->db->update('users', $data, array($this->identity_column => $identity));
	    }
	    else 
	    {
			if (!$this->social_auth->is_admin()) 
			{
				return false;
			}

			$data = array(
				'activation_code' => '',
				'active' => 1
			);
		   
			$this->db->where($this->social_auth->_extra_where);
			$this->db->update('users', $data, array('user_id' => $id));
	    }
		
		return $this->db->affected_rows() == 1;
	}
		
	function deactivate($id = 0)
	{
	    if (empty($id))
	    {
	        return FALSE;
	    }
	    
		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		
		$data = array(
			'activation_code' => $activation_code,
			'active'          => 0
		);
        
		$this->db->where($this->social_auth->_extra_where);
		$this->db->update('users', $data, array('user_id' => $id));
		
		return $this->db->affected_rows() == 1;
	}

	function change_password($identity, $old, $new)
	{
	    $query = $this->db->select('password,salt')
						  ->where($this->identity_column, $identity)
						  ->where($this->social_auth->_extra_where)
						  ->limit(1)
						  ->get('users');
 
	    $result = $query->row();
 
	    $db_password = $result->password;
	    $old         = $this->hash_password_db($identity, $old);
	    $new         = $this->hash_password($new, $result->salt);
 
	    if ($db_password === $old)
	    {
	        $data = array('password' => $new);
 
	        $this->db->where($this->social_auth->_extra_where);
	        $this->db->update('users', $data, array($this->identity_column => $identity));
 
	        return TRUE;
	    }
 
	    return FALSE;
	}
		
	function username_check($username = '')
	{
	    if (empty($username))
	    {
	        return FALSE;
	    }
		   
	    return $this->db->where('username', $username)->where($this->social_auth->_extra_where)->count_all_results('users') > 0;
	}
	
	function email_check($email = '')
	{
	    if (empty($email))
	    {
	        return FALSE;
	    }
		   
	    return $this->db->where('email', $email)->where($this->social_auth->_extra_where)->count_all_results('users') > 0;
	}
	
	function identity_check($identity = '')
	{
	    if (empty($identity))
	    {
	        return FALSE;
	    }
	    
	    return $this->db->where($this->identity_column, $identity)->count_all_results('users') > 0;
	}

	function forgotten_password($email = '')
	{
	    if (empty($email))
	    {
	        return FALSE;
	    }
	    
		$key = $this->hash_password(microtime().$email);
			
		$this->forgotten_password_code = $key;
		
		$this->db->where($this->social_auth->_extra_where);
		   
		$this->db->update('users', array('forgotten_password_code' => $key), array('email' => $email));
		
		return $this->db->affected_rows() == 1;
	}
	
	function forgotten_password_complete($code, $salt=FALSE)
	{
	    if (empty($code))
	    {
	        return FALSE;
	    }
		   
	   	$this->db->where('forgotten_password_code', $code);

	   	if ($this->db->count_all_results('users') > 0) 
        {
        	$password = $this->salt();
		    
            $data = array(
            	'password'                => $this->hash_password($password, $salt),
                'forgotten_password_code' => '0',
                'active'                  => 1
            );
		   
            $this->db->update('users', $data, array('forgotten_password_code' => $code));

            return $password;
        }
        
        return FALSE;
	}

	function profile($identity = '')
	{ 
	    if (empty($identity))
	    {
	        return FALSE;
	    }
	    
		$this->db->select(array(
	    	'users.user_id',
	    	'users.username',
	    	'users.password',
	    	'users.salt',
	    	'users.email',
	    	'users.activation_code',
	    	'users.forgotten_password_code',
	    	'users.ip_address',
	    	'users.active',
	    	'users_meta.name',
	    	'users_meta.image',
	    	'users_level.level AS `user_level`',
	    	'users_level.description'
	    ));

		if (!empty($this->columns))
        {
            foreach ($this->columns as $field)
            {
                $this->db->select('users_meta.'.$field);
            }
        }

		$this->db->join('users_meta', 'users.user_id = users_meta.user_id', 'left');
		$this->db->join('users_level', 'users.user_level_id = users_level.user_level_id', 'left');
		
		if (strlen($identity) === 40)
	    {
	        $this->db->where('users.forgotten_password_code', $identity);
	    }
	    else
	    {
	        $this->db->where('users.'.$this->identity_column, $identity);
	    }
	    
		$this->db->where($this->social_auth->_extra_where);
		   
		$this->db->limit(1);
		$i = $this->db->get('users');
		
		return ($i->num_rows > 0) ? $i->row() : FALSE;
	}

	function register($username, $password, $email, $additional_data=false, $group_name=false)
	{
	
	    if ($this->identity_column == 'email' && $this->email_check($email))
	    {
			$this->social_auth->set_error('account_creation_duplicate_email');
	    	return FALSE;
	    } 
	    elseif ($this->identity_column == 'username' && $this->username_check($username))
	    {
	    	$this->social_auth->set_error('account_creation_duplicate_username');
	    	return FALSE;
	    }

		// Are Basics met
	    if (empty($username) || empty($password) || empty($email) || $this->email_check($email))
	    {
	        return FALSE;
	    }
	    
	    // If Username is taken append increment
	    if ($this->identity != 'username') 
	    {
		    for ($i = 0; $this->username_check($username); $i++)
		    {
		    	if($i > 0)
		    	{
		    		$username .= $i;
		    	}
		    }
	    }
	    
        // Group
		if(empty($group_name))
        {
        	$group_name = config_item('default_group');
        }
       
	    $user_level_id	= $this->db->select('user_level_id')->where('level', $group_name)->get('users_level')->row()->user_level_id;
        $ip_address		= $this->input->ip_address();
        
        if ($this->store_salt) 
        {
        	$salt = $this->salt();
        }
        else 
        {
        	$salt = false;
        }
		$password = $this->hash_password($password, $salt);
		
        // Users table.
		$user_data = array(
			'username'   		=> $username, 
			'password'   		=> $password,
  			'salt'				=> $salt,
  			'email'      		=> $email,
			'user_level_id'   	=> $user_level_id,
			'ip_address' 		=> $ip_address,
        	'created_on' 		=> now(),
			'last_login' 		=> now(),
			'active'     		=> 1
		);
		
		if ($this->store_salt) 
        {
        	$data['salt'] = $salt;
        }
		  				
		$this->db->insert('users', $user_data);		
		$user_id = $this->db->insert_id();
        
		// Meta table.		
		$data = array('user_id' => $user_id);
		
		if (!empty($this->columns))
	    {
	        foreach ($this->columns as $input)
	        {
	        	if (is_array($additional_data) && isset($additional_data[$input])) 
	        	{
	        		$data[$input] = $additional_data[$input];	
	        	}
	        	else 
	        	{
	            	$data[$input] = $this->input->post($input);
	        	}
	        }
	    }
        
		$this->db->insert('users_meta', $data);
		return $this->db->affected_rows() > 0 ? $user_id : false;			
	}

	function social_register($username, $email, $additional_data=false)
	{
	    // If Username is taken append increment
	    if ($this->identity != 'username') 
	    {
		    for ($i = 0; $this->username_check($username); $i++)
		    {
		    	if ($i > 0)
		    	{
		    		$username .= $i;
		    	}
		    }
	    }
	    
	    $user_level_id	= $this->db->select('user_level_id')->where('level', config_item('default_group'))->get('users_level')->row()->user_level_id;
        $ip_address		= $this->input->ip_address();
        
        if ($this->store_salt) 
        {
        	$salt = $this->salt();
        }
        else 
        {
        	$salt = false;
        }
        
		$password = $this->hash_password('social_signup', $salt);
		
        // Users table.
		$user_data = array(
			'username'   		=> $username, 
			'password'   		=> $password,
  			'salt'				=> $salt,
  			'email'      		=> $email,
			'user_level_id'   	=> $user_level_id,
			'ip_address' 		=> $ip_address,
        	'created_on' 		=> now(),
			'last_login' 		=> now(),
			'active'     		=> 1
		);
		
		if ($this->store_salt) 
        {
        	$data['salt'] = $salt;
        }
		  				
		$this->db->insert('users', $user_data);		
		$user_id = $this->db->insert_id();
        
		// Meta table.		
		$data = array('user_id' => $user_id);
		
		if (!empty($this->columns))
	    {
	        foreach ($this->columns as $input)
	        {
	        	if (is_array($additional_data) && isset($additional_data[$input])) 
	        	{
	        		$data[$input] = $additional_data[$input];	
	        	}
	        	else 
	        	{
	            	$data[$input] = $this->input->post($input);
	        	}
	        }
	    }
        
		$this->db->insert('users_meta', $data);
		return $this->db->affected_rows() > 0 ? $user_id : false;			
	}
	
	function login($identity, $password, $remember=FALSE)
	{
	    if (empty($identity) || empty($password) || !$this->identity_check($identity))
	    {
	        return FALSE;
	    }
	    
	    $this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_meta', 'users_meta.user_id = users.user_id');
		$this->db->join('users_level', 'users_level.user_level_id = users.user_level_id');
		$this->db->where('users.'.$this->identity_column, $identity);
		$this->db->where('active', 1);
		$this->db->limit(1);
 		$user = $this->db->get()->row();
        
        if ($user)
        {
            $password = $this->hash_password_db($identity, $password);
            
    		if ($user->password === $password)
    		{
        		$this->update_last_login($user->user_id);
        		
    		    $this->session->set_userdata($this->identity_column,  $user->{$this->identity_column});
    		    $this->session->set_userdata('user_id',  $user->user_id);
    		    $this->session->set_userdata('username',  $user->username); 
       		    $this->session->set_userdata('user_level_id',  $user->user_level_id);
    		    $this->session->set_userdata('user_level',  $user->level);
    		    $this->session->set_userdata('name',  $user->name);
    		    $this->session->set_userdata('image',  $user->image);
    		    $this->session->set_userdata('language',  $user->language);
    		    $this->session->set_userdata('time_zone',  $user->time_zone);
    		    $this->session->set_userdata('geo_enabled',  $user->geo_enabled);
    		    $this->session->set_userdata('privacy',  $user->privacy);
    		    
    		    if ($remember && config_item('remember_users'))
    		    {
    		    	$this->remember_user($user->user_id);
    		    }
    		    
    		    return TRUE;
    		}
        }
        
		return FALSE;		
	}

	function social_login($user_id, $connection)
	{
	    if (empty($user_id))
	    {
	        return FALSE;
	    }
	    
	    $this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_meta', 'users_meta.user_id = users.user_id');
		$this->db->join('users_level', 'users_level.user_level_id = users.user_level_id');
		$this->db->where('users.user_id', $user_id);
		$this->db->where('active', 1);
		$this->db->limit(1);
 		$user = $this->db->get()->row();

		if ($user)
		{
		    $this->session->set_userdata($this->identity_column, $user->{$this->identity_column});
		    $this->session->set_userdata('user_id', $user->user_id);
		    $this->session->set_userdata('username', $user->username);
		    $this->session->set_userdata('user_level_id', $user->user_level_id);
		    $this->session->set_userdata('user_level', $user->level);
		    $this->session->set_userdata('name', $user->name);
		    $this->session->set_userdata('image', $user->image);
		    $this->session->set_userdata('language', $user->language);
		    $this->session->set_userdata('time_zone', $user->time_zone);
		    $this->session->set_userdata('geo_enabled', $user->geo_enabled);
		    $this->session->set_userdata('privacy', $user->privacy);

    		$this->update_last_login($user->user_id);

		    return TRUE;
        }
        
		return FALSE;
	}
	
	function get_users($group = false)
	{
		$this->db->select(array('users.*', 'users_level.level AS `user_level`', 'users_level.description'));
	    
		if (!empty($this->columns))
        {
            foreach ($this->columns as $field)
            {
                $this->db->select('users_meta.'.$field);
            }
        }
        
		$this->db->join('users_meta', 'users.user_id = users_meta.user_id', 'left');
		$this->db->join('users_level', 'users.user_level_id = users_level.user_level_id', 'left');

		if (is_string($group))
		{
			$this->db->where('users_level.level', $group);
		}
		else if (is_array($group))
		{
			$this->db->where_in('users_level.level', $group);
		}

		if (isset($this->social_auth->_extra_where))
		{
			$this->db->where($this->social_auth->_extra_where);
		}

		return $this->db->get('users');		
	}

	function get_user_row()
	{
		$this->db->select(array('users.*', 'users_level.level AS `user_level`', 'users_level.description'));
	    
		if (!empty($this->columns))
        {
            foreach ($this->columns as $field)
            {
                $this->db->select('users_meta.'.$field);
            }
        }
        
		$this->db->join('users_meta', 'users.user_id = users_meta.user_id');
		$this->db->join('users_level', 'users.user_level_id = users_level.user_level_id');

		if (isset($this->social_auth->_extra_where))
		{
			$this->db->where($this->social_auth->_extra_where);
		}

		return $this->db->get('users')->row();		
	}

	
	function get_active_users($group_name = false)
	{
	    $this->db->where('users.active', 1);
		return $this->get_users($group_name);
	}
	
	function get_inactive_users($group_name = false)
	{
	    $this->db->where('users.active', 0);
		return $this->get_users($group_name);
	}
	
	function get_user($id=false)
	{
		if (empty($id)) $id = $this->session->userdata('user_id');
		
		$this->db->where('users.user_id', $id);
		$this->db->limit(1);
		return $this->get_user_row();
	}
	
	function get_user_by_email($email)
	{
		$this->db->where('users.email', $email);
		$this->db->limit(1);
		return $this->get_user_row();
	}

	function get_user_by_username($username)
	{
		$this->db->where('users.username', $username);
		$this->db->limit(1);
		return $this->get_user_row();
	}
	
	function get_newest_users($limit = 10)
  	{
    	$this->db->order_by('users.created_on', 'desc');
    	$this->db->limit($limit);
    	return $this->get_users();
  	}
	
	function get_users_group($id=false)
	{
		if (!$id)  
		{
			$id = $this->session->userdata('user_id');
		}
		
	    $query = $this->db->select('user_level_id')
						  ->where('user_id', $id)
						  ->get('users');

		$user = $query->row();
		
		return $this->db->select('level, level_name, description')
						->where('user_level_id', $user->user_level_id)
						->get('users_level')
						->row();
	}

	function update_user($user_id, $data)
	{
	    $this->db->trans_begin();
		
	    if (!empty($this->columns))
	    {
			$this->db->where('user_id', $user_id);
			
	        foreach ($this->columns as $field)
	        {
	        	if (is_array($data) && isset($data[$field])) 
	        	{
	            	$this->db->set($field, $data[$field]);

	            	unset($data[$field]);
	        	}
	        }

	        $this->db->update('users_meta');
	    }
	    
        if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) 
        {
	        if (array_key_exists('password', $data))
			{
			    $data['password'] = $this->hash_password($data['password']);
			}
	
			$this->db->where($this->social_auth->_extra_where);
	
			$this->db->update('users', $data, array('user_id' => $user_id));
        }
            
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return FALSE;
		}
		else
		{
		    $this->db->trans_commit();
		    return TRUE;
		}
	}
	
	function delete_user($id)
	{
		$this->db->trans_begin();
		
		$this->db->delete('users_meta', array('user_id' => $id));
		$this->db->delete('users', array('user_id' => $id));
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return FALSE;
		}
		else
		{
		    $this->db->trans_commit();
		    return TRUE;
		}
	}
	
	function update_last_login($user_id)
	{
		
		if (isset($this->social_auth->_extra_where))
		{
			$this->db->where($this->social_auth->_extra_where);
		}
		
		$this->db->update('users', array('last_login' => now()), array('user_id' => $user_id));
		
		return $this->db->affected_rows() == 1;
	}
	
	function set_lang($lang = 'en')
	{
		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $this->config->item('user_expire') + time()
		));
		
		return TRUE;
	}	

	function login_remembered_user()
	{
		if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity')))
		{
			return FALSE;
		}

		// Get User
        if (isset($this->social_auth->_extra_where))
		{
			$this->db->where($this->social_auth->_extra_where);
		}					
					
	    $this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_meta', 'users_meta.user_id = users.user_id');
		$this->db->join('users_level', 'users_level.user_level_id = users.user_level_id');
		$this->db->where($this->identity_column, get_cookie('identity'));
		$this->db->where('remember_code', get_cookie('remember_code'));
		$this->db->limit(1);
 		$user = $this->db->get()->row();		
					
		if ($user)
		{
			$this->session->set_userdata($this->identity_column,  $user->{$this->identity_column});
			$this->session->set_userdata('user_id',  $user->user_id);
			$this->session->set_userdata('user_level_id',  $user->user_level_id);
			$this->session->set_userdata('username',  $user->username);
			$this->session->set_userdata('user_level',  $user->level);
		    $this->session->set_userdata('name', $user->name);
		    $this->session->set_userdata('image', $user->image);
		    $this->session->set_userdata('language', $user->language);
		    $this->session->set_userdata('time_zone', $user->time_zone);
		    $this->session->set_userdata('geo_enabled', $user->geo_enabled);
		    $this->session->set_userdata('privacy', $user->privacy);

			$this->update_last_login($user->user_id);

			// Extend the users cookies if enabled
			if (config_item('user_extend_on_login'))
			{
				$this->remember_user($user->user_id);
			}

			return TRUE;
		}

		return FALSE;
	}
		
	function remember_user($user_id)
	{
		if (!$user_id) {
			return FALSE;
		}
		
		$salt = sha1(md5(microtime()));
		
		$this->db->update('users', array('remember_code' => $salt), array('user_id' => $user_id));
		
		if ($this->db->affected_rows() == 1) 
		{
			$user = $this->get_user($user_id);
			
			$identity = array('name'   => 'identity',
	                   		  'value'  => $user->{$this->identity_column},
	                   		  'expire' => config_item('user_expire'),
	               			 );
			set_cookie($identity); 
			
			$remember_code = array('name'   => 'remember_code',
	                   		  	   'value'  => $salt,
	                   		  	   'expire' => config_item('user_expire'),
	               			 	  );
			set_cookie($remember_code); 
			
			return TRUE;
		}
		
		return FALSE;
	}	
}