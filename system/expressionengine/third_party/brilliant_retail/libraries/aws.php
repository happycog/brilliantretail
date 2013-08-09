<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//composer auto load
include_once 'aws/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Iterator;
use Guzzle\Http\EntityBody;

class aws {   

    public $AWSAccessKeyId;
    public $AWSSecretKey;

    private $aws;
    private $s3Client;
    private $cfClient;
    private $cfIdentity;
    
	function listBuckets()
	{
		// Create the S3 client
			$this->_set_login();
		
			$result = $this->s3Client->listBuckets();
		
		$list = array();
		foreach ($result['Buckets'] as $bucket) {
		    $list[] = $bucket['Name'];
		}
		return $list;
	}
	
	function listFiles($name)
	{
		// Create the S3 client
			$this->_set_login();
		
		    $iterator = $this->s3Client->getIterator('ListObjects', array(
		    	'Bucket' => $name 
			));

			foreach ($iterator as $object) {
			   $list[] = array('filename' => $object['Key']);
			}
			return $list;
	}
	
	function createUrl ($bucket,$file,$length=10)
	{
		$this->_set_login();
		$arg = array(
						'ResponseContentDisposition'	=> 'attachement',
						'ResponseContentType' 			=> "application/octet-stream"
					);
		
		$min = $length*1;

		return $this->s3Client->getObjectUrl($bucket,$file,'+'.$min.' minutes',$arg);
	}
	
	
	private function _set_login(){
        $this->s3Client = S3Client::factory(array(
									    'key'    => $this->AWSAccessKeyId,
									    'secret' => $this->AWSSecretKey
									));
	}
}
