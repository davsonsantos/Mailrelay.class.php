<?php 

/**
* 
*/

class Mailrelay{

	var $URL = 'http://davtech1.ip-zone.com/ccm/admin/api/version/2/&type=json';
	var $Authentication; //Codigo de Autenticação
	var $Result; //Retorna as informações das classes
	var $postData; // array com os dados de envio de email

	function __construct(){
		$this->getAuthentication();
	}

	/*
	*<b>sendEmail</b> - Função de envio de emails
	*/
	function sendEmail(array $Data){
		$rcpt = array(array('name' => $Data['name'], 'email' => $Data['email']));
		$this->postData = array(
		    'function' => 'sendMail',
		    'apiKey' => $this->Authentication,
		    'subject' => $Data['subject'],
		    'html' => $Data['message'],
		    'mailboxFromId' => 1,
		    'mailboxReplyId' => 1,
		    'mailboxReportId' => 1,
		    'packageId' => 6,
		    'emails' => $rcpt
		);
		$this->exeCURL();
		return $this->Result;
	}

	/*
	*<b>package</b> - Retorna os dados de quantas email e cadastros tem liberados na plataforma
	*/
	function package(){
		$this->postData = array(
		    'function' => 'getPackages',
		    'apiKey' => $this->Authentication,
		);
 		
 	 	$this->exeCURL();
		return $this->Result;
	}

	/**********************************************
	* START GROUP
	* Funções de Gerenciamento de Grupos de Emails
	**********************************************/

	/*
	*<b>getGroup</b> - Lista os grupos criados
	*/
	function getGroup(){
		$this->postData = array(
		    'function' => 'getGroups',
		    'apiKey' => $this->Authentication,
		    'offset' => 0
		);
		$this->exeCURL();
		return $this->Result;
	}

	/*
	*<b>addGroup</b> - Adicona novos grupos
	*/
	function addGroup(array $Data){ 
		$this->postData = array(
		    'function' => 'addGroup',
		    'apiKey' => $this->Authentication,
		    'name' => $Data['groupName'],
		    'description' => $Data['groupDescription'],
		    'position' => 1,
		    'enable' => (isset($Data['enable'])  ? false : true),
		    'visible' => (isset($Data['visible']) ? false : true),
		);
  
		$this->exeCURL();
	 	return $this->Result;
	}

	/*
	*<b>updateGroup<b> - Edita os dados de um grupo
	*/
	function updateGroup(array $Data){
		$this->postData = array(
		    'function' => 'updateGroup',
		    'apiKey' => $this->Authentication,
		    'id' => $Data['groupId'],
		    'name' => $Data['groupName'],
		    'description' => $Data['groupDescription'],
		    'position' => 1,
		    'enable' => (isset($Data['enable'])  ? false : true),
		    'visible' => (isset($Data['visible']) ? false : true),
		);

		$this->exeCURL();
	 	return $this->Result;
	}

	/*
	*<b>deleteGroup</b> - Exclui um grupo
	*/
	function deleteGroup($Data){
		$this->postData = array(
		    'function' => 'deleteGroup',
		    'apiKey' => $this->Authentication,
		    'id' => $Data['groupId']
		);

		$this->exeCURL();
	 	return $this->Result;
	}
	/***********************************************
	* END GROUP
	***********************************************/

	/**********************************************
	* START SUBSCRIPT
	* Funções de Gerenciamento de Subscripts
	***********************************************/

	/*
	*<b>getSubscripts</b - Consulta dos Subscripts
	*/
	function getSubscripts($Data = null){
		$this->getAuthentication();
		$this->postData = array(
		    'function' => 'getSubscribers',
		    'apiKey' => $this->Authentication,
		    'offset' => 0,
		    'count' => null
		);
		$this->exeCURL();
		return $this->Result;
	}

	/*
	*<b>addSubscript</b>Addiciona Assinaturas
	*/
	function addSubscript($Data){ 
		$this->getAuthentication();
 
		$this->postData = array(
		    'function' => 'addSubscriber',
		    'apiKey' => $this->Authentication,
		    'email' => $Data['user_email'],
		    'name' => $Data['user_name'],
		    'groups' => $Data['group']
		);
		$this->exeCURL();
		return $this->Result;
	}

	/*
	*<b>updateSubscript</b>Atualiza Assinaturas
	*/
	function updateSubscript($Data){
		$this->getAuthentication();

		$this->postData = array(
		    'function' => 'updateSubscriber',
			'apiKey' => $this->Authentication,
			'id' => $Data['id'],
			'email' => $Data['user_email'],
			'name' => $Data['user_name'],
			'groups' => array($Data['groups'])
		);

		$this->exeCURL();
		return $this->Result;
	}
	
	/*
	*<b>disableSubscribers</b>Ativa e Desativa Assinaturas
	*/
	function statusSubscribers($Data){
		$this->getAuthentication();
		$this->postData = array(
		    'function' => 'updateSubscribers',
			'apiKey' => $this->Authentication,
			'ids' => array($Data['ids']),
			'activated' => $Data['status']
	);
	}

	/*
	*<b>deleteSubscribers</b>Exclui Assinaturas
	*/
	function deleteSubscribers($Data){
		$this->getAuthentication();

		$this->postData = array(
		    'function' => 'deleteSubscriber',
		    'apiKey' => $this->Authentication,
		    'email' => $Data['email']
		);

		$this->exeCURL();
		return $this->Result;
	}

	/***********************************************
	* END SUBSCRIPT
	***********************************************/

	private function exeCURL(){
		$curl = curl_init($this->URL);		
 
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->postData));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		 
		$json = curl_exec($curl);
		if ($json === false) {
		    $this->Result = 'A solicitação falhou com erro: '. curl_error($curl);
		}
		 
		$result = json_decode($json);
		if ($result->status == 0) {
		    $this->Result = 'Status inválido. Erro: '. $result->error;
		}

		if(isset($result->data)){
			$this->Result = $result->data;
		}

		return $this->Result;
			
		
	}

	
	private function getAuthentication (){
		$curl = curl_init($this->URL);
 
		$postData = array(
		    'function' => 'doAuthentication',
		    'username' => 'davtech1',
		    'password' => '487a8b08',
		);
 
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
		$json = curl_exec($curl);
		if ($json === false) {
		    die('Request failed with error: '. curl_error($curl));
		}
		 
		$result = json_decode($json);
		if ($result->status == 0) {
		    die('Bad status returned. Error: '. $result->error);
		}
		 
		$this->Authentication = $result->data;
	}
}


?>
