<?php
/*
 * 
CREATE TABLE `must_reads` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `content` text NOT NULL,
  `time` datetime NOT NULL
)
 * 
 */

class MustRead extends AppModel {
	var $name = "MustRead";
	
	var $validate = array(
		
	);
}
?>
