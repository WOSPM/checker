<?php
namespace WOSPM\Checker;

/**
 * Doc comment for abstract class Metric
 */
class Metric
{
    private $code    = "WOSPM0001";
    private $title   = "Nothing is working";
    private $message = "It seems nothing is working.";
    private $type    = MetricType::INFO;

    /**
     * Main check function
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        // do some check
        $this->success();
    }

    /**
     * Succes function
     * 
     * @return array
     */
    public function success()
    {
        return $this->result(true);
    }

    /**
     * Fail function
     * 
     * @return array
     */
    public function fail()
    {
        return $this->result();
    }

    /**
     * Result function that returns the result of the check with metric info
     * 
     * @param boolean $status The result of the check
     *
     * @return array
     */
    private function result($status = false)
    {
        return array(
            "code"    => $this->code,
            "title"   => $this->title,
            "message" => $this->message,
            "type"    => $this->type,
            "status"  => $status
        );
    }
}