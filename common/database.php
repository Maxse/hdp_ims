<?php
	// Global variables;

	$imsDBConn = null;
?>
<?php
	// Functions

	function log_page($conn, $ip, $page, $action) {
		if (!empty($conn)) {
			mysql_query(
				'INSERT INTO log_page ' .
				'(ip, page, action, datetime) VALUES ' .
					"(INET_ATON('$ip'), " .
					"'$page', " .
					"'$action', " .
					'NOW());',
				$conn);
		}
	}

	function log_ims($conn, $ip, $user_id, $action) {
		if (!empty($conn)) {
			mysql_query(
				'INSERT INTO log_ims ' .
				'(ip, user_id, action, datetime) VALUES ' .
					"(INET_ATON('$ip'), " .
					"$user_id, " .
					"'$action', " .
					'NOW());',
				$conn);
		}
	}

	function check_user_id_ims($conn, $ip, $duration, $page, $uid) {
		if (empty($conn)) {
			return 0;
		}

		$user_id          = 0;
		$lastAccess       = 0;
		$dbResult =
			mysql_query(
				"SELECT * FROM log_ims" .
					" WHERE ip=INET_ATON('$ip')" .
					" AND user_id=$uid" .
					" ORDER BY datetime DESC" .
					" LIMIT 0, 1;",
				$conn);
		if ($row = mysql_fetch_array($dbResult)) {
			$user_id          = $row['user_id'];
			$lastAccess       = strtotime($row['datetime']);
		}
		log_page($conn, $ip, $page, "User: $user_id -- $ip");
		if ($user_id > 0) {
			$now = time();
			$elapsedSeconds = $now - $lastAccess;
			if ($elapsedSeconds > $duration) {
				log_page($conn, $ip, $page, "User: $user_id -- $elapsedSeconds > $duration seconds elapsed");
				// Invalidate this user
				$user_id = 0;
			}
			else {
				log_ims($conn, $ip, $user_id, "Login renewed ($page)");
			}
		}
		return $user_id;
	}

	function check_user_access_ims($conn, $user_id, $item_id) {

		// Constant
		$status_enabled = 1;

		if (!empty($conn)) {
			$dbResult =
				mysql_query(
					"SELECT * FROM access a, item i" .
						" WHERE a.item_id=i.id" .
							" AND a.user_id=$user_id" .
							" AND a.status=$status_enabled" .
							" AND i.id=$item_id" .
							" AND i.status=$status_enabled" .
						" LIMIT 0, 1;",
					$conn);
			if ($row = mysql_fetch_array($dbResult)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			// If database is not enabled, all permissions are considered forbidden
			return false;
		}
	}

	function history_ims($conn, $ip, $user_id, $link, $query) {
		if (!empty($conn)) {
			mysql_query(
				'INSERT INTO history_ims ' .
				'(ip, user_id, link, query, datetime) VALUES ' .
					"(INET_ATON('$ip'), " .
					"$user_id, " .
					"'$link', " .
					"'$query', " .
					'NOW());',
				$conn);
		}
	}
?>