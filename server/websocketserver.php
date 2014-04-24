<?php

use Wrench\Application\Application;
//use Wrench\Application\NamedApplication;



class Message
{

	protected $type;
	protected $data;

	public function __construct($type, $data, $channel=0) {
		$this->channel=$channel;
		$this->type=$type;
		$this->data=$data;
	}
	
	
	public function __toString() {
		
		return json_encode(
			array(
				'type'=>$this->type,
				'data'=>$this->data,
				'time'=>time()
			)
		);
	}
}



class WebsocketServer extends Application
{

protected $lastTimestamp = null;
    /**
     * @see Wrench\Application.Application::onData()
     */

	protected $clients=array();

	protected $callbacks=array();


	public function on($eventName, $callBack) {
	
		$this->callbacks[$eventName]=$callBack;
	}
	
	
	
	protected function executeCallback($name, $parameters) {
		if(isset($this->callbacks[$name])) {
			return call_user_func_array(
				array($this->callbacks[$name], '__invoke'),
				$parameters
			);
		}
	}


    public function onConnect($client) {
        $this->clients[$client->getId()]=$client;
		$client->send((string) new Message('connect', array(
			'id'=>$client->getId()
		)));
		
		return $this->executeCallback('connect', array($this, $client));
		
    }


    public function onDisconnect($client) {
		unset($this->clients[$client->getId()]);
		return $this->executeCallback('disconnect', array($this, $client));
    }
	
	
	
	


    public function onData($data, $client) {
		$this->handleMessage($data, $client);
    }
	
	
    public function onUpdate() {
		return $this->executeCallback('update', array($this, null));
    }
	


	public function handleMessage($data, $client) {
		$data=json_decode($data, true);
		
		print_r($data);
		
		
		$type=$data['type'];
		
		if(is_callable($this->callbacks[$type])) {
		
			array_unshift($data['data'], $client);
			array_unshift($data['data'], $this);

			return call_user_func_array(array(
				$this->callbacks[$type], '__invoke'
			), $data['data']);
		}
		else {
			print_r($data);
		}
	}
	


	public function broadCast($message) {
	
		if(count($this->clients)) {
			foreach($this->clients as $client) {	
				$client->send((string) $message);
			}
		}	
	}

	
	
	
	
	
	
}