<?php

echo 'Version PHP courante : ' . phpversion();

session_start();
// added in v4.0.0
require_once 'autoload.php';
// erreur ?
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication( '426192597572805','27be0db02ca076e690eb5a047c3e7d56' );

// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('http://qrv.fr/fb/fbconfig.php' );
try  {
    $session = $helper->getSessionFromRedirect();
  }
  catch( FacebookRequestException $ex )   {
    // When Facebook returns an error
  }
  catch( Exception $ex )  {
  // When validation fails or other local issues
  }

// see if we have a session
if ( isset( $session ) )
{
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
  $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	$fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	$femail = $graphObject->getProperty('email');    // To Get Facebook email ID
	
    /* ---- Session Variables -----*/
	  $_SESSION['FBID'] = $fbid;           
    $_SESSION['FULLNAME'] = $fbfullname;
	  $_SESSION['EMAIL'] =  $femail;
    /* ---- header location after session ----*/
    header("Location: index.php");
}
else
{
  $loginUrl = $helper->getLoginUrl();
  header("Location: ".$loginUrl);
}

?>