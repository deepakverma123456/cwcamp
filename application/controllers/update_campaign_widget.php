 <?php
 //error_reporting(E_ALL);
$servername = "localhost";
$username = "python";
$password = "ZxRBhcALnjQUH3aW";
$dbname = "cartwire_cn";
//http://192.200.12.168/china/update_campaign_widget.php?w_id=28
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//check connection
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

/*
	* Function to insert campaign records from widget details to widget_source_view and update widget_details.
	* parameters: $con, $product_id			
*/

function updateCampaign($conn) {

    $sql = "SELECT * FROM campaign_widget_details WHERE widget_id = ".$_GET['w_id']." AND widget_source_view_id = 0 ";
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
	$i=0;
	$j=0;
        while($row = $result->fetch_assoc()) {
	++$i;	
                $sql = "INSERT INTO `campaign_widget_source_view` (
                            `widget_id`,
                            `source_url`,
                            `ip`,
                            `view`,
                            `country_name`,
                            `country_code`,
                            `region`,
                            `city`,
                            `latitude`,
                            `longitude`,
                            `device_version`,
                            `bin_type`,
                            `created_date`,
                            `modified_date`,
                            `status`,
                            `report_status`
                        )
                    VALUES (
                            '".$row['widget_id']."',
                            '".$row['target_url']."',
                            '".$row['ip']."',
                            '1',
                            'null',
                            'null',
                            'null',
                            'null',
                            'null',
                            'null',
                            '".$row['device_version']."',
                            '1',
                            '".$row['created_by']."',
                            '".$row['created_by']."',
                            '".$row['status']."',
                            '".$row['report_status']."'                           
                            );";
                
                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    $sql = "UPDATE `campaign_widget_details` SET `widget_source_view_id` = '".$last_id."' WHERE `widget_details`.`id` = '".$row['id']."'";
                    $conn->query($sql);
		    ++$j;
                }
		else {
			Echo "Error: " . $sql . "<br>" . $conn->error;
		}
            }
        }
        else {
            echo "0 results";
            }
	echo $i." View Table Record Upadated<br/>";
	echo $j." Detail Table Record Upadated";
}

updateCampaign($conn); // call the function

$conn->close();

?> 
