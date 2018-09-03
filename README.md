# batch-ship-to-teamdrive
Example files for running a daily "batch" job to ship local files to a Google Team Drive via the Google API PHP Client Library.

With some additions (credentials and setting variables), this is production-ready code.
## Dependencies
Operating Environment:
* Designed for use in a LAMP environment, but could be used in any environment with PHP with minor modifications.
Requires:
* `Google API Client Library for PHP` (see: https://developers.google.com/api-client-library/php/start/installation)
* Credential files obtained/generated via https://console.developers.google.com
## The Execution:
* `batchShipFilesToTeamDrive.php`
    * Will identify all of the previous day's images (by file name) and upload them to the identified Team Drive.
* crontab
    * No crontab example is provided, but ideally would be used.
## A Real Use Case/Some History
This script was quickly spun up and used in a production environment to ship captured still images from an Internet-connected webcam to a Google Team Drive for sharing with the appropriate audience. See the [`capture-webcam-image`](https://github.com/zaskem/capture-webcam-image) repository for information on the file sourcing mechanism. The `batchShipFilesToTeamDrive.php` script was automatically triggered daily (overnight) via cron.
This process has been used on several projects, but has not typically been used in a single production setting for more than 60 concurrent days. As such, several nice-to-have features don't exist (local files are deleted manually at the completion of the project/run, for example) and there is very minimal error handling with the process (all error handling is managed outside of this script).
## Considerations
The first version of this script was using filesystem create/modified timestamps to determine "yesterday's" files. This is a superior method to identify when any of the given files were actually generated; however the plan was scrapped due to the environment(s) across which the files needed to travel (timestamps were not as reliable as the file date/time in each file name as the cradle-to-grave transport crossed multiple machines/services).
## Future Work
As there is _zero_ error handling with the data transport (i.e. handling if/when a Google API error occurs), it would be strongly encouraged to bolt in some error handling if this were to be run on a more frequent schedule than once or twice per day...or if run in a long-term setting (more than 30-60 days).
There is currently not a mechanism to remove the local files successfully transported to Team Drive. With proper error handling (see above), adding a quick call to remove the local files would be in order. 