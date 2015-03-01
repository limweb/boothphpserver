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
// // echo $sv->register($user);
// echo $sv->getusers();
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
           consolelog($user);
           $user->password = password_hash($user->password, PASSWORD_BCRYPT,$this->options);
           $user = json_decode(json_encode($user),TRUE);
           return User::create((array) $user);
    }

    public function login($user){
        consolelog('login:');
        consolelog($user);
        // $u = User::where('username',$user['username'])->first();
        // if (password_verify($user['password'], $u['password'])) {
        //         echo 'Password is valid!';
        //         return $u;
        // } else {
        //         echo 'Invalid password.';
        // }
        $user = toObj($user);
        $u = User::where('username',$user->username)->where('status','1')->first();
        if(password_verify($user->password,$u->password)){
              // echo $u  . 'Password is vald!';
            // var_dump($u->toArray());
              consolelog($u);
              return $u->toArray();
        } else {
             // echo 'Invalid password';
             return -1;
        }

    }

    public function deluser($id){
            // $u = User::destroy($id);
      $u = User::find($id);
      $u->status = 0;
      $u->save();
      return $u;
    }

    public function updateuser( $user){
           consolelog('update user');
           $user = (object) $user;
            // $user->password = password_hash($user->password, PASSWORD_BCRYPT,$this->options);
            // $user = json_decode(json_encode($user),TRUE);
            $u = User::find($user->id);
            if($u) {
                $u->username = $user->username;
                $u->status = $user->status;
                $u->role = $user->role;
                $u->save();
                // return $u->update($user);
                consolelog($u);
                return $u;
            } else {
                return -1;
            }
    }

    public function getusers(){
        consolelog('get all user');
        // $rs =  User::where('status',1)->get()->toArray();
        $rs = User::all()->toArray();
        return $rs;
    }

   public function getuserbyid($id){
        $u = User::find($id);
        return $u;
   }


    public function changepass($obj) {
        $u = User::find($obj->id);
        if($u) {
              if( password_verify($obj->oldpassword,$u->password) ){
                   $u->password = password_hash($obj->newpassword, PASSWORD_BCRYPT,$this->options);
                   $u->save();
                   return $u;
              }
        } else {
          return -1;
        }

    }

    public function resetpass($id) {
        $u = User::find($id);
        $u->password = password_hash('123456', PASSWORD_BCRYPT,$this->options);
        $u->save();
    }

}