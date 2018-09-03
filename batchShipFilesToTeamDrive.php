<?php
require_once '/google/vendor/autoload.php';

putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/google/api/credentials.json');

if (!function_exists('curl_reset'))
{
    function curl_reset(&$ch)
    {
        $ch = curl_init();
    }
}
/**
* Returns an authorized API client.
* @return Google_Client the authorized client object
*/

$client = new Google_Client();
$client->setApplicationName('YourAppNameHere');
$client->setDeveloperKey('YourDeveloperKeyHere');
$client->useApplicationDefaultCredentials();
$client->addScope(Google_Service_Drive::DRIVE);
$service = new Google_Service_Drive($client);

// Destination Team Drive ID and Local File Path
$teamDriveId = 'YourTeamDriveIDHere';
$localFilePath = '/path/to/localfiles';

// Find Yesterday's Files (files are named "sc-<mm>-<dd>-<yy>-<hh>-<ss>.jpg")
$yesterday = date("m-d-y", strtotime("yesterday"));
$yesterdaysImages = glob("$localFilePath/sc-$yesterday*.jpg");

// Now Handle File Upload to Team Drive
$yesterdayUploads = array();

foreach ($yesterdaysImages as $yesterdayImage) {
    $content = file_get_contents($yesterdayImage);
    $teamDriveFilename = pathinfo($yesterdayImage)['basename'];
    // Generate Metadata
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $teamDriveFilename,
        'supportsTeamDrives' => true,
        'teamDriveId' => $teamDriveId,
        'parents' => array($teamDriveId)));
    // Create File
    $file = $service->files->create($fileMetadata, array(
        'data' => $content,
        'mimeType' => 'image/jpeg',
        'uploadType' => 'multipart',
        'supportsTeamDrives' => true,
        'fields' => 'id'));
    // Add result to Uploads List (debugging)
    $yesterdayUploads[] = $file->id;
}