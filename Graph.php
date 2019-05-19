<?php

class Graph
{
    public $v_num;
    public $edges;
    public $adj_matrix;

    /**
     * Graph constructor.
     * @param array $edges - [u, v, len]
     * @param integer $v_num - number of vertexes
     */
    function __construct($edges, $v_num)
    {
        $this->v_num = $v_num;
        $this->edges = $edges;
        $this->build_adj_matrix();
        $this->matrix_output();

        $this->depth_first_round();
        $this->breadth_first_round();
    }

    function build_adj_matrix()
    {
        $this->adj_matrix = [];

        for ($i = 0; $i < $this->v_num; $i++) {
            $this->adj_matrix[$i] = [];

            for ($a = 0; $a < $this->v_num; $a++) {
                $this->adj_matrix[$i][$a] = 0;
            }
        }

        foreach ($this->edges as $edge) {
            $u = $edge[0];
            $v = $edge[1];

            $this->adj_matrix[$u][$v] = 1;
            $this->adj_matrix[$v][$u] = 1;
        }
    }

    function matrix_output()
    {
        echo "  | ";
        for ($i = 0; $i < $this->v_num; $i++) {
            echo str_pad($i, 2, ' ', STR_PAD_LEFT) . ' ';
        }

        echo "\n" . str_pad('', 3 * $this->v_num + 3, '-', STR_PAD_LEFT);

        echo "\n";

        for ($i = 0; $i < $this->v_num; $i++) {
            echo str_pad($i, 2, ' ', STR_PAD_LEFT) . '| ';

            foreach ($this->adj_matrix[$i] as $item) {
                echo str_pad($item, 2, ' ', STR_PAD_LEFT) . ' ';
            }

            echo "\n";
        }
    }

    function breadth_first_round()
    {
        $start = 0;
        $marked = [];
        $waiting = [];
        $stack = new SplStack();

        $stack->push($start);

        while (!$stack->isEmpty()) {
            $v = $stack->pop();

            echo $v . ' -> ';
            $marked[$v] = 1;

            for ($a = 0; $a < $this->v_num; $a++) {
                $u = $this->adj_matrix[$v][$a];

                if ($u === 1 && !isset($marked[$a]) && !isset($waiting[$a])) {
                    $stack->push($a);
                    $waiting[$a] = 1;
                }
            }

            var_dump($stack);
        }

        echo "\n";
    }

    function depth_first_round()
    {
        $start = 0;
        $marked = [];
        $waiting = [];
        $queue = new SplQueue();
        $queue->push($start);

        while (!$queue->isEmpty()) {
            $v = $queue->pop();

            echo $v . ' -> ';
            $marked[$v] = 1;
            unset($waiting[$v]);

            for ($a = 0; $a < $this->v_num; $a++) {
                $u = $this->adj_matrix[$v][$a];

                if ($u === 1 && !isset($marked[$a]) && !isset($waiting[$a])) {
                    $queue->push($a);
                    $waiting[$a] = 1;
                }
            }
        }

        echo "\n";
    }
}