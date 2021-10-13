<?php

namespace App\Api\Controllers;

use Illuminate\Support\Facades\DB;
use File;
use Image;
use App\Api\Requests\UpdateUser;
use App\Api\Requests\UpdateUserPassword;
use App\Api\Transformers\UserTransformer;

/**
 * Profile resource representation. Requires Authorization header.
 *
 * @Resource("Profile", uri="/profile")
 */
class ProfileController extends BaseController
{
    /**
     * Get user data
     *
     * Get a JSON representation of signed in user.
     *
     * @Get("/")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data":{
     *          "id": 123, "first_name": "Firstname", "last_name": "Lastname", "gender": "male", "phone": "123456789",
     *          "email": "example@example.com", "avatar": "https://betaplatform.showcasehub.com/avatars/user.jpg",
     *           "contact_email": "mycontact@example.com", "website": "www.example.com",
     *          "social_links": "facebook.com/exampleprofile", "birth_date": "06/20/1990", "about": "example",
     *          "location": "New York City", "location_state": "NY", "activities": {{"id": 1, "name": "dancing"}},
     *          "genres": {{"id": 1, "name": "hip-hop"}, {"id": 2, "name": "ballet"}}, "levels": {{"id": 1, "name": "newbie"}}
     *     }, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function index()
    {
        $user = $this->auth->user();
        return $this->response()->item($user, new UserTransformer());
    }

    /**
     * Update profile
     *
     * Update user profile.
     *
     * @param UpdateUser $request
     *
     * @Patch("/")
     * @Transaction({
     *     @Request(body={"first_name": "New first name", "last_name": "New last name", "gender": "male or female",
     *         "avatar":"File: jpeg,jpg,png,gif", "activity_type": 1, "genres": {1, 2}, "location": "New location",
     *         "location_state": "New state", "about": "New about", "contact_email": "newcontact@example.com",
     *         "phone": "987654321", "website": "www.newexample.com", "social_links": "facebook.com/new-profile",
     *         "birth_date": "01/15/1990", "genres": {2,3}, "levels": {3}}, headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"message": "Profile successfully updated!"}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{
     *         "email":{"The email field is required."}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function update(UpdateUser $request)
    {
        $data = $request->resolvedData();
        $user = app(\Dingo\Api\Auth\Auth::class)->user();
        if ($request->hasFile('avatar')) {
            $file = $request->file("avatar");
            $fileName = str_random(8) . "_" . $file->getClientOriginalName();
            $file->move(public_path("avatars/"), $fileName);
            self::storeAvatar($fileName);
            File::delete(public_path() . '/' . $user->avatar);
            $data['avatar'] = "/avatars/" . $fileName;
        }else{
            array_forget($data, 'avatar');
        }
        $user->update($data);
        if($data['levels'] !='')
            $user->performance_levels()->sync($data['levels']);
        if($data['genres'] !='')
            $user->activity_genres()->sync($data['genres']);
//      $user->activity_types()->sync($data['activity_type']);

        return $this->response->array(['data' => ['message' => 'Profile successfully updated!'], 'status_code' => 200]);
    }

    /**
     * Change password
     *
     * Change user password. For each request parameter may be provided validation errors.
     *
     * @Patch("/password")
     * @Transaction({
     *     @Request(body={"password_old": "old_password", "password": "new_password", "password_confirmation": "new_password"},
     *         headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"message": "Password successfully changed!"}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{"password":
     *         {"The password field is required."}}}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{"password_old":
     *         {"Old password entered incorrectly!"}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function password(UpdateUserPassword $request)
    {
        $data = $request->all();
        $user = app(\Dingo\Api\Auth\Auth::class)->user();
        $result = $user->changePassword($data, $user->id);
        if ($result instanceof \Illuminate\Support\MessageBag) {
            return $this->response->array([
                'message' => "422 Unprocessable Entity",
                'errors' => ['password_old' => ['Old password entered incorrectly!']],
                'status_code' => 422,
            ])->setStatusCode(422);
        }

        return $this->response->array(['data' => ['message' => 'Password successfully changed!'], 'status_code' => 200]);
    }

    /**
     * Get genres list
     *
     * Get a JSON representation of genres based on user's activity type.
     *
     * @Get("/activity-genres")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data":{{"id": 1, "name": "Hip-hop"}, {"id": 2, "name": "Modern"}}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error": "Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function activityGenres()
    {
        $user = app(\Dingo\Api\Auth\Auth::class)->user();

        $genres = DB::table('user_activity_types')->where('user_id', $user->id)
            ->join('activity_genres', 'user_activity_types.activity_type_id', '=', 'activity_genres.activity_type_id')
            ->select('activity_genres.id', 'activity_genres.name')
            ->get();

        return $this->response->array(['data' => $genres, 'status_code' => 200]);
    }

    protected static function storeAvatar($fileName = null)
    {
        $image_path_name = public_path("avatars/") . $fileName;
        $height = env('AVATAR_IMG_H', 320);
        $width = env('AVATAR_IMG_W', 250);
        $image = Image::make($image_path_name);
        if ($image->height() / $image->width() > 1) {
            $background = Image::make($image_path_name)->fit($width, $height)/*->blur(80)*/
            ->fill('#ffffff');
            $image->resize($width, $height, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $image = $background->insert($image, 'center');
        } else {
            $image->fit($width, $height);
        }
        File::delete($image_path_name);
        $image->save($image_path_name);

        return $image_path_name;
    }
}
