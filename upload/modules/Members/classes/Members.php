<?php
/*
 *	Made by Partydragen
 *  https://github.com/partydragen/Members/
 *  https://partydragen.com/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 */

class Members {
    /*
     *  Check for Module updates
     *  Returns JSON object with information about any updates
     */
    public static function updateCheck($current_version = null) {
        $queries = new Queries();

        // Check for updates
        if (!$current_version) {
            $current_version = $queries->getWhere('settings', ['name', '=', 'nameless_version']);
            $current_version = $current_version[0]->value;
        }

        $uid = $queries->getWhere('settings', ['name', '=', 'unique_id']);
        $uid = $uid[0]->value;

		$enabled_modules = Module::getModules();
		foreach ($enabled_modules as $enabled_item) {
			if ($enabled_item->getName() == 'Members') {
				$module = $enabled_item;
				break;
			}
		}

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://api.partydragen.com/stats.php?uid=' . $uid . '&version=' . $current_version . '&module=Members&module_version='.$module->getVersion() . '&domain='. Util::getSelfURL());

        $update_check = curl_exec($ch);
        curl_close($ch);

		$info = json_decode($update_check);
		if (isset($info->message)) {
			die($info->message);
		}

        return $update_check;
    }
}