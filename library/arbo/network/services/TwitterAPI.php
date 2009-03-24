<?php
Rhaco::import("network.http.ServiceRestAPIBase");
/**
 * get Twitter Account => http://twitter.com/
 * 
 * @author Takuya Sato
 * @license New BSD License
 * @copyright Copyright 2007- rhaco.org All rights reserved.
 */
class TwitterAPI extends ServiceRestAPIBase{
	var $base_url = "http://twitter.com";
	var $format = "xml";
	
	var $login = "";
	var $user_id = "";
	var $password = "";
	var $url = "";
	var $cmd1 = "";
	var $cmd2 = "";
	var $target = "";
	
	function TwitterAPI($login = "", $password = ""){
		parent::ServiceRestAPIBase();
		$this->login = $login;
		$this->password = $password;
	}
	
	function status_public_timeline($since_id = "", $iscache=false) {
		$this->createCmd("public_timeline", "statuses");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array("since_id"=>$since_id),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_friends_timeline($user=null, $since="", $iscache=false) {
		$this->authRequire();
		if (is_null($user)) {
			$this->createCmd("friends_timeline", "statuses");
		} else {
			$this->createCmd($user, "statuses", "friends_timeline");
		}
		
		$options = $this->buildOptions(array("since"=>$since));
		
		$pTag = new SimpleTag();
		$pTag->set($this->get($options,$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_user_timeline($user=null, $iscache=false) {
		if (is_null($user)) {
			$this->authRequire();
			$this->createCmd("user_timeline", "statuses");
		} else {
			$this->createCmd($user, "statuses", "user_timeline");
		}
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_show($user, $iscache=false) {
		$this->createCmd($user, "statuses", "show");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_update($status) {
		$this->authRequire();
		
		$this->createCmd("update", "statuses");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array("status"=>$status));
		$pTag->set($this->post($options));
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $pTag;
	}
	
	function status_friends($user, $iscache=false) {
		$this->authRequire();
		
		$this->createCmd($user, "statuses", "friends");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseUserResults($pTag);
	}
	
	function status_followers($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("followers", "statuses");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseUserResults($pTag);
	}
	
	function status_featured($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("featured", "statuses");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "users");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseUserResults($pTag);
	}
	
	function user_show($user, $iscache=false) {
		$this->authRequire();
		
		$this->createCmd($user, "users", "show");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "user");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseUserResult($pTag);
	}
	
	function direct_messages($since="", $iscache=false) {
/*
		$this->authRequire();
		
		$this->createCmd("direct_messages");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array("since"=>$since));
		$pTag->set($this->get($options,$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
*/
	}
	
	function direct_messages_new($user, $text, $iscache=false) {
/*
		$this->authRequire();

		$this->createCmd("new", "direct_messages");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array("user"=>$user, "text"=>$text));
		$pTag->set($this->post($options));
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $pTag;
*/
	}
	
	function authRequire() {
		$this->setBasicAuthorization($this->login, $this->password);
	}
	
	function parseUserResult(&$pTag) {
		$user = array("id"=>$pTag->getInValue("id"),
					"name"=>$pTag->getInValue("name"),
					"screen_name"=>$pTag->getInValue("screen_name"),
					"location"=>$pTag->getInValue("location"),
					"description"=>$pTag->getInValue("description"),
					"profile_image_url"=>$pTag->getInValue("profile_image_url"),
					"url"=>$pTag->getInValue("url"),
					"protected"=>$pTag->getInValue("protected"),
					);
		return $user;
	}
	
	function parseUserResults(&$pTag) {
		$list = array();
		foreach ($pTag->getIn("user") as $user) {
			$list[] = $this->parseUserResult($user);
		}
		return $list;
	}
	
	function parseStatusResults(&$pTag) {
		$list = array();
		foreach($pTag->getIn("status") as $status){
			$userTag = $status->getIn("user");
			$user = array();
			if (count($userTag) > 0) {
				$userTag = $userTag[0];
				$user = $this->parseUserResult($userTag);
			}
			$list[] = array("created_at"=>$status->getInValue("created_at"),
						"id"=>$status->getInValue("id"),
						"text"=>$status->getInValue("text"),
						"user"=>$user,
						);
		}
		return $list;
	}
	
	function getErrorMsg(&$pTag) {
		if (empty($pTag->value)) {
			$plain = $pTag->plain;
			if (!empty($plain)) {
				return $plain;
			}
		}
		
		return false;
	}
	
	function createCmd($target, $cmd1 = "", $cmd2 = "") {
		$this->url = $this->base_url;
		$this->cmd1 = $cmd1;
		$this->cmd2 = $cmd2;
		$this->target = $target . "." . $this->format;
	}
	
	function buildUrl($hash = array()) {
		$cmd = "";
		if (!empty($this->cmd1)) {
			$cmd .= "/" . $this->cmd1;
		}
		if (!empty($this->cmd2)) {
			$cmd .= "/" . $this->cmd2;
		}
		if (!empty($this->target)) {
			$cmd .= "/" . $this->target;
		}
		return parent::buildUrl($hash,array(),$cmd);
	}
	
	function buildOptions($options = array()) {
		$new_options = array();
		foreach($options as $key=>$value) {
			if (!is_null($value) && !empty($value)) {
				$new_options[$key] = $value;
			}
		}
		return $new_options;
	}
}
?>