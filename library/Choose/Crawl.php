<?php
class Choose_Crawl
{
    protected $_argv;

    protected $_httpOptions = array(
        'timeout' => 60,
        'maxredirects' => 0,
        'useragent' => 'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0',
    );

    public function __construct($argv = null)
    {
        $this->_argv = $argv;
    }

    public function getResponse($url, $options = null)
    {
        if (!$options) {
            $options = $this->_httpOptions;
        }

        $error = 0;
        do {
            try {
                $client = new Zend_Http_Client($url, $options);
                $response = $client->request();

                if (200 != $response->getStatus()) {
                    throw new Choose_Exception('http status: ' . $response->getStatus());
                }

                return $response;

            } catch(Exception $e) {
                $error++;
            }
            
        } while($error < 3);

        throw $e;
    }
}