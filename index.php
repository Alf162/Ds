<html>
<body>
<?php
	class ComputeTime {
		const v = 150000; // половина скорости света в ваккуме
		const tobr = 100; // время обработки информационного запроса сервером
		const npw = 100; // количество пакетов в сообщении на запись
		const npr = 100; // количество пакетов в сообщении на чтение
		private $n, $nw, $nr, $sr;
		private $nwc = array();
		private $nrc = array();
		
		public function __construct($filename){
			self::readInpFile($filename);
		}

		private function readInpFile($file){
			$in = parse_ini_file($file);
			$this->sr = explode(",", $in["Sr"]);
			$this->n = $in["N"];
			$this->nw = $in["Nw"];
			$this->nr = $in["Nr"];
			if($this->n != count($this->sr)){
				echo "Ошибка во входном файле данных!!!";
				exit;
			}
			if(!isset($in["Nwc"]) OR !isset($in["Nrc"])) {
				for($i=0; $i<$this->nw; $i++){
					$ch = mt_rand(0,$this->n-1);
					if(!in_array($ch, $this->nwc))
						$this->nwc[$i] = $ch;
					else
						$i--;
				}
				for($i=0; $i<$this->nr; $i++){
					$ch = mt_rand(0,$this->n-1);
					if(!in_array($ch, $this->nrc))
						$this->nrc[$i] = $ch;
					else
						$i--;
				}
			}
			else {
				$this->nwc = explode(",", $in["Nwc"]);
				$this->nrc = explode(",", $in["Nrc"]);
			}
		}

		public function computeWrite(){
			$maxTime = 0;
			for($i=0; $i<$this->nw; $i++){
				$tz = $this->sr[$this->nwc[$i]] * 1000/self::v;
				$tzobr = self::npw*$this->sr[$this->nwc[$i]]*1000/self::v;
				$time = 3*$tz + $tzobr + self::tobr;
				if($time>$maxTime)
					$maxTime = $time;
			}
			return $maxTime;
		}

		public function computeRead(){
			$maxTime = 0;
			for($i=0; $i<$this->nr; $i++){
				$tz = $this->sr[$this->nrc[$i]] * 1000/self::v;
				$tzobr = self::npr*$this->sr[$this->nrc[$i]]*1000/self::v;
				$time = 3*$tz + $tzobr + self::tobr;
				if($time>$maxTime)
					$maxTime = $time;
			}
			return $maxTime;
		}

	}
	$tr = 0;
	$tw = 0;
	for($i=0; $i<=20000; $i++){
		$f = new ComputeTime("input.txt");
		$tr += $f->computeRead();
		$tw += $f->computeWrite();
	}
	echo "Tr = ".round(($tr/20001),2)."<br>Tw = ".round(($tw/20001),2);
?>
</body>
</html>