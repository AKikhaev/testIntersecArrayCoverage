<?php


class IntervalSummator
{
    private $data = [];

    /**
     * IntervalSummator constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData():array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data):void
    {
        $this->data = $data;
    }

    /**
     * return sum result
     */
    public function calc():int {
        if (count($this->data) === 0) return 0;

        $sorted = $this->data;
        usort($sorted,fn($a,$b) => $a[0] == $b[0] ? 0 : ($a[0] < $b[0] ? -1 : 1));

        [$from, $to] = $sorted[0];
        $len = $to - $from;

        $count = count($sorted); // performance
        for($i=1;$i<$count;$i++) {
            [$_from, $_to] = $sorted[$i];


            if ($_from<$to && $_to>$to) {
                // Продлеваем
                $len += $_to-$to;
                $to = $_to;
            } elseif ($_from>$to) {
                //Отдельный сегмент
                $len += $_to - $_from;
                //$from = $_from;
                $to = $_to;
            }
        }
        return $len;
    }

}

try {
    if (isset($_POST['array'])) {
        $data = json_decode($_POST['array'],true,9,JSON_THROW_ON_ERROR);
        $summator = new IntervalSummator($data); //[ [1,4],  [7, 10],  [3, 5] ]
        $summ = $summator->calc();
        echo $summ;
    } else throw new Exception('Вам требуется передать массив чисел в формате json!');
}
catch (Throwable $e) {
    echo $e->getMessage();
}