<?php
namespace GC\WebSocket;



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




