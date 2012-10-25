<?php
/**
 * Description of user
 *
 * @author jj
 */
class Controller_User extends Controller_Resource {

    public function before()
    {
        static::$model = Model::factory('user');
        parent::before();

    }

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

        if($id)
            return $this->get($id);
        else
            return $this->search();
    }

    public function action_update()
    {
    }

    public function action_create()
    {

    }

    public function action_delete()
    {

    }

    public function action_likes()
    {
        return $this->data(['action' => ' in likes']);
    }

    public function action_like()
    {
        $this->interact('like');

        $this->data(['success' => 1]);
    }

    public function action_unlike()
    {
        $this->interact('unlike');

        $this->data(['success' => 1]);
    }

    public function action_subscribe()
    {
        $this->interact('subscribe');

        $this->data(['success' => 1]);
    }

    public function action_unsubscribe()
    {
        $this->interact('unsubscribe');

        $this->data(['success' => 1]);
    }

    protected function interact($interaction)
    {
        $interact = Mongo_Document::factory('interaction', null, static::$user_id);
        $interact->{$interaction}($this->request->param('id'), 'user');
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
