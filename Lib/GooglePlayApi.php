<?php

App::uses('AppDescriptionApi', 'AppDescription.Lib');


require_once(dirname(__FILE__).'/../../../Vendor/android-market-api/proto/protocolbuffers.inc.php');
require_once(dirname(__FILE__).'/../../../Vendor/android-market-api/proto/market.proto.php');
require_once(dirname(__FILE__).'/../../../Vendor/android-market-api/Market/MarketSession.php');



class GooglePlayApi extends AppDescriptionApi
{


    public function lookup($url)
    {
    	$rslt = $this->get_top();
        // TODO GooglePlayのURLから情報を取ってくる処理
        return [$rslt];
    }

	public function get_screenshot($appId = null) {
		App::import('Vendor', 'android-market-api/examples/local');

		$session = new MarketSession();
		$session->login(GOOGLE_EMAIL, GOOGLE_PASSWD);
		$session->setAndroidId(ANDROID_DEVICEID);

		$appId		= "com.google.android.apps.giant";
		$imageId	= 1;

		$gir = new GetImageRequest();
		$gir->setImageUsage(GetImageRequest_AppImageUsage::SCREENSHOT);
		$gir->setAppId($appId);
		$gir->setImageId($imageId);


		$reqGroup = new Request_RequestGroup();
		$reqGroup->setImageRequest($gir);
		$response = $session->execute($reqGroup);

		$groups = $response->getResponsegroupArray();
		#echo "<xmp>".print_r($groups, true)."</xmp>";
		foreach ($groups as $rg) {
			$imageResponse = $rg->getImageResponse();
			file_put_contents("../".$appId."_".$imageId.".png", $imageResponse->getImageData());
			var_dump($imageResponse->getImageData());
		}
		echo '<img src="../'.$appId.'_'.$imageId.'.png"; ?>">';
	}

	public function get_top($appId = null) {
		App::import('Vendor', 'android-market-api/examples/local');
		$session = new MarketSession();
		$session->login(GOOGLE_EMAIL, GOOGLE_PASSWD);
		$session->setAndroidId(ANDROID_DEVICEID);

		$ar = new AppsRequest();
		$ar->setOrderType(AppsRequest_OrderType::POPULAR);
		$ar->setStartIndex(0);
		$ar->setEntriesCount(5);
		$ar->setViewType(AppsRequest_ViewType::PAID);
		$ar->setCategoryId("ARCADE");

		$reqGroup = new Request_RequestGroup();
		$reqGroup->setAppsRequest($ar);

		$response = $session->execute($reqGroup);

		$groups = $response->getResponsegroupArray();
		foreach ($groups as $rg) {
			$appsResponse = $rg->getAppsResponse();
			$apps = $appsResponse->getAppArray();
			foreach ($apps as $app) {
				echo $app->getTitle()." (".$app->getId().")<br/>";
				//Get comments
				echo "<div style=\"padding-left:20px\">";
				$cr = new CommentsRequest();
				$cr->setAppId($app->getId());
				$cr->setEntriesCount(3);

				$reqGroup = new Request_RequestGroup();
				$reqGroup->setCommentsRequest($cr);

				$response = $session->execute($reqGroup);
				$groups	= $response->getResponsegroupArray();
				foreach ($groups as $rg) {
					$commentsResponse = $rg->getCommentsResponse();

					$comments = $commentsResponse->getCommentsArray();
					foreach ($comments as $comment) {
						echo "<strong>".$comment->getAuthorName()."</strong> [".str_repeat("*", $comment->getRating())."]<br/>";
						echo $comment->getText()."<br/><br/>";
					}
				}

				echo "</div>";
			}
		}
	}



}