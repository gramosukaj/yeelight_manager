<?php

class Yeelight
    {
        private $ip_address;
        private $port = 55443;
        private $name;
        private $power;
        private $color;
        private $brightness;
        private $socket;
        private $mode = "None";

        const NVT_LF  = "\n";
        const NVT_CR  = "\r";

        // MODE
        private const FULL_WHITE = "Full power white";
        private const SIMPLE_COLOR = "Color";
        private const FLOW = "Flow rgb";

        function __construct($ip, $name) 
        {
            $this->set_ipaddress($ip);
            $this->set_name($name);

            // set & create socket for Yeelight Object
            $this->new_socket();

            // Set current power status
            $this->refresh_data();
        }

        public function new_socket()
        {
            $this->socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
            if($this->socket == false)
            {
                echo ('Socket create failed for :' .  socket_strerror(socket_last_error($this->socket)) . '<br>');
                $this->socket = false;
            }
            
            $result_socket = socket_connect($this->socket, $this->ip_address, $this->port);
            if($result_socket == false)
            {
                echo ('Socket connect failed for :' .  socket_strerror(socket_last_error($this->socket)) . '<br>');
            }
        }

        public function close_socket()
        {
            socket_close($this->socket);

            return true;
        }

        public function refresh_data()
        {
            $this->new_socket();

            $get_power_command = '{"id": 1, "method": "get_prop", "params":["power"]}' . self::NVT_CR . self::NVT_LF;
            $get_power = socket_write($this->socket, $get_power_command, strlen($get_power_command));
            $get_power_result = socket_read($this->socket, strlen($get_power_command), getprotobyname('PHP_BINARY_READ'));
            if(!$power_result = json_decode($get_power_result))
            {
                return false;
            }

            $power = $power_result->result[0];
            $this->set_power($power); 

            $get_brightness_command = '{"id": 1, "method": "get_prop", "params":["bright"]}' . self::NVT_CR . self::NVT_LF;
            $get_brightness = socket_write($this->socket, $get_brightness_command, strlen($get_brightness_command));
            $get_brightness_result = socket_read($this->socket, strlen($get_brightness_command), getprotobyname('PHP_BINARY_READ'));
            if(!$brightness_result = json_decode($get_brightness_result))
            {
                return false;
            }

            $brightness = $brightness_result->result[0];
            $this->set_brightness($brightness);

            $get_color_command = '{"id": 1, "method": "get_prop", "params":["rgb"]}' . self::NVT_CR . self::NVT_LF;
            $get_color = socket_write($this->socket, $get_color_command, strlen($get_color_command));
            $get_color_result = socket_read($this->socket, strlen($get_color_command), getprotobyname('PHP_BINARY_READ'));
            if(!$color_result = json_decode($get_color_result))
            {
                return false;
            }
            $color = $color_result->result[0];
            $this->set_color($color);
            $this->close_socket();

            return true;
        }

        static function rgb_decode($rgb)
        {
            if(!intval($rgb))
            {
                return false;
            }
            
            $red = intval($rgb/65536);

            $tempo_green = intval($rgb - ($red*65536));
            $green = intval($tempo_green / 256);
            $blue = intval($tempo_green-($green*256));

            return "rgb($red, $green, $blue)";
        }

        public function toggle_power()
        {
            $command_onoff = '{ "id": 1, "method": "toggle","params":[]}' . self::NVT_CR . self::NVT_LF;
            $res_onoff = socket_write($this->socket, $command_onoff, strlen($command_onoff));
            if($res_onoff)
            {
                socket_read($this->socket, strlen($command_onoff), getprotobyname('PHP_BINARY_READ'));
                $get_power_command = '{"id": 1, "method": "get_prop", "params":["power"]}' . self::NVT_CR . self::NVT_LF;
                if($get_power = socket_write($this->get_socket(), $get_power_command, strlen($get_power_command)))
                {
                    $get_power_result = socket_read($this->get_socket(), strlen($get_power_command), getprotobyname('PHP_BINARY_READ'));
                    $power = json_decode($get_power_result);
                    $power = $power->params->power;
                    $this->power = $power;

                    return true;
                }
                return false;
            }
            return false;
        }

        private function set_power($power)
        {
            if($power == "on" || $power == "off")
            {
                $this->power = $power;

                return true;
            }
            return false;
        }

        private function set_ipaddress($ip)
        {
            if (filter_var($ip, FILTER_VALIDATE_IP)) 
            {
                $this->ip_address = $ip;
                return true;
            }
            return false;
        }

        private function set_name(string $name) : bool
        {
            if(strlen($name) > 64)
            {
                return false;
            }

            $this->name = $name;
            return true;
        }
        
        public function set_brightness($brightness)
        {
            $set_command_brightness = '{ "id": 1, "method": "set_bright", "params":['. $brightness .', "smooth", 100]}' . self::NVT_CR . self::NVT_LF;
            $result_brightness = socket_write($this->socket, $set_command_brightness, strlen($set_command_brightness));
            $result_color_bright = socket_read($this->socket, strlen($set_command_brightness), getprotobyname('PHP_BINARY_READ'));
            if($result_brightness && $brightness >= 0 && $brightness <= 100)
            {
                $this->brightness = $brightness;
                return $brightness;
            }
            return false;
        }

        public function set_color($color)
        {
            $set_command_color = '{"id":2,"method":"set_rgb", "params":['. $color .', "smooth", 500]}' . self::NVT_CR . self::NVT_LF;
            $result_color = socket_write($this->socket, $set_command_color, strlen($set_command_color));
            $result_color_read = socket_read($this->socket, strlen($set_command_color), getprotobyname('PHP_BINARY_READ'));
            if($result_color)
            {
                $this->set_mode(self::SIMPLE_COLOR);
                $this->color = Yeelight::rgb_decode($color);
                return true;
            }
            return false;
        }

        public function set_mode($mode)
        {
            if(strlen($mode) > 64 || !is_string($mode))
            {
                return false;
            }
            $this->mode = $mode;
            return true;
        }

        public function get_json_data($current_yeelight = null)
        {
            return json_encode(array('power' => $this->get_power(), 'color' => $this->get_color() , 'brightness' => $this->get_brightness(), 'mode' => $this->get_mode(), 'name' => $this->get_name(), 'yeelight' => $current_yeelight));
        }

        public function get_ipaddress()
        {
            return $this->ip_address;
        }

        public function get_port()
        {
            return $this->port;
        }

        public function get_name()
        {
            return $this->name;
        }

        public function get_power()
        {
            return $this->power;
        }

        public function get_color()
        {
            return $this->color;
        }

        public function get_brightness()
        {
            return $this->brightness;
        }

        public function get_socket()
        {
            return $this->socket;
        }

        public function get_mode()
        {
            return $this->mode;
        }
    }