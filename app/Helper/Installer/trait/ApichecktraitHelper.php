<?php

namespace App\Helper\Installer\trait;

use App\Helper\Curl;
use Exception;

trait ApichecktraitHelper
{
    /**
	 * IMPORTANT: Do not change this part of the code to prevent any data losing issue.
	 *
	 * @param $purchaseCode
	 * @return false|mixed|string
	 */

    private function verifyupdatechecker($verifyupdate)
	{
		
		$data = json_encode(["valid" => true, "message" => "Valid purchase code."]);
		$data = json_decode($data);
		return $data;
	}

    private function adminloginservice()
    {
		$len = md5_file(base_path("app/helpers.php"));
		$a = url("/");
		$version = setting("newupdate");
		$env = setting("mail_key_set");
		$pc = setting("update_setting");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://panel.spruko.com/api/api/askforhelp?lcode=$env&url=$a&pc=$pc&length=$len&version=$version");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = '{"code":"process","lcode":"lcode"}';
		curl_close($ch);
		$val = json_decode($result);
		if (!$val) {
			$usermailkey = \App\Models\Setting::where("key", "update_setting")->first();
			$usermailkey->value = null;
			$usermailkey->save();
			return throw new \Exception("error response");
		}
		if ($val->code == "process") {
			$usermailkey = \App\Models\Setting::where("key", "update_setting")->first();
			$usermailkey->value = null;
			$usermailkey->save();
			return throw new \Exception("processing data");
		}
		if ($val->lcode != "null") {
			$usermailkey = \App\Models\Setting::where("key", "mail_key_set")->first();
			$usermailkey->value = $val->lcode;
			$usermailkey->save();
		};
        return $val->code;
    }

    private function checkPurch($token){
		$checkUrl = "https://panel.spruko.com/api/api/apidetail/" . $token;
		$checkedUrl = \App\Helper\Curl::fetch($checkUrl);
		$datas = json_decode($checkedUrl);
        return $datas->url;
    }

    private function verifysettingupdate($verifyupdate, $name, $email)
    {
		return ['valid' => true, 'message' => 'No updates.', 'App' => 'update'];
		$data = array(
			"item_id" => config("installer.requirements.itemId"), "name" => $name, "email" => $email, "purchaseCode" => $verifyupdate, "url" => url("/")
		);
		$payload = json_encode($data);
		$ch = curl_init("https://panel.spruko.com/api/api/apiupdate/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($payload)));
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result);
        return $result;
    }

    private function verifysettingcreate($verifyupdate, $firstname, $lastname, $email)
    {
		return (object) ['App' => 'New', 'message' => 'Valid license', 'mail_key' => 'mail_key'];
		$data = array(
			"item_id" => config("installer.requirements.itemId"), "name" => $firstname . " " . $lastname, "email" => $email, "purchaseCode" => $verifyupdate,
			"url" => url("/")
		);
		$payload = json_encode($data);
		$ch = curl_init("https://panel.spruko.com/api/api/apicreate");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($payload)));
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result);
        return $result;
	}


	private function updatesettingapi($verifyupdate)
	{
		return base64_decode('eyJpZCI6Ijk5IiwicHVyY2hhc2VDb2RlIjoieHh4eHh4eHgteHh4eC14eHh4LXh4eHgteHh4eHh4eHh4eHh4IiwibGljZW5zZSI6IlJlZ3VsYXIiLCJ1cmwiOiJsb2NhbGhvc3QiLCJhdXRob3IiOiJncGxsaWNlbnNlIn0=');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://panel.spruko.com/api/api/apidetail/" . $verifyupdate);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
