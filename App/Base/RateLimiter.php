<?php 
namespace App\Base;
class RateLimiter {
    private $limit;
    private $interval;
    private $storage;

    public function __construct($limit = 100, $interval = 3600) {
        $this->limit = $limit;
        $this->interval = $interval;
        $this->storage = get_option('rate_limiter_storage', array());
        $this->cleanup();
    }

    private function cleanup() {
        $now = time();
        foreach ($this->storage as $key => $time) {
            if ($now - $time > $this->interval) {
                unset($this->storage[$key]);
            }
        }
        update_option('rate_limiter_storage', $this->storage);
    }

    public function isLimited($userIP) {
        $count = isset($this->storage[$userIP]) ? $this->storage[$userIP]['count'] : 0;
        return $count >= $this->limit;
    }

    public function hit($userIP) {
        if (!isset($this->storage[$userIP])) {
            $this->storage[$userIP] = ['count' => 0, 'timestamp' => time()];
        }

        $this->storage[$userIP]['count']++;
        update_option('rate_limiter_storage', $this->storage);
    }
}


// Example usage:
// $limiter = new RateLimiter(100, 3600); --//100 requests per hour
// $userIP = $_SERVER['REMOTE_ADDR']; --//Get user IP address

// if ($limiter->isLimited($userIP)) {
//     wp_die('You have exceeded the rate limit for requests. Please try again later.');
// } else {
//     $limiter->hit($userIP);
//      --//Proceed with the request
// }