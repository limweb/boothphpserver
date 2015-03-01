<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';

class User  extends Illuminate\Database\Eloquent\Model  {
       protected $table = 'app_users';
       public $timestamps = true;
       protected $guarded = array('id');
       protected $hidden = ['password','created_at','updated_at','created_by','updated_by'];
};

// $user = new stdClass();
// $user->id = 7;
// $user->role = 'Admin';
// $user->username = "testadmin1";
// $user->password = "testpassword123";
// $sv = new UsersService();
// echo $sv->register($user);
// exit();
// $rs = $sv->login(['username'=>'testadmin1','password'=>'testpassword123']);
// echo $rs;

// echo $sv->updateuser($user);
// echo $sv->getuserbyid(7);
// echo $sv->deluser(1);
// echo $sv->getusers();



class UsersService {


    private $options;


    public function __construct() {
            consolelog("start User service");
            $this->options =  [
                'cost' => 11,
                'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
            ];
    }


    public function register($user){
           $user->password = password_hash($user->password, PASSWORD_BCRYPT,$this->options);
           $user = json_decode(json_encode($user),TRUE);
           return User::create((array) $user);
    }

    public function login($user){
        consolelog('login:');
        // $u = User::where('username',$user['username'])->first();
        // if (password_verify($user['password'], $u['password'])) {
        //         echo 'Password is valid!';
        //         return $u;
        // } else {
        //         echo 'Invalid password.';
        // }
        $user = toObj($user);
        $u = User::where('username',$user->username)->first();
        if(password_verify($user->password,$u->password)){
              // echo $u  . 'Password is vald!';
            // var_dump($u->toArray());
              return $u->toArray();
        } else {
             // echo 'Invalid password';
             return -1;
        }

    }

    public function deluser($id){
            $u = User::destroy($id);
            return $u;
    }

    public function updateuser($user){
            $user->password = password_hash($user->password, PASSWORD_BCRYPT,$this->options);
            $u = User::find($user->id);
            if($u) {
                $user = json_decode(json_encode($user),TRUE);
                return $u->update($user);
            } else {
                return -1;
            }
    }

    public function getusers(){
        consolelog('get all user');
        $rs = User::all();
        return $rs;
    }

   public function getuserbyid($id){
        $u = User::find($id);
        return $u;
   }


}