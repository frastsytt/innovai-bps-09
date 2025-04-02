<?php
class ApiTester {
    public $ip;
    private $res;

    public function __construct($ip) {
        $this->ip = $ip;
    }

    public function __invoke() {
        $this->res = system("ping " . $this->ip);
    }

    public function __destruct() {
        system("echo '" . $this->res . "' >> /var/log/api_ping.log");
    }
}
class User {
    private $username;
    private $email;
    private $enable;

    public function __construct($username, $email, $enable) {
        $this->username = $username;
        $this->email = $email;
        $this->enable = $enable;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isEnable() {
        return $this->enable === 1;
    }
}
function gen_uid(){
    return md5(uniqid('', true));
}
