<?php
	require_once '__system__/functions/config.php';

	use Model\{User, Storage, Department};

	Storage::getFromCookie();
	User::getFromCookie();
	User::checkAccountStatus();

	$REQUEST_URI = $_SERVER["REQUEST_URI"];
	
	$INITE = strpos($REQUEST_URI, '?');
	if ($INITE):
		$REQUEST_URI = substr($REQUEST_URI, 0, $INITE);
	endif;
	
	$REQUEST_URI_PASTA = substr($REQUEST_URI, 1);
	
	$URL = explode('/', $REQUEST_URI_PASTA);
	$URL[0] = ($URL[0] != '' ? $URL[0] : 'home');
	
	if (file_exists('__system__/' . $URL[0] . '.php')):
		if (isset($URL[1])):
			if ($URL[0] == "produto"):
				if (!isset($URL[2])):
					require '__system__/' . $URL[0] . '.php';
				else:
					require '__system__/404.php';
				endif;
			else:
				require '__system__/404.php';
			endif;
		else:
			require '__system__/' . $URL[0] . '.php';
		endif;
	elseif (is_dir('__system__/' . $URL[0])):
		if ($URL[0] == "admin-area"):
			if (isset($URL[1]) && file_exists('__system__/admin-area/' . $URL[1] . '.php')):
				if (isset($URL[2])):
					require '__system__/404.php';
				else:
					require '__system__/admin-area/' . $URL[1] . '.php';
				endif;
			elseif (isset($URL[1]) && is_dir('__system__/admin-area/' . $URL[1])):
				if (isset($URL[2]) && file_exists('__system__/admin-area/' . $URL[1] . '/' . $URL[2] . '.php')):
					if (isset($URL[3])):
						require '__system__/404.php';
					else:
						require '__system__/admin-area/' . $URL[1] . '/' . $URL[2] . '.php';
					endif;
				elseif (isset($URL[2]) && is_dir('__system__/admin-area/' . $URL[1] . '/' . $URL[2])):
					if (isset($URL[3]) && file_exists('__system__/admin-area/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3] . '.php')):
						if (isset($URL[4])):
							require '__system__/404.php';
						else:
							require '__system__/admin-area/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3] . '.php';
						endif;
					elseif (isset($URL[3]) && is_dir('__system__/admin-area/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3])):
						require '__system__/404.php';
					else:
						require '__system__/404.php';
					endif;
				else:
					require '__system__/404.php';
				endif;
			elseif (isset($URL[1]) && file_exists('__system__/admin-area/resource/' . $URL[1] . '.php')):
				if (isset($URL[2])):
					require '__system__/404.php';
				else:
					require '__system__/admin-area/resource/' . $URL[1] . '.php';
				endif;
			elseif (isset($URL[1]) && is_dir('__system__/admin-area/resource/' . $URL[1])):
				if (isset($URL[2]) && file_exists('__system__/admin-area/resource/' . $URL[1] . '/' . $URL[2] . '.php')):
					if (isset($URL[3])):
						require '__system__/404.php';
					else:
						require '__system__/admin-area/resource/' . $URL[1] . '/' . $URL[2] . '.php';
					endif;
				elseif (isset($URL[2]) && is_dir('__system__/admin-area/resource/' . $URL[1] . '/' . $URL[2])):
					if (isset($URL[3]) && file_exists('__system__/admin-area/resource/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3] . '.php')):
						if (isset($URL[4])):
							require '__system__/404.php';
						else:
							require '__system__/admin-area/resource/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3] . '.php';
						endif;
					elseif (isset($URL[3]) && is_dir('__system__/admin-area/resource/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3])):
						require '__system__/404.php';
					else:
						require '__system__/404.php';
					endif;
				else:
					require '__system__/404.php';
				endif;
			else:
				require '__system__/404.php';
			endif;
		else:
			if (isset($URL[1]) && file_exists('__system__/' . $URL[0] . '/' . $URL[1] . '.php')):
				if (isset($URL[2])):
					require '__system__/404.php';
				else:
					require '__system__/' . $URL[0] . '/' . $URL[1] . '.php';
				endif;
			elseif (isset($URL[1]) && is_dir('__system__/' . $URL[0] . '/' . $URL[1])):
				if (isset($URL[2]) && file_exists('__system__/' . $URL[0] . '/' . $URL[1] . '/' . $URL[2] . '.php')):
					if (isset($URL[3])):
						require '__system__/404.php';
					else:
						require '__system__/' . $URL[0] . '/' . $URL[1] . '/' . $URL[2] . '.php';
					endif;
				elseif (isset($URL[2]) && is_dir('__system__/' . $URL[0] . '/' . $URL[1] . '/' . $URL[2])):
					if (isset($URL[3]) && file_exists('__system__/' . $URL[0] . '/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3] . '.php')):
						if (isset($URL[4])):
							require '__system__/404.php';
						else:
							require '__system__/' . $URL[0] . '/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3] . '.php';
						endif;
					elseif (isset($URL[3]) && is_dir('__system__/' . $URL[0] . '/' . $URL[1] . '/' . $URL[2] . '/' . $URL[3])):
						require '__system__/404.php';
					else:
						require '__system__/404.php';
					endif;
				else:
					require '__system__/404.php';
				endif;
			else:
				require '__system__/404.php';
			endif;
		endif;
	else:
		$url_depart = Department::getFromUrl($URL[0]);

		if (count($url_depart) > 0):
			$_SESSION[Department::SESSION]['depart_id'] = $url_depart[0]["depart_id"];

			if (isset($URL[1])):
				$url_subcateg = Department::getFromUrlAndSub($url_depart[0]["depart_id"], $URL[1]);

				if (count($url_subcateg) > 0):
					$_SESSION[Department::SESSION]['subcateg_id'] = $url_subcateg[0]["subcateg_id"];

					if (isset($URL[2])):
						$url_categ = Department::getFromCategAndSub($url_subcateg[0]["subcateg_id"], $URL[2]);

						if (count($url_categ) > 0):
							if (isset($URL[3])):
								require '__system__/404.php';
							else:
								// $_SESSION['url3'] = $URL[2];
								$_SESSION[Department::SESSION]['categ_id'] = $url_categ[0]["categ_id"];
								require '__system__/departamento.php';
							endif;
						else:
							require '__system__/404.php';
						endif;
					else:
						// $_SESSION['url2'] = $URL[1];
						$_SESSION[Department::SESSION]['subcateg_id'] = $url_subcateg[0]["subcateg_id"];
						if (isset($_SESSION[Department::SESSION]['categ_id'])):
							unset($_SESSION[Department::SESSION]['categ_id']);
							// unset($_SESSION['url3']);
						endif;
						require '__system__/departamento.php';
					endif;
				else:
					require '__system__/404.php';
				endif;
			else:
				// $_SESSION['url1'] = $URL[0];
				$_SESSION[Department::SESSION]['depart_id'] = $url_depart[0]["depart_id"];
				if (isset($_SESSION[Department::SESSION]['subcateg_id']) && isset($_SESSION[Department::SESSION]['categ_id'])):
					unset($_SESSION[Department::SESSION]['subcateg_id']);
					unset($_SESSION[Department::SESSION]['categ_id']);
					// unset($_SESSION['url2']);
					// unset($_SESSION['url3']);
				else:
					if (isset($_SESSION[Department::SESSION]['subcateg_id'])):
						unset($_SESSION[Department::SESSION]['subcateg_id']);
						// unset($_SESSION['url2']);
					elseif (isset($_SESSION[Department::SESSION]['categ_id'])):
						unset($_SESSION[Department::SESSION]['categ_id']);
						// unset($_SESSION['url3']);
					endif;
				endif;
				require '__system__/departamento.php';
			endif;
		else:
			require '__system__/404.php';
		endif;
	endif;
