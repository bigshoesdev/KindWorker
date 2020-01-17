<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
return [ 
		"job" => [ 
				"add-custom-job" => [ 
						"Job[description]" => "hgjk",
						"Job[title]" => "Test string",
						"Job[category_id]" => "12",
						"Job[sub_category_id]" => "2",
						"Job[estimated_price]" => "45",
						"Job[first_file]" => "",
						"Job[second_file]" => "",
						"Job[third_file]" => "",
						"Job[address]" => "Test string",
						"Job[budget_type]" => "1",
						"Job[latitude]" => "54",
						"Job[longitude]" => "74",
						"Job[type_id]" => "1",
						"Job[job_type]" => "1"  // 0 is for booked worker // 1 is for custom jobs
				],
				
				"update?job_id=" => [ 
						"Job[description]" => "hgjk" 
				
				],
				"book-worker" => [ 
						"Job[description]" => "hgjk",
						"Job[worker_id]" => "1",
						"Job[slot_id]" => "1",
						"Job[title]" => "Test string",
						"Job[category_id]" => "12",
						"Job[sub_category_id]" => "2",
						"Job[estimated_price]" => "45",
						"Job[address]" => "Test string",
						"Job[budget_type]" => "1",
						"Job[latitude]" => "54",
						"Job[longitude]" => "74",
						"Job[type_id]" => "1",
						"Job[date]" => "1",
						"Job[gig_quantity]" => "1",
						"Job[job_type]" => "1"  // 0 is for booked worker // 1 is for custom jobs
				],
				"showjob" => [ 
						"Job[description]" => "hgjk",
						"Job[job_title]" => "Test string",
						"Job[first_file]" => "" 
				
				],
				"list" => [ 
						"Job[type_id]" => "" 
				
				],
				"search-job?lat=72.12547&long=36.9878844" => [ 
						"Job[type_id]" => "0",
						"Job[job_title]" => "sujd",
						"Job[category]" => "hdhhd" 
				],
				"update?id={id}" => [ ],
				"index" => [ ],
				"get?id={}" => [ ],
				"delete?id={}" => [ ] 
		] 
];
?>
