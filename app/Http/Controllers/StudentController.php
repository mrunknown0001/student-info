<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Hashing\Hasher;

use App\User;
use App\StudentLog;
use App\PasswordChange;

class StudentController extends Controller
{
    
    /*
     * studentLogin() Method use to login students
     */
    public function studentLogin(Request $request)
    {
    	/*
		 * Validating Input from Admin and Co-Admin Login form
		 */
		$this->validate($request, [
			'id' => 'required',
			'password' => 'required'
			]);

		/*
		 * Assigning values to variables
		 */
		$id = $request['id'];
		$password = $request['password'];

		if(Auth::attempt(['user_id' => $id, 'password' => $password])) {

			/*
			 * Check if the user is inactive
			 */
			if(Auth::user()->status != 1) {
				Auth::logout();
				return redirect()->route('login')->with('error_msg', 'Your Accout is Inactive! Please Report to Admin.');
			}

			/*
			 * Check if ther user is student
			 * if not the user will not be login
			 */
			if(Auth::user()->privilege != 3) {
				Auth::logout();
				return redirect()->route('login')->with('error_msg', 'Please use this login form.');
			}


			/*
			 * check if student
			 */
			if(Auth::user()->privilege == 3 ) {

				/*
				 * Save students log
				 */
				$students_log = new StudentLog();

				$students_log->student = Auth::user()->id;
				$students_log->action = 'Login to your account';

				$students_log->save();


				$password_change = PasswordChange::where('user_id', Auth::user()->id)->first();

				if(!empty($password_change)) {
					return redirect()->route('students_home');
				}
				else {
					return redirect()->route('students_settings')->with('notice', "You are using default password given by admin, you must change your password immediately.");
				}

				
			}

			// if there is something error
			Auth::logout();
			return redirect()->route('home')->with('error_msg', 'App has encountered error. Please reload this page.');


		}

		// Error Message if student id and/or password is incorrect
		return redirect()->route('home')->with('error_msg', 'Student ID or Password is Incorrect!');

    }


    /*
     * getAllStudentsLog() use to get all log made by student and 
     * pass it in the view
     */
    public function getAllStudentsLog()
    {
    	$logs = StudentLog::where('student', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(15);

    	return view('students.students-log', ['logs' => $logs]);
    
    }


    /*
     * getStudentProfile() use to fetch students profile
     */
    public function getStudentProfile()
    {

    	// get the students profile
    	$profile = User::findorfail(Auth::user()->id);

    	return view('students.students-profile', ['s' => $profile]);
    
    }


    /*
     * postChangePassword() method to cnhange password for students
     */
    public function postChangePassword(Request $request)
    {
    	/*
    	 * input validation
    	 */
    	$this->validate($request, [
    		'old_pass' => 'required',
    		'password' => 'required| min:8 | max:64 | confirmed',
    		'password_confirmation' => 'required'
    		]);

    	
    	// Assign values to variable
    	$old_pass = $request['old_pass'];
    	$password = $request['password'];


    	// Check if old password and new password is the same, this is not permitted
    	if($old_pass == $password) {
    		return redirect()->route('students_settings')->with('error_msg', 'Choose different password from your current password.');    	}

    	// bcrypt (hashed) password of students
    	$hashed_password = Auth::user()->password;

    	// id of the student
    	$student_id = Auth::user()->id;

    	// verify the entered old password
    	$password_compare = password_verify($old_pass, $hashed_password);
    	if($password_compare == True) {
    		$student = User::findorfail($student_id);

    		$student->password = bcrypt($password);

    		$student->save();

    		/*
			 * Save students log
			 */
			$students_log = new StudentLog();

			$students_log->student = Auth::user()->id;
			$students_log->action = 'Password Change';

			$students_log->save();


			$password_change = PasswordChange::where('user_id', Auth::user()->id)->first();

			if(empty($password_change)) {
				
				$change = new PasswordChange();

				$change->user_id = Auth::user()->id;
				$change->status = 1;

				$change->save();

			}

    		// Successfully Change Password
    		return redirect()->route('students_settings')->with('success', 'Your Password Has Been Successfully Changed!');
    	}
    	else {
    		// Wrong Password
    		return redirect()->route('students_settings')->with('error_msg', 'Your Password is Incorrect! Please Try Again.');
    	}
    
    }


    /*
     * showProfileEdit() methods use to update profile of a student, that loads on form
     */
    public function showProfileUpdate()
    {

    	$profile = User::findorfail(Auth::user()->id);

    	return view('students.students-profile-update', ['student' => $profile]);
    }


    /*
     * postProfileUpdate() method is used to update profile
     */
    public function postProfileUpdate(Request $request)
    {

    	/*
    	 * input validation
    	 */
    	$this->validate($request, [
    		'firstname' => 'required',
    		'lastname' => 'required',
    		'email' => 'email|required',
    		'mobile' => 'required',
    		'birthday' => 'required',
    		'password' => 'required'
    		]);

    	// Assign to varaibles
    	$firstname = $request['firstname'];
    	$lastname = $request['lastname'];
    	$email = $request['email'];
    	$mobile = $request['mobile'];
    	$birthday = $request['birthday'];
    	$password = $request['password'];

    	$student_update = User::findorfail(Auth::user()->id);
    	$student_hashed_password = Auth::user()->password;

    	/*
    	 * Email Address Availability Check
    	 */
    	if($email != Auth::user()->email) {

    		$email_check = User::where('email', $email)->get();

    		if(!empty($email_check)) {

    			return redirect()->route('students_profile_update')->with('error_msg', 'This email: ' . $email . ' is registered with different account, please choose different active email address.');

    		}
    		else {

    			$student_update->firstname = $firstname;
	    		$student_update->lastname = $lastname;
	    		$student_update->mobile = $mobile;
	    		$student_update->birthday = $birthday;

	    		// check if password is correctly typed
	    		if(password_verify($password, $student_hashed_password)) {

	    			$student_update->save();

	    			$students_log = new StudentLog();

	    			$students_log->student = Auth::user()->id;
	    			$students_log->action = 'Update Profile';

	    			$students_log->save();

	    			return redirect()->route('students_profile_update')->with('success', 'Profile Updated Successfully!');

	    		}
	    		else {
	    			return redirect()->route('students_profile_update')->with('error_msg', 'Incorrect Password!');
	    		}

	    	}
    	}
    	// if the email entered is same with the current email address of the student, nothing to do with it
    	else {

    		$student_update->firstname = $firstname;
    		$student_update->lastname = $lastname;
    		$student_update->mobile = $mobile;
    		$student_update->birthday = $birthday;

    		// check if password is correctly typed
    		if(password_verify($password, $student_hashed_password)) {

    			$student_update->save();

    			$students_log = new StudentLog();

    			$students_log->student = Auth::user()->id;
    			$students_log->action = 'Update Profile';

    			$students_log->save();

    			return redirect()->route('students_profile_update')->with('success', 'Profile Updated Successfully!');

    		}
    		else {
    			return redirect()->route('students_profile_update')->with('error_msg', 'Incorrect Password!');
    		}

    	}
    }

}