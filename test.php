<?php

    Class Answer
        {
            private $averages = array();
            private $pixel_nums = array();
            private $n;
            private $k;

            function __construct($is_test = 0)
            {
                if($is_test == 1){
                    $this->n = 6;
                    $this->k = 3;
                    $this->pixel_nums = array(
                        '1 2 3 4 5 6',
                        '1 2 3 4 5 6',
                        '1 2 3 4 5 6',
                        '4 5 6 1 2 3',
                        '4 5 6 1 2 3',
                        '4 5 6 1 2 3',
                    );
                    //print_r($pixel_nums);
                }
            }

        public function getAnswer(){
                $result = $this->getAverage($this->pixel_nums);
                print_r($result);
        }

        public function getAverage($pixel_nums){
            //結果用配列の初期化
            for($i = 0; $i < $this->n/$this->k; $i++){
                for($j = 0; $j < $this->n/$this->k; $j++){
                    $result[$i][$j] = 0;
                }
            }

            foreach($pixel_nums as $nums){
                $nums = str_replace(" ", "", $nums);
                $nums_split[] = str_split($nums, $this->k);
            }


            $sums = array();
            foreach($nums_split as $nums_row){
                for($i = 0; $i < $this->n/$this->k; $i++){
                    $sums[] += $nums_row[$i];
                }
            }





            return $sums;
        }

        }

        $answer = new Answer(1);
        echo $answer->getAnswer();
?>
