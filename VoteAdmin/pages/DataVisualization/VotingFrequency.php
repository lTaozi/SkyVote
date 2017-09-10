<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/4/16 0016
 * Time: 17:31
 */
include_once "lib/config.php";
include_once "lib/db.class.php";
$min = isset($_GET['min']) ? $_GET['min'] : 60;
$id  = isset($_GET['id']) ? $_GET['id'] : "";
if($id  == ""){
    exit('{
	labels : [],
	datasets : [
		{
            label: "Electronics",
            fillColor: "rgba(210, 214, 222, 1)",
            strokeColor: "rgba(210, 214, 222, 1)",
            pointColor: "rgba(210, 214, 222, 1)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data : []
		}
	]
}');
}
$interval = $min * 60;
$sql = "select votetime from record where towho =  '$id' ORDER BY  `record`.`votetime`  ASC ";
$rs = $dbClass->query($sql);
$voteTimes = array();
$i = 0;
while ($r = $dbClass->getone($rs)){
    $voteTime = strtotime($r['votetime']);
    $voteTimes[] = $voteTime;

}
$start_time = $voteTimes[0];
$times = array();
$i = 0;
$data[0] = 0;
$labels[] = date("Y-m-d H:i:s",$start_time);
foreach ($voteTimes as $value){
    if($value <= $start_time + $interval ){
        $data[$i] ++;
    }else{
        $i ++;
        $start_time = $value;
        $data[$i]  = 1;
        $labels[] = date("Y-m-d H:i:s",$start_time);
    }

}
$json['labels'] = $labels;
$json['datasets'][0]['label']='Electronics';
$json['datasets'][0]['pointHighlightFill']='#fff';
$json['datasets'][0]['pointHighlightStroke']='rgba(220,220,220,1)';
$json['datasets'][0]['fillColor']='rgba(151,187,205,0.5)';
$json['datasets'][0]['strokeColor']='rgba(151,187,205,1)';
$json['datasets'][0]['pointColor']='rgba(151,187,205,1)';
$json['datasets'][0]['pointStrokeColor']='#fff';
$json['datasets'][0]['data']=$data;
echo json_encode($json,JSON_UNESCAPED_UNICODE);
