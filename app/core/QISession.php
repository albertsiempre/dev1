<?php

class QISession
{
    public function __construct($params = array())
    {
        $options = array(
            'sess_encrypt_cookie',
            'sess_use_dbconfig',
            'sess_time_update_tolerance',
            'sess_dbconfig',
            'sess_use_database',
            'sess_table_name',
            'sess_expiration',
            'sess_match_ip',
            'sess_match_useragent',
            'sess_cookie_name',
            'time_reference',
            'cookie_prefix',
            'encryption_key',
        );

        foreach ($options as $key) {
            $this->$key = (isset($params[$key]))
                        ? $params[$key]
                        : Config::get('session.qeon.' . $key);
        }

        if ($this->encryption_key === '') {
            throw new Exception("No encryption key specified.");
        }

        if ($this->sess_use_database === true && $this->sess_table_name !== '') {
            if ($this->sess_use_dbconfig === true && $this->sess_dbconfig !== '') {
                $this->_sdb = DB::connection($this->sess_dbconfig)
                                ->table(Config::get('session.qeon.sess_table_name'));
            } else {
                $this->_sdb = DB::connection(Config::get('database.default'))
                                ->table(Config::get('session.qeon.sess_table_name'));
            }
        }

        $this->now = $this->getTime();

        if ($this->sess_expiration == 0) {
            $this->sess_expiration = (60*60*24*365*2);
        }

        $this->sess_cookie_name = $this->cookie_prefix . $this->sess_cookie_name;
    }

    public static function read()
    {
        $session = Request::cookie($this->sess_cookie_name);

        if ( ! $session) {
            return null;
        }

        if ($this->sess_encrypt_cookie === true) {
            $session = (new CIEncrypt)->decode($session);
        } else {
            $hash = substr($session, strlen($session) - 32);
            $session = substr($session, 0, strlen($session) - 32);

            if ($hash !==  md5($session . $this->encryption_key)) {
                return null;
            }
        }

        $session = $this->unserialize($session);

        if ( ! is_array($session)
            || ! isset($session['session_id'])
            || ! isset($session['ip_address'])
            || ! isset($session['user_agent'])
            || ! isset($session['last_activity'])) {
            return null;
        }

        if (($session['last_activity'] + $this->sess_expiration) < $this->now) {
            return null;
        }

        if ($this->sess_match_ip === true
            && $session['ip_address'] !== Request::getClientIp()) {
            return null;
        }

        $userAgent = Useragent::agent_string();

        if ($this->sess_match_useragent === true
            && trim($session['user_agent']) !== trim(substr($userAgent, 0, 120))) {
            return null;
        }

        if ($this->sess_use_database === true) {
            $query = $this->_sdb->where('session_id', $session['session_id'])
                                ->orWhere('old_session_id', $session['session_id'])
                                ->where(
                                    'last_update',
                                    '>=',
                                    $this->now - $this->sess_time_update_tolerance
                                );

            if ($this->sess_match_ip === true) {
                $query->where('ip_address', $session['ip_address']);
            }

            if ($this->sess_match_useragent === true) {
                $query->where('user_agent', $session['user_agent']);
            }

            $result = $query->get();

            if (count($result) === 0) {
                return null;
            }

            $row = $result[0];

            if (isset($row->user_data) && $row->user_data !== '') {
                $data = $this->unserialize($row->user_data);
                if (is_array($data)) {
                    foreach ($data as $key => $val) {
                        $session[$key] = $val;
                    }
                }
            }

            // Update Session Random Logout : Add current session from database to variable
            $session['session_id'] = $row->session_id;
            $session['old_session_id'] = $row->old_session_id;
        }

        return $session;
    }

    private function getTime()
    {
        if (strtolower($this->time_reference) === 'gmt') {
            $time = mktime(
                gmdate("H", $now),
                gmdate("i", $now),
                gmdate("s", $now),
                gmdate("m", $now),
                gmdate("d", $now),
                gmdate("Y", $now)
            );
        } else {
            $time = time();
        }

        return $time;
    }

    private function unserialize($data)
    {
        $data = @unserialize(stripslashes($data));

        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_string($val)) {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }

            return $data;
        }

        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }
}