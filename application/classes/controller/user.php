<?php
/**
 * Description of user
 *
 * @author jj
 */
class Controller_User extends Controller_Resource {

    private static $valid_update_types = [
        'password_reset', 'complete_registration'
    ];

    private static $valid_get_types = [
        'verify_key', 'has_social', 'is_unique'
    ];

    private static $valid_create_types = [
        'registration', 'join_beta'
    ];

    public function before()
    {
        static::$model = Model::factory('user');
        parent::before();

    }


    /**************************************************************************
     * Default Actions
     *************************************************************************/

    /**
     * Fetch by id/username/email if available. If not, fetch users
     * Check if there is a logged in user
     * Fetch requested user and check relation to logged_in user
     *
     * @return type
     */
    public function action_index()
    {
        $id = $this->request->param('id');

        $type = $this->params['request_type'];

        if (in_array($type, static::$valid_get_types) && method_exists($this, $type))
            return $this->{$type};

        if ($id)
            return $this->get($id);
        else
            return $this->search();
    }

    /**
     * Changes to user settings, notifications, profile info etc will use
     * this update.
     */
    public function action_update()
    {
        // get the type of update
        $type = $this->params['request_type'];

        if (!$type)
            return $this->oauth_error('invalid_request', 400);

        if (in_array($type, static::$valid_update_types) && method_exists($this, $type))
            return $this->{$type};

        return $this->oauth_error('invalid_request', 400);
    }

    /**
     * Creates a new user with the given params.
     */
    public function action_create()
    {

    }

    /**
     * Changes the state of a user to inactive. No real delete in the api.
     * Also checks if logged in user has authority to do so.
     */
    public function action_delete()
    {

    }

    /**************************************************************************
     * Public Connections
     *************************************************************************/

     /**
     * Retrieves an array of [user_ids and user names] of people who like this user
     */
    public function action_likes()
    {
        return $this->data(['action' => ' in likes']);
    }

    /**
     * Retrieves an array of [user_ids and user names] of people who like this user
     */
    public function action_subscribes()
    {
        return $this->data(['action' => ' in likes']);
    }


    /**************************************************************************
     * Public Actions
     *************************************************************************/

    /**
     * Performs a like on the given user
     */
    public function action_like()
    {
        return parent::interact('like', 'user');
    }

    /**
     * Performs an unlike on the given user
     */
    public function action_unlike()
    {
        return parent::interact('unlike', 'user');
    }

    /**
     * Performs a subscribe on the given user
     */
    public function action_subscribe()
    {
        return parent::interact('subscribe', 'user');
    }

    /**
     * Performs an unsubscribe on the given user
     */
    public function action_unsubscribe()
    {
        return parent::interact('unsubscribe', 'user');
    }


    /**************************************************************************
     * Protected Utility Actions
     *************************************************************************/

    /**
     * Checks for unique username or email.
     *
     * @param string $this->params['type'] decides whether it is username or email
     * @param string $request->param['id'] the username or email to check
     */
    protected function unique()
    {
        $model = Mongo_Document::factory('user');

        $type = $this->params['request_type'];
        $value = $this->request->param('id');

        if(!$type || !$value)
            return $this->oauth_error('invalid_request', 400);

        if($model->get([$type => $value]))
            return $this->error('register/user', 'username.' . $type . '_not_available');

        return $this->data(['success' => 1]);

    }

    /**
     * 1. Returns a user's public information if not logged in.
     * 2. Returns all user information if logged in & ids are the same.
     * 3. Retrieves a user & it's relation to logged in user.
     *
     * @param string|MongoID $id
     * @return object
     */
    protected function get($id)
    {
        /**
        *   $user = Mongo_Document::factory('user')->set('id','colin')->set('email','colin@mollenhour.com');
        *   $post = Mongo_Document::factory('post');
        *   $post->user = $user;
        *   $post->title = 'MongoDb';
        *   $post->save()
        *   // db.users.save({"_id":"colin","email":"colin@mollenhour.com"})
        *   // db.posts.save({"_id":Object,"_user":"colin","title":"MongoDb"})
        *
        *   $post = new Model_Post($id);
        *   $post->_user;
        *   // "colin" - the post was loaded lazily.
        *   $post->user->id;
        *   // "colin" - the user object was created lazily but not loaded.
        *   $post->user->email;
        *   // "colin@mollenhour.com" - now the user document was loaded as well.
         */
//        if(!parent::logged_in())
//            return $this->data(static::$model->get_public($id));
        $user = Mongo_Document::factory('user');
        $user->load($id, ['_id' => 1, 'name' => 1]);
//        $post = Mongo_Document::factory('post');
//
//        $post->title = 'Wassup to the people!!';
//        $post->user = ['_id' => $user->id, 'name' => $user->name];
//        $post->save();

        return $this->return = $user->as_array();
    }

    /**
     * 1. Retrives public user information by search params listed below.
     * 2. Retrieves users & their relation to current logged in user.
     *
     * @return array
     */
    protected function search()
    {
        return $this->data(['action' => ' in search']);
    }

}

?>
