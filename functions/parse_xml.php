<?php
	function parseXML(SimpleXMLElement $xml, int $level = 0):string {
		$result = '';
		$spaces = str_repeat("&nbsp;", $level * 4);
		foreach ($xml->children() as $key => $value) {
			$currentPath = "$key";
			if ($value->count() > 0) {
				$micro_hash = substr(md5(uniqid(rand(), true)), 0, 8);
				$result .= "<div class='xml-node'><input type='checkbox' id='$micro_hash' class='checkbox-toggle'><label for='$micro_hash'>$key ({$value->count()})</label><div class='children'>";
				$result .= parseXML($value, $level + 1);
				$result .= "</div></div>";
			} else {
				$nodeValue = trim((string)$value);
				$result .= "<div class='xml-node_nochildren'>$spaces$key: $nodeValue</div>";
			}
		}
		return $result;
	}

	function parseXMLWithSearch(SimpleXMLElement $xml, string $searchTerm, int $level = 0, string $path = "", bool $hasParent = false):string {
		$result = '';
		$spaces = str_repeat("&nbsp;", $level * 4);
		foreach ($xml->children() as $key => $value) {
			$currentPath = "$path/$key";
			$nodeValue = trim((string)$value);
			if (stripos($key, $searchTerm) !== false || stripos($currentPath, $searchTerm) !== false || stripos($nodeValue, $searchTerm) !== false) {
				if ($value->count() > 0) {
					$micro_hash = substr(md5(uniqid(rand(), true)), 0, 8);
					$result .= ($hasParent) ? "<div class='xml-node'><input type='checkbox' id='$micro_hash' class='checkbox-toggle'><label for='$micro_hash'>$key ({$value->count()})</label><div class='children'>" : "<div class='xml-node'><input type='checkbox' id='$micro_hash' class='checkbox-toggle'><label for='$micro_hash'>$currentPath ({$value->count()})</label><div class='children'>";
					$result .= parseXMLWithSearch($value, $searchTerm, $level + 1, $currentPath, true);
					$result .= "</div></div>";
				}
				else {
					$result .= ($hasParent) ? "<div class='xml-node_nochildren'>$key: $nodeValue</div>" : "<div class='xml-node_nochildren'>$currentPath: $nodeValue</div>";
				}
			} elseif ($value->count() > 0) {
				$result .= parseXMLWithSearch($value, $searchTerm, $level + 1, $currentPath);
			}
		}
		return $result;
	}

	$xmlContent = isset($_POST['xml_content']) ? $_POST['xml_content'] : '';

	if (!empty($xmlContent)) {
		$xml = simplexml_load_string($xmlContent) or die('Error loading XML content');
		$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
		if (!empty($searchTerm)) {
			echo parseXMLWithSearch($xml, $searchTerm, 0);
		} else {
			echo parseXML($xml, 0);
		}
	} else {
		echo "No XML content received";
	}
?>
