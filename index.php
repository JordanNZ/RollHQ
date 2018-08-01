<?php
$service_url = 'https://api.rollhq.com/graphql';
$curl = curl_init($service_url);

$query = <<<'JSON'
query Projects {
  project(ProjectStatus: "in-progress", ProjectTitle: "SUB", offset: 0) {
    ProjectId
    ProjectTitle
    CompanyId
    ProjectLeadSourceId
    Company {
      CompanyName
    }
    Employees {
      EmployeeId
      EmployeeName
    }
    ProjectDescription
    ProjectStatus
    ProjectSubStatusId
    ProjectSubStatus {
      Id
      Name
    }
    ProjectValue
    DueDate
    Time {
      TimeId
      TimeInSeconds
      TimeText
      TimeStatus
      LoggedForDate
      LastUpdated
    }
  }
}
JSON;

$curl_post_data = array("query" => $query);
$data_string =  json_encode($curl_post_data);
$auth = "Authorization: Bearer token";
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string),
    $auth
    )
);

$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$response = json_decode($curl_response);


foreach ($response->data as $key => $jsons) { 
    foreach($jsons as $key => $value) {
        echo '<b>Project Type: </b>' . $value->ProjectTitle . '<br>';
        echo '<b>Company: </b>' . $value->Company[0]->CompanyName . '<br>';
        echo '<b> Time Status: </b>'  . $value->Time[0]->TimeStatus . '<br>';

        $start_date = $date = date("d/m/Y", strtotime(" -1 month"));
        $end_date = $date = date("d/m/Y", strtotime(" today"));
       echo $start_date; echo $end_date;
        for($i = 0; $i < count($value->Time); $i++) {
         echo '<b>Total Time: </b>' . $value->Time[$i]->TimeInSeconds . '<br><br><br>';
        }

     }
}

echo '<pre>'; var_dump($response); echo '</pre>';
