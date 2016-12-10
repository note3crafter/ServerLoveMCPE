<?php
namespace ServerLoveMCPE;

class BabyVillager extends Child{

	const NETWORK_ID = 15;
	
	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.8;
	
		
	public function getName() {
		return "BabyVillager";
	}
		
	public function getSpeed() {
		return 2.0;
	}
}
