<?php
	$host = "localhost";
	$username = "root";
	$password = "";
	$conn = mysqli_connect($host, $username, $password);

	$sql = "CREATE DATABASE IF NOT EXISTS hostel_db";
	mysqli_query($conn, $sql);
	mysqli_close($conn);

	require_once 'database_connection.php';
		
	// table to store user data
	$sql = 'CREATE TABLE IF NOT EXISTS `users` (
			  `user_id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_email` varchar(30) NOT NULL,
			  `user_fname` varchar(30) NOT NULL,
			  `user_lname` varchar(30) NOT NULL,
			  `user_Gender` varchar(10) NOT NULL,
		      `user_p_no` varchar(30) NOT NULL,
			  `user_password` varchar(255) NOT NULL,
			  `user_account_type` TINYINT NOT NULL,
			  PRIMARY KEY (`user_id`)
			);';

	if(!mysqli_query($conn, $sql))
		die("Error description: " . mysqli_error($conn));

	// table to store user pending users data
	$sql = 'CREATE TABLE IF NOT EXISTS `pending_users` (
		`user_id` int(11) NOT NULL AUTO_INCREMENT,
		`user_email` varchar(30) NOT NULL,
		`user_fname` varchar(30) NOT NULL,
		`user_lname` varchar(30) NOT NULL,
		`user_Gender` varchar(10) NOT NULL,
		`user_p_no` varchar(30) NOT NULL,
		`user_password` varchar(255) NOT NULL,
		`user_account_type` TINYINT NOT NULL,
		PRIMARY KEY (`user_id` ,`user_email`)
	  );';

	if(!mysqli_query($conn, $sql))
		die("Error description: " . mysqli_error($conn));

	//table to store hostel data
	$sql = 'CREATE TABLE IF NOT EXISTS `hostels` (
			  `hostel_id` int(11) NOT NULL AUTO_INCREMENT,
			  `hostel_name` varchar(60) NOT NULL,
			  `hostel_city` varchar(30) NOT NULL,
			  `hostel_address` varchar(100) NOT NULL,
			  `hostel_rooms` int(3) NOT NULL,
			  `hostel_extras` varchar(255) NOT NULL,
			  `hostel_owner` int(11) NOT NULL,
			  `hostel_rating` float NOT NULL,
			  `hostel_img` TEXT NOT NULL,
			  	PRIMARY KEY (`hostel_id`),
			  	FOREIGN KEY (`hostel_owner`) REFERENCES `users` (`user_id`)
			);';
	if(!mysqli_query($conn, $sql))
		die("Error description: " . mysqli_error($conn));

	//table to store hostel pending data
	$sql = 'CREATE TABLE IF NOT EXISTS `pending_hostels` (
		`hostel_id` int(11) NOT NULL AUTO_INCREMENT,
		`hostel_name` varchar(60) NOT NULL,
		`hostel_city` varchar(30) NOT NULL,
		`hostel_address` varchar(100) NOT NULL,
		`hostel_rooms` int(3) NOT NULL,
		`hostel_extras` varchar(255) NOT NULL,
		`hostel_owner` int(11) NOT NULL,
		`hostel_rating` float NOT NULL,
		`hostel_img` TEXT NOT NULL,
			PRIMARY KEY (`hostel_id`),
			FOREIGN KEY (`hostel_owner`) REFERENCES `users` (`user_id`)
	  );';
	if(!mysqli_query($conn, $sql))
		die("Error description: " . mysqli_error($conn));

	// table to store the pictures of the hostels
	$sql = 'CREATE TABLE IF NOT EXISTS `hostels_images` (
			  `hostel_id` int(11) DEFAULT 0,
			  `pending_hostel_id` int(11) DEFAULT 0,
			  `hostel_pic` TEXT NOT NULL
			);';
	if(!mysqli_query($conn, $sql))
		die("Error description: " . mysqli_error($conn));
	
	//adding table to check whether a database is initializing 1st time
	$sql = 'CREATE TABLE IF NOT EXISTS `database_check` (
		`database_check_flag` int(1)
	  );';

	if(!mysqli_query($conn, $sql))
		die("Error description: " . mysqli_error($conn));

	////////////////////////////////////// adding dummy data ///////////////////////////////////////
	
	include_once("functions.php");
	// adding dummy data to tables wheneevr one's open this site on first time
	if(isTableEmpty($conn, 'database_check'))
	{
		$sql = "INSERT INTO `database_check`
			( database_check_flag )
			VALUES
			('1');";

		if(!mysqli_query($conn, $sql)) {
			die("Error description: " . mysqli_error($conn));
		}

		// adding dummy data to users table
		$sql = "INSERT INTO `users`
			( user_email, user_password, user_account_type )
			VALUES
			('user1@test.com', '123456', 1), 
			('user2@test.com', '123456', 1), 
			('user3@test.com', '123456', 1),
			('admin1@test.com', '123456', 2),
			('admin2@test.com', '123456', 2),
			('owner@test.com', '123456', 3);";

		if(!mysqli_query($conn, $sql)) {
			die("Error description: " . mysqli_error($conn));
		}

		// adding dummy data to pending users table
		$sql = "INSERT INTO `pending_users`
			( user_email, user_password, user_account_type )
			VALUES
			('admin3@test.com', '123456', 2),
			('admin4@test.com', '123456', 2),
			('admin5@test.com', '123456', 2);";

		if(!mysqli_query($conn, $sql)) {
			die("Error description: " . mysqli_error($conn));
		}  

		// adding dummy data to hostel table
		$sql = "INSERT INTO `hostels`
			( hostel_id, hostel_name, hostel_city, hostel_address, hostel_rooms, hostel_extras, hostel_owner, hostel_img )
			VALUES
			('1', 'hostel1', 'Peshawar', 'Murree town 53', '12', 'Double bed', '4', 'src/hostel_images/1_image1.jpg'), 
			('2', 'hostel2', 'Lahore', 'Murree town 345', '9', 'heater, Double bed', '4', 'src/hostel_images/2_image1.jpg'), 
			('3', 'hostel3', 'Quetta', 'Murree town 123', '3', 'AC, heater, Double bed', '4', 'src/hostel_images/3_image1.jpg');";

		$result = mysqli_query($conn, $sql);
		if(!$result) {
			die("Error description: " . mysqli_error($conn));
		} 

		// adding dummy data to pending hostel table
		$sql = "INSERT INTO `pending_hostels`
			( hostel_id, hostel_name, hostel_city, hostel_address, hostel_rooms, hostel_extras, hostel_owner, hostel_img )
			VALUES
			('1', 'hostel4', 'Lahore', 'Johar town 12', '12', 'AC, fridge, Heater', '4', 'src/hostel_images/4_image1.jpg'), 
			('2', 'hostel5', 'Lahore', 'Johar town 92', '9', 'AC, fridge, Microwave', '4', 'src/hostel_images/5_image1.jpg'), 
			('3', 'hostel6', 'Karachi', 'Johar town 102', '3', 'AC, fridge', '4', 'src/hostel_images/6_image1.jpg');";

		if(!mysqli_query($conn, $sql)) {
			die("Error description: " . mysqli_error($conn));
		} 

		// adding dummy data to hostels_images table
		$sql = "INSERT INTO `hostels_images`
			( pending_hostel_id, hostel_id, hostel_pic)
			VALUES
			('0', '1', 'src/hostel_images/1_image1.jpg'), 
			('0', '1', 'src/hostel_images/1_image2.jpg'), 
			('0', '1', 'src/hostel_images/1_image3.jpg'), 
			('0', '2', 'src/hostel_images/2_image1.jpg'), 
			('0', '2', 'src/hostel_images/2_image2.jpg'), 
			('0', '2', 'src/hostel_images/2_image3.jpg'), 
			('0', '3', 'src/hostel_images/3_image1.jpg'), 
			('0', '3', 'src/hostel_images/3_image2.jpg'), 
			('0', '3', 'src/hostel_images/3_image3.jpg'), 
			('1', '0', 'src/hostel_images/4_image1.jpg'), 
			('2', '0', 'src/hostel_images/5_image1.jpg'), 
			('3', '0', 'src/hostel_images/6_image1.jpg');";

			if(!mysqli_query($conn, $sql)) {
				die("Error description: " . mysqli_error($conn));
			} 
	}

	mysqli_close($conn);
?>