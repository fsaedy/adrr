<?php

class WebUser extends CWebUser
{

    public function getRole()
    {
        return $this->getState('__role');
    }
    
    public function getId()
    {
        return $this->getState('__id') ? $this->getState('__id') : 0;
    }

//    protected function beforeLogin($id, $states, $fromCookie)
//    {
//        parent::beforeLogin($id, $states, $fromCookie);
//
//        $model = new UserLoginStats();
//        $model->attributes = array(
//            'user_id' => $id,
//            'ip' => ip2long(Yii::app()->request->getUserHostAddress())
//        );
//        $model->save();
//
//        return true;
//    }

    protected function afterLogin($fromCookie)
	{
        parent::afterLogin($fromCookie);
        $this->updateSession();
	}

    public function updateSession() {
        $user = Yii::app()->getModule('user')->user($this->id);
        $userAttributes = CMap::mergeArray(array(
                                                'email'=>$user->email,
                                                'username'=>$user->username,
                                                'create_at'=>$user->create_at,
                                                'lastvisit_at'=>$user->lastvisit_at,
                                           ),$user->profile->getAttributes());
        foreach ($userAttributes as $attrName=>$attrValue) {
            $this->setState($attrName,$attrValue);
        }
    }

    public function model($id=0) {
        return Yii::app()->getModule('user')->user($id);
    }

    public function user($id=0) {
        return $this->model($id);
    }

    public function getUserByName($username) {
        return Yii::app()->getModule('user')->getUserByName($username);
    }

    public function getAdmins() {
        return Yii::app()->getModule('user')->getAdmins();
    }

    public function isAdmin() {
        return Yii::app()->getModule('user')->isAdmin();
    }

    public function getIsSenior ()
	{
		return Yii::app()->getModule('user')->isSenior();
	}
	
	public function getIsEng()
	{
		return Yii::app()->getModule('user')->isEng();
	}

    public function getUsersByRole ($role)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'user_id';

        switch ($role)
        {
            case 'senior':
                $criteria->condition = 'senior = 1';
            break;

            case 'eng':
                $criteria->condition = 'eng = 1';
            break;
        }

        $users = Profile::model()->findAll ($criteria);

        $ids = array ();

        foreach ($users as $user)
        {
            $ids[] = intval($user->attributes['user_id']);
        }

        return $ids;
    }
}