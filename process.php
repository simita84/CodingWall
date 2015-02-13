<?php 
	//Start session
	session_start();
	//Include DB connection file
	include('new-connection.php');
	//Processing Login Form
	if(isset($_POST['login'])&&$_POST['login']=="login")
	{
		$email		= escape_this_string($_POST['email']);
		$password	= escape_this_string($_POST['password']);	

		$get_user_query="select email,password from wall_users where email='{$email}'";
		$user=fetch_record($get_user_query);
 	
		if(!empty($user) && ( $user['password'] == $password ) )
		{
				 $_SESSION['user_name']=$user['email']; 
				 $_SESSION['is_login']=TRUE;
				 $_SESSION["success"]="Successfully logged In";
				 header("Location: user_profile.php");
				 die();
		}
		else if(empty($user) )
		{
				 $_SESSION["error"]="User not found";
				 $_SESSION['is_login']=FALSE;
		}
		else if($user['password'] != $password){
				 $_SESSION["error"]="Password incorrect ";
				 $_SESSION['is_login']=FALSE;
		}
		if($_SESSION['is_login']==FALSE || count($_SESSION["error"])!=0 ){
		 	header("Location: index.php");
			die();
		 }
	 }
	 if(isset($_POST['message_post'])&&$_POST['message_post']="message_post")
	 {
	 	$current_user = $_SESSION['user_name'];
		$current_user_query="select id from   wall_users where email='{$current_user}'";
		$current_user=fetch_record($current_user_query);
		$current_user_id=$current_user['id'];
	    //Protecting my MySQL injection
		$message_content=escape_this_string($_POST['text_message']); 
		$create_message_query="insert into messages 
		                       (wall_user_id,
								content,created_at,updated_at)
			  		  		    values('{$current_user_id}','{$message_content}',NOW(),NOW())";
	    //Create messages
	    if(strlen($message_content)==0){
	    	$_SESSION["create_message"]="Please enter valid message";
	    }
	    else
	    {
	    	if(run_mysql_query($create_message_query))
	    	{
	    		$_SESSION["create_message"]="The message created successfully";
	    	}
	    	else
	    	{
	    		$_SESSION["create_message"]="The message cannot   be created ";
	    	}
	    } 
	    header("Location: user_profile.php");
	 	die();	
	 }
	 if(isset($_POST['post_comment'])&&$_POST['post_comment']="post_comment")
	 {
	 	//Setting values for db
		$message_id=$_POST['comment'];
	    //protecting form MySQL injection
		$comment_content=escape_this_string($_POST['text_comment']); 
	 	$current_user_id=$_SESSION['user_id'];
		$create_comment_query="insert into comments 
		                       (message_id,wall_user_id,
								content,created_at,updated_at)
			  		  		    values('{$message_id}','{$current_user_id}',
			  		  		          '{$comment_content}',NOW(),NOW())";
	    //Create comments
	    if(strlen($comment_content)==0){
	    	$_SESSION["create_message"]="Please enter valid comment";
	    }
	    else
	    	{
	    		if(run_mysql_query($create_comment_query)){
	    			$_SESSION["create_message"]="The comment created successfully";
	    		}
	    		else
	    		{
	    			$_SESSION["create_message"]="The comment cannot   be created ";
	    		}
	     }
	 	header("Location: user_profile.php");
	 	die();
	 }
	 //Processing Registration Form
	if(isset($_POST['register'])&&$_POST['register']=="register")
	{
		echo "Registered";
	}
	if(isset($_POST['delete'])&&$_POST['delete']=='delete')
	{
		$message_id=$_POST['delete_message'];
		$delete_message_query="delete from messages where id='{$message_id}'";
		$delete_comment_query="delete from comments where message_id='{$message_id}'";
	 	run_mysql_query($delete_message_query);
	 	run_mysql_query($delete_comment_query);
	    $_SESSION["delete_message"]="Deleted the message successfully";
		header("Location: user_profile.php");
		die();
	}

	if(isset($_POST['logout'])&&$_POST['logout']=="logout")
	{
		session_destroy();
		header("Location: index.php");
		die();
	}


?>