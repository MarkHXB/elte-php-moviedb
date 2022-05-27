<?php

class Series
{
    private $series_storage;
    private $series = NULL;

    public function __construct(IStorage $series_storage)
    {
        $this->series_storage = $series_storage;

        if (isset($_SESSION["series"])) {
            $this->series = $_SESSION["series"];
        }
    }

    public function register($data)
    {
        $user = [
            'username'  => $data['username'],
            'password'  => password_hash($data['password'], PASSWORD_DEFAULT),
            'fullname'  => $data['fullname'],
            "roles"     => ["user"],
        ];
        return $this->user_storage->add($user);
    }

    public function user_exists($username)
    {
        $users = $this->user_storage->findOne(['username' => $username]);
        return !is_null($users);
    }

    public function authenticate($username, $password)
    {
        $users = $this->user_storage->findMany(function ($user) use ($username, $password) {
            return $user["username"] === $username &&
                password_verify($password, $user["password"]);
        });
        return count($users) === 1 ? array_shift($users) : NULL;
    }

    public function is_authenticated()
    {
        return !is_null($this->user);
    }

    public function authorize($roles = [])
    {
        if (!$this->is_authenticated()) {
            return FALSE;
        }
        foreach ($roles as $role) {
            if (in_array($role, $this->user["roles"])) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function login($user)
    {
        $this->user = $user;
        $_SESSION["user"] = $user;
    }

    public function logout()
    {
        $this->user = NULL;
        unset($_SESSION["user"]);
    }

    public function authenticated_user()
    {
        return $this->user;
    }
}
