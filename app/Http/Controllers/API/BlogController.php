<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use App\Blog;
use App\Http\Controllers\Controller as Controller;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get list of blogs
        $blogs = Blog::all();
        $message = 'Blogs retrieved successfully.';
        $status = true;

        //Call function for response data
        $response = $this->response($status, $blogs, $message);
        return $response;
    }

    /**
     * Store a newly created blog in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Get request data
        $input = $request->all();

        //Validate requested data
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $blog = Blog::create($input);
        $message = 'Blog created successfully.';
        $status = true;

        //Call function for response data
        $response = $this->response($status, $blog, $message);
        return $response;
    }

    /**
     * Update the specified blog in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Get request data
        $input = $request->all();

        //Validate requested data
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            $blog = [];
            $status = 'fail';
            $response = $this->response($status, $blog, $message);
            return $response;
        }

        //Update blog
        $blog = Blog::find($id)->update(['name' => $input['name'], 'description' => $input['description']]);
        $message = 'Blog updated successfully.';
        $status = true;

        //Call function for response data
        $response = $this->response($status, $blog, $message);
        return $response;
    }

    /**
     * Display the specified blog.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::find($id);

        //Check if blog found or not.
        if (is_null($blog)) {
            $message = 'Blog not found.';
            $status = false;
            $response = $this->response($status, $blog, $message);
            return $response;
        }
        $message = 'Blog retrieved successfully.';
        $status = true;

        //Call function for response data
        $response = $this->response($status, $blog, $message);
        return $response;
    }

    /**
     * Remove the specified blog from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Delete blog
        $blog = Blog::findOrFail($id);
        $blog->delete();
        $message = 'Blog deleted successfully.';
        $status = true;

        //Call function for response data
        $response = $this->response($status, $blog, $message);
        return $response;
    }

    /**
     * Response data
     *
     * @param $status
     * @param $blog
     * @param $message
     * @return \Illuminate\Http\Response
     */
    public function response($status, $blog, $message)
    {
        //Response data structure
        $return['success'] = $status;
        $return['data'] = $blog;
        $return['message'] = $message;
        return $return;
    }
}
