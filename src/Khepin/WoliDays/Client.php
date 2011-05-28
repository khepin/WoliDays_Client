<?php
namespace Khepin\WoliDays;

/**
 * A client library for WoliDays. It relies on Goutte for the http client part.
 */
class Client {
    
    /**
     * The WoliDays server url
     * @var string
     */
    private $url;
    /**
     * The channel we are targetting on the WoliDays server
     * @var string
     */
    private $channel;
    /**
     * Client to issue the http requests
     * @var \Goutte\Client
     */
    private $goutte;

    /**
     * Constructor takes the server's url and the channel on which we are checking about
     * days being holidays or not
     * 
     * @param string $url
     * @param string $channel 
     */
    public function __construct($url, $channel = 'default') {
        if(substr($url, strlen($url) - 1) != '/'){
            $url .= '/';
        }
        $this->url = $url;
        $this->channel = $channel;
        $this->goutte = new \Goutte\Client();
    }
    
    /**
     * Returns the given date + X working days
     *
     * @param \DateTime $date
     * @param int $days
     * @return \DateTime 
     */
    public function addWorkingDays(\DateTime $date, $days){
        $url = $this->url.'add_work_days/'.$this->channel.'/'.$date->format('Y-m-d').'/'.$days;
        $this->goutte->request('GET', $url);
        $date_string = $this->goutte->getResponse()->getContent();
        return new \DateTime($date_string);
    }
    
    /**
     * Tells if the given date falls on a holiday or a working day.
     *
     * @param \DateTime $date
     * @return int
     */
    public function isHoliday(\DateTime $date){
        $url = $this->url.'is_holiday/'.$this->channel.'/'.$date->format('Y-m-d');
        $this->goutte->request('GET', $url);
        return (boolean) $this->goutte->getResponse()->getContent();
    }
    
    /**
     * Returns the number of workign days between the two given dates
     *
     * @param \DateTime $start
     * @param \DateTime $end
     * @return int
     */
    public function getWorkDaysBetween(\DateTime $start, \DateTime $end){
        $url = $this->url.'work_days_between/'.$this->channel.'/'.
                $start->format('Y-m-d').'/'.$end->format('Y-m-d');
        $this->goutte->request('GET', $url);
        return $this->goutte->getResponse()->getContent();
    }
}