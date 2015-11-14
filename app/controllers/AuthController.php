<?php
/*
 * 这里应该是身份验证与权限设置的地方，可以理解
 *
 */

class AuthController extends myController
{

    public function loginAction()
    {
        if($this->request->isPost()){
            $data = $this->request->getPost();
//            var_dump($data);
            $user = $this->getUserByEmail($data['email']);

            if($this->security->checkHash($data['password'],$user->password)){
                $this->registerSession($user);
                $this->flash->success('欢迎'.$user->name.'登录！你上次登录的时间是：'.$user->getLastLoginTime());
                $this->redirectByRoute(['for'=>'movies.index']);
//                dd('成功登录！');
            }else{
                $this->flash->error('登录不成功，密码或者邮件地址有误！');
            }
        }
        //避免重复登录，还应有一个logout的设置
        if($this->session->has('auth')) $this->redirectByRoute(['for'=>'movies.index']);
        $this->view->form = $this->buildLoginForm();

    }

    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->redirectByRoute(['for'=>'auth.login']);

    }

    public function notFindedAction()
    {
        dd('你没有权限进行该操作！');
    }

    public function resource2rolesAction()
    {
        foreach($this->router->getRoutes() as $route){
            echo $route->getName().'<br>';
        }

        dd(count($this->router->getRoutes()));

        // 1、如何将矩阵保存下来，使用什么数据结构？ 保存在redis中还是别的上？
        // 2、如何读取数据，根据role和resource来判断是否可以拥有操作权限？
    }







    //@todo 如果信息填写错误，如何用类似laravel中的方法，将错误数据传递回来呢？flash？session？还是别的方法？
    private function buildLoginForm()
    {
        $form = new \Phalcon\Forms\Form();
        $form->add(new \Phalcon\Forms\Element\Text('email'));
        $form->add(new \Phalcon\Forms\Element\Password('password'));
        $form->add(new \Phalcon\Forms\Element\Check('remember'));
        $form->add(new \Phalcon\Forms\Element\Submit('Login'));
        return $form;
    }

    /**将登录对象写入session中
     * @param $user
     */
    private function registerSession(Users $user)
    {
        $this->session->set('auth',['id'=>$user->id,'name'=>$user->name]);
        $user->setLastLogin();//设置当前登录用户的login时间，以便将来进行跟踪
        $this->cookies->set('auth',$user,time() + 15 * 86400);

    }

    /**
     * @param $email
     * @return Users
     */
    private function getUserByEmail($email)
    {
        return Users::findFirst([
            "conditions"=>'email = :email:',
            'bind'=>['email'=>$email]
        ]);
    }


}

