<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserAccountController extends Controller
{
    public function registration()
    {
        return view('account.registration');
    }

    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $message = "You have registered successfully.";
            Session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function login()
    {
        return view('account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error', 'Either email/password is incorrect');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }


    public function profile()
    {

        // here we will get user id, which user logged in
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();

        return view('account.profile', [
            'user' => $user,
        ]);
    }

    // update profile function
    public function updateProfile(Request $request)
    {

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id'
        ]);

        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            Session()->flash('success', 'Profile updated successfully');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // /upload/change image from profile
    public function updateProfileImg(Request $request)
    {
        // dd($request->all());
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048' // Adjust max size as needed
        ]);
        if ($validator->passes()) {

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;
            $image->move(public_path('/profile_img/'), $imageName);

            // $sourcePath = public_path('/profile_img/'. $imageName);
            // $manager = new ImageManager(Driver::class);
            // $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (150x150) ratio and resize to 150x150 pixel
            // $image->cover(150, 150);
            // $image->toPng()->save(public_path('/profile_img/thumb/'. $imageName));

            // This code will create a small thumbnail
            $sourcePath = public_path() . '/profile_img/' . $imageName;
            $destPath = public_path() . '/profile_img/thumb/' . $imageName;
            $image = Image::make($sourcePath);
            $image->fit(300, 275);
            $image->save($destPath);

            // delete old profile image, when user update his/her new image
            File::delete(public_path('/profile_img/' . Auth::user()->image));
            File::delete(public_path('/profile_img/thumb/' . Auth::user()->image));

            User::where('id', $id)->update(['image' => $imageName]);

            Session()->flash('success', 'Profile image updated successfully.');
            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function myProjects()
    {

        //metioned the code of the paginator in AppServiceProvider Class otherwise your paginator will not work perfectly

        $projects = Project::where('client_id', Auth::user()->id)->with('projectType')->orderBy('created_at', 'DESC')->paginate(10);
        return view('account.project.my-projects', [
            'projects' => $projects,
        ]);
    }

    public function editProject($id)
    {
        $projectCategories = ProjectCategory::orderBy('name', 'ASC')->where('status', 1)->get();
        $projectTypes = ProjectType::orderBy('name', 'ASC')->where('status', 1)->get();

        // If user write another user id through url show him 404 page
        $project = Project::where([
            'client_id' => Auth::user()->id,
            'id' => $id,
        ])->first();

        if ($project == null) {
            abort(404);
        }

        return view('account.project.edit', [
            'categories' => $projectCategories,
            'project_types' => $projectTypes,
            'project' => $project,
        ]);
    }

    // Update project
    public function updateProject($projectId, Request $request)
    {

        $rules = [
            'title' => 'required|min:5|max:200',
            'projectCategory' => 'required',
            'projectType' => 'required',
            'description' => 'required',
            'experience' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {

            $project = Project::find($projectId);
            $project->title = $request->title;
            $project->category_id = $request->category;
            $project->project_type_id = $request->projectType;
            $project->client_id = Auth::user()->id;
            $project->budget = $request->budget;
            $project->description = $request->description;
            $project->responsibility = $request->responsibility;
            $project->qualifications = $request->qualifications;
            $project->experience = $request->experience;
            $project->save();

            Session()->flash('success', 'Project updated successfully.');
            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    // This method will delete project
    public function deleteProject(Request $request)
    {
        $project = Project::where([
            'client_id' => Auth::user()->id,
            'id' => $request->projectId,
        ])->first();

        if ($project == null) {
            Session()->flash('error', 'Either Project deleted or not found.');
            return response()->json([
                'status' => true,
            ]);
        }

        Project::where('id', $request->projectId)->delete();
        Session()->flash('success', 'Project deleted successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    // Change password function
    public function changePassword(Request $request)
    {
        $data = [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ];
        $validator = Validator::make($request->all(), $data);
        if ($validator->passes()) {

            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();
            if (!Hash::check($request->old_password, $user->password)) {

                Session::flash('error', 'Your old password is incorrect, Please try again');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            Session()->flash('success', 'Your have successfully change your password');
            return response()->json([
                'status' => true,
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
