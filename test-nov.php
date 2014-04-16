<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title></title>
  </head>
  <body>
    <?php
    class node {
      function __construct($c_name, $c_cost, $c_f_cost) {
        $this->name = $c_name;
        $this->cost = $c_cost;
        $this->f_cost = $c_f_cost;
      }
      public $name="";
      public $cost="";
      public $f_cost="";
    }

    //return true if one string contains other
    function check_str($a, $b) {
      if (strpos($a,$b) === false) return false;
      if (strpos($a,$b) != 0) return false;
      return true;
    }

    //generate random name
    function generate_name($length){ 
      $code = ''; 
      $symbols = 'abcdefghijklmnopqrstuvwxyz'; 
      for( $i = 0; $i < (int)$length; $i++ ) { 
        $num = rand(1, strlen($symbols)); 
        $code .= substr( $symbols, $num-1, 1 ); 
      } 
      return $code; 
    }

    //sort nodes on cost (ASC)
      function cmp_cost($a, $b)
      {
        if ($a[2] == $b[2]) {
        return 0;
        }
        return ($a[2] < $b[2]) ? -1 : 1;
      }

    //sort odes on cost (DESC)
      function cmp_alph($a, $b)
      {
        return strcmp($a->name, $b->name);
      }

    echo 'Вычисление минимальной стоимости<br>';

    //create nodes
    $nodes_ar2 = array();
    array_push($nodes_ar2, new node('окно',250,250));
    array_push($nodes_ar2, new node('окно с форточкой',150,150));
    array_push($nodes_ar2, new node('дверь',350,350));
    array_push($nodes_ar2, new node('арка',200,200));
    array_push($nodes_ar2, new node('дверь стальная',400,400));
    array_push($nodes_ar2, new node('дверь стальная с замком',100,100));
    array_push($nodes_ar2, new node('арка кленовая',300,300));

    //alpha sort and display
    usort($nodes_ar2, "cmp_alph");
    foreach ($nodes_ar2 as $key => $val) {
      echo $val->name." || ".$val->cost." || ".$val->f_cost."<br>";
    }
    echo '<br>';

    //calc cost
    for ($i=sizeof($nodes_ar2)-1; $i>0; $i--) { 
      $j=0;
      do {
        $ch=check_str($nodes_ar2[$i]->name, $nodes_ar2[$j]->name);
        if ($ch===true) $nodes_ar2[$j]->f_cost=$nodes_ar2[$j]->f_cost-$nodes_ar2[$i]->cost/2;
        if ($nodes_ar2[$j]->f_cost<$nodes_ar2[$j]->cost/2) $nodes_ar2[$j]->f_cost=$nodes_ar2[$j]->cost/2;
        $j++;
      } while ($j<$i && $ch===false);
    }

    $sum=0;
    echo '<br>Итоговый список<br>';
    foreach ($nodes_ar2 as $key => $val) {
      echo $val->name." || ".$val->cost." || ".$val->f_cost."<br>";
      $sum+=$val->f_cost;
    }  
    echo 'Итоговая стоимость: '.$sum;

    echo '<br><br>Нахождение остовного дерева неориентированного графа<br><br>';
    //create initial list of nodes
    $nodes_ar = array();
    for ($i=0; $i<7; $i++) {
      $a = generate_name(4);
      array_push($nodes_ar, $a);
    }

    //create connections beyond nodes with random cost
    $connections = array();
    $i = sizeof($nodes_ar)-1;
    $a = $nodes_ar;
    do {
      $b = $a[$i];
      unset ($a[$i]);
      for ($j=0; $j<sizeof($a); $j++) {
        $b2 = $a[$j];
        array_push($connections, array($b, $b2, rand(1,10)));
      }
      $i--;
    } while ($i>0);

    $ar_1 = $nodes_ar; //initial list
    $ar_2 = array(); //done list
    $fin_ar = array(); //result list

    array_push($ar_2, $nodes_ar[sizeof($nodes_ar)-1]);
    unset($ar_1[sizeof($ar_1)-1]);

    do {
      $c_con1 = $connections; //initial list of connections
      $c_con2 = array(); //done list

      foreach ($c_con1 as $key => $val) {
        if ((in_array($val[0], $ar_1) && in_array($val[1], $ar_2)) || (in_array($val[1], $ar_1) && in_array($val[0], $ar_2))) {
        array_push($c_con2, $val);
        unset($c_con1[$key]);
        }
      }

      usort($c_con2, "cmp_cost");
      array_push($fin_ar, array_shift($c_con2));
      $name = end($fin_ar);
      if (in_array($name[0], $ar_2)) {
        array_push($ar_2, $name[1]);
        unset($ar_1[array_search($name[1], $ar_1)]);
      } else {
        array_push($ar_2, $name[0]);
        unset($ar_1[array_search($name[0], $ar_1)]);
      }
    } while (sizeof($ar_1)>0);

    echo 'Начальные связи<br>';
    //display initial connections
     foreach ($connections as $key => $val) {
      echo $val[0]." || ".$val[1]." || ".$val[2]."<br>";
    }
    echo "<br>Связи остовного дерева<br>";

    //display result 
     foreach ($fin_ar as $key => $val) {
      echo $val[0]." || ".$val[1]." || ".$val[2]."<br>";
    }
    echo '<br>';

    ?>
  </body>
</html>