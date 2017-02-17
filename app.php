<?php

/** Here will be autoload */
require_once ('src/Init.php');

use CodexEditor\Entry\Structure;

class Application {

    /**
     * @var JSON data from POST request
     */
    private $json;

    /**
     * @var Clean Data for save
     */
    private $outputData;

    public function __construct($data)
    {
        $this->json = $data;
    }

    /**
     * Validation
     */
    public function run()
    {
        $entry = new Structure($this->json);
        $this->outputData = $entry->getEntryData();

    }

    /**
     * @return Clean data
     */
    public function getData()
    {
        return $this->outputData;
    }

}

/**
 * get POST data
 */
$data = isset($_POST['entry']) ? $_POST['entry'] : '';

if ($data) {

    $app = new Application($data);

    $app->run();
    $cleanData = $app->getData();

    var_dump($cleanData);
}