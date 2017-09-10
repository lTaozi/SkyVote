<?php
include_once '../lib/DataBase.class.php';

$db = new DataBase("furonxuezi", false, true);

    $i = $_GET['id'];
    $type = $_GET['type'];
    $start = $_GET['start'];
    $end = $_GET['end'];

    if ($type == 1) {
        $sql = "SELECT * FROM `record_list` WHERE toCandidate = $i AND voteTime > '$start' AND voteTime < '$end'";
        $db->query($sql);
        $result = $db->fetchArray(MYSQL_ASSOC);

        $sql = "SELECT * FROM `candidate` WHERE id =".$i;
        $db->query($sql);
        $candidate = $db->fetchArray(MYSQL_ASSOC);
        $name = $candidate[0]['name'];
        $vote = $candidate[0]['votes'];
        $rentou = $candidate[0]['persons'];
        $zb = round((count($result)/$vote)*100,4).'%';
        echo "分析对象：".$name."      总票数：".$vote."     人头数：".$rentou."     此段票数：".count($result)."     此段票数占比：".$zb;
    }

    if ($type == 2) {
        $sql = "SELECT * FROM `record_list` WHERE toCandidate = ".$i." LIMIT ".($start-1).",".($end-$start+1);
        $db->query($sql);
        $result = $db->fetchArray(MYSQL_ASSOC);

        $sql = "SELECT * FROM `candidate` WHERE id =".$i;
        $db->query($sql);
        $candidate = $db->fetchArray(MYSQL_ASSOC);
        $name = $candidate[0]['name'];
        $vote = $candidate[0]['votes'];
        $rentou = $candidate[0]['persons'];
        $zb = round((count($result)/$vote)*100,4).'%';
        echo "分析对象：".$name."      总票数：".$vote."     人头数：".$rentou."     此段票数：".count($result)."     此段票数占比：".$zb;
    }
    
    $ip_list = array();
    $city_list = array();
    foreach ($result as $key => $value) {
        if (!in_array($value['ip'], $ip_list)) {
            $ip_list[] = $value['ip'];
        }
        $city_list[] = $value['position'];
    }
    $ip_num = count($ip_list);
    $city_array = array_count_values($city_list);
    $city_num = count($city_array);
    echo "     此段ip数：$ip_num";
    echo "     此段城市数：$city_num <br/><br/>";

    
    echo "来自各个城市的票数：<br/><br/>";
    foreach ($city_array as $key => $value) {
        $zhanbi = round(($value/$vote)*100,4).'%';
        echo "<b>".$key."</b>:".$value."票     占比：$zhanbi<br/><br/>";
    }


    if ($_GET['echoDetail']) {
        $max_num = 0;
        foreach ($ip_list as $key => $value) {
            $sql = "SELECT * FROM `record_list` WHERE toCandidate = $i AND ip = "."'$value'";
            $db->query($sql);
            $result = $db->fetchArray(MYSQL_ASSOC);
            echo "----------------------------------------";
            echo "ip:".$value."<br/>";
            foreach ($result as $key => $value) {
                    echo "时间:".$value['voteTime']."<br/>";
                    echo "地点:".$value['position']."<br/><br/>";
            }
            $num = count($result);
            if ($num > $max_num) {
                $max_num = $num;
            }
        }

        echo "同一IP最大投票数：".$max_num;
    }
    

