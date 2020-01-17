<?php /**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
 

return [
	"worker-skill" => [
		"add" => [
			"WorkerSkill[category_id]" => "1",
			"WorkerSkill[description]" => "Test string",
			"WorkerSkill[hourly_rate]" => "Test string",
			"WorkerSkill[experience]" => "Test string",
			"WorkerSkill[state_id]" => "0",
			"WorkerSkill[type_id]" => "0",
			"WorkerSkill[created_on]" => "2017-07-01 18:50:15",
			"WorkerSkill[updated_on]" => "2017-07-01 18:50:15",
			"WorkerSkill[created_by_id]" => "1",
			"WorkerSkill[day]" => "Test string",
			],
		"update?id={id}"=>  [
			"WorkerSkill[category_id]" => "1",
			"WorkerSkill[description]" => "Test string",
			"WorkerSkill[hourly_rate]" => "Test string",
			"WorkerSkill[experience]" => "Test string",
			"WorkerSkill[state_id]" => "0",
			"WorkerSkill[type_id]" => "0",
			"WorkerSkill[created_on]" => "2017-07-01 18:50:15",
			"WorkerSkill[updated_on]" => "2017-07-01 18:50:15",
			"WorkerSkill[created_by_id]" => "1",
			"WorkerSkill[day]" => "Test string",
			],
			
			"search-worker" => [
					"WorkerSkill[search]" => "Test string",
			],
			
			"search-worker-local" => [
					"WorkerSkill[search]" => "Test string",
					"User[last_search]" => "Test string",
					"User[last_zip]" => "12345",
					/* "City[name]" => "Miami", */
			],
			"worker-detail" => [
					"WorkerSkill[id]" => "44",
					"WorkerSkill[sub_category_id]" => "132",
					/* "City[name]" => "Miami", */
			],
		"index" => [],
		"get?id={}" => [],
		"delete?id={}" => []
	]
];
?>
