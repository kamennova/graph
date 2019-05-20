<?php

class Graph
{
    public $v_num;
    public $edges;
    public $adj_matrix;
    public $distance_matrix;

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
        $this->matrix_output($this->adj_matrix);

        $this->depth_first_round();
        $this->breadth_first_round();

        $this->build_distance_matrix();

        $this->dijkstra_round(0);
        $this->floyd_warshall_round();
    }

    function build_adj_matrix()
    {
        $this->adj_matrix = $this->matrix_init(0);

        foreach ($this->edges as $edge) {
            $u = $edge[0];
            $v = $edge[1];

            $this->adj_matrix[$u][$v] = 1;
            $this->adj_matrix[$v][$u] = 1;
        }
    }

    function matrix_output($matrix)
    {
        echo "  | ";
        for ($i = 0; $i < $this->v_num; $i++) {
            echo str_pad($i, 2, ' ', STR_PAD_LEFT) . ' ';
        }

        echo "\n" . str_pad('', 3 * $this->v_num + 3, '-', STR_PAD_LEFT);

        echo "\n";

        for ($i = 0; $i < $this->v_num; $i++) {
            echo str_pad($i, 2, ' ', STR_PAD_LEFT) . '| ';

            foreach ($matrix[$i] as $item) {
                echo str_pad($item, 2, ' ', STR_PAD_LEFT) . ' ';
            }

            echo "\n";
        }
    }

    function depth_first_round()
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
        }

        echo "\n";
    }

    function breadth_first_round()
    {
        $start = 0;
        $marked = [];
        $waiting = [];
        $queue = new SplQueue();
        $queue->enqueue($start);

        while (!$queue->isEmpty()) {
            $v = $queue->dequeue();

            echo $v . ' -> ';
            $marked[$v] = 1;
            unset($waiting[$v]);

            for ($a = 0; $a < $this->v_num; $a++) {
                $u = $this->adj_matrix[$v][$a];

                if ($u === 1 && !isset($marked[$a]) && !isset($waiting[$a])) {
                    $queue->enqueue($a);
                    $waiting[$a] = 1;
                }
            }
        }

        echo "\n";
    }

    function matrix_init($default)
    {
        $matrix = [];

        for ($i = 0; $i < $this->v_num; $i++) {
            $matrix[$i] = [];

            for ($a = 0; $a < $this->v_num; $a++) {
                $matrix[$i][$a] = $default;
            }
        }

        return $matrix;
    }

    function build_distance_matrix()
    {
        $this->distance_matrix = $this->matrix_init(INF);

        foreach ($this->edges as $edge) {
            $u = $edge[0];
            $v = $edge[1];
            $dist = $edge[2];

            $this->distance_matrix[$u][$v] = $dist;
            $this->distance_matrix[$v][$u] = $dist;
        }
    }

    function dijkstra_round($start)
    {
        /**
         * @var array $ways - array of ways to start lengths
         */
        $ways = [];

        for ($i = 0; $i < $this->v_num; $i++) {
            $ways[$i] = INF;
        }

        $ways[$start] = 0;

        $marked = [];

        $queue = new SplQueue();
        $queue->enqueue($start);

        while (!$queue->isEmpty()) {
            $v = $queue->dequeue();
            $marked[$v] = 1;

            for ($a = 0; $a < $this->v_num; $a++) {
                $u = $this->adj_matrix[$v][$a];

                if ($u === 1 && !isset ($marked[$a])) {
                    $dist = $this->distance_matrix[$v][$a] // distance from current to next vertex
                        + $ways[$v]; // distance from start to current vertex

                    if ($dist < $ways[$a]) {
                        $ways[$a] = $dist;
                    }

                    $queue->enqueue($a);
                }
            }
        }

        return $ways;
    }

    function floyd_warshall_round()
    {
        $ways = $this->distance_matrix;

        for ($k = 0; $k < $this->v_num; $k++) {
            for ($i = 0; $i < $this->v_num; $i++) {
                for ($j = 0; $j < $this->v_num; $j++) {
                    if($i == $j) continue;

                    $dist = min($ways[$i][$j], $ways[$i][$k] + $ways[$k][$j]);
                    $ways[$i][$j] = $dist;
                    $ways[$j][$i] = $dist;
                }
            }
        }

        return $ways;
    }
}