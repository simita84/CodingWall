<?php 
	//Starting the session
	session_start();
	//Include DB connection file
	include('new-connection.php');
	//Saving  user session information after login
	$current_user = $_SESSION['user_name'];
	$current_user_query="select id from   wall_users where email='{$current_user}'";
	$current_user=fetch_record($current_user_query);
	//Assigning the values for DB
	$current_user_id=$current_user['id'];
	$_SESSION['user_id']=$current_user['id'];
	 //Retreiving all messages in the DB
	$fetch_messages_query="select wall_users.id as 'user_id', wall_users.first_name,
	                              wall_users.last_name,
	                    	 	  messages.id,messages.content,messages.created_at
	                 			  from messages join wall_users 
	                 		      on messages.wall_user_id=wall_users.id
	                		      order by messages.created_at desc";
	$messages_from_db=array();
	$messages_from_db=fetch_all($fetch_messages_query);
	//Retreiving comments from DB
	$fetch_comments_query="select wall_users.first_name,wall_users.last_name,
							      comments.message_id,comments.content,
							      comments.created_at  
						   		  from comments join wall_users on
						  		  comments.wall_user_id=wall_users.id 	
						  		  order by comments.created_at desc";
	$comments_from_db=array();
	$comments_from_db=fetch_all($fetch_comments_query);	 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Profile</title>
		<link rel="stylesheet" 
			  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" 
			  href="styles.css">	  
	</head>
	<body class="container">
		<div class="row top_row">
			<h4>
			<div class="col-md-3 well">
				CodingDojo Wall
			</div>
			<div class="col-md-4 col-md-offset-3">
				<?php
					echo "Welcome ".$_SESSION["user_name"];     
				?>		 
				 <form action="process.php" method="post">
				 	<input type="hidden" name="logout" value="logout">
				 	<input type="submit" value="logout">
				 </form>
			</div>
			</h4>
		</div>
		<div class="row">
		    <?php 
			if(isset($_SESSION["success"]))
		 	{?>
				<div class="col-md-6 col-md-offset2 info">
				 	<p> <?php echo $_SESSION["success"];?></p>
				</div>
			<?php 
			}
		 	unset($_SESSION["success"]);
			 
			if(isset($_SESSION["delete_message"]))
		 	{?>
				<div class="col-md-6 col-md-offset2 info">
				 	<p> <?php echo $_SESSION["delete_message"];?></p>
				</div>
			<?php 
			}
		 	unset($_SESSION["delete_message"]);
			 
			if(isset($_SESSION["create_message"]))
		 	{?>
				<div class="col-md-6 col-md-offset2 info">
				 	<p> <?php echo $_SESSION["create_message"];?></p>
				</div>
			<?php 
			}
		 	unset($_SESSION["create_message"]);
			?>
		</div>
	    <div class="row">
	    	<h2>Post a message</h2>
	    	<form action="process.php" method="post">
	    		<div class="form-group">
	    			<input type="text" name="text_message" id="text_message">
	    		</div>
	    		<input type="hidden" name="message">
	    		<div class="form-group">
	    			<input type="submit" name="message_button" 
	    				   value="Post Message" class="btn btn-primary">
	    			<input type="hidden" name="message_post" 
	    				   value="message_post">
	    		</div>
	    	</form>
	    </div>
	    <div class="row">	    	
	    	<?php 
	    		if(isset($messages_from_db)){?>
	    		<h2>Messages</h2>    		 	 
	    			<?php 
	    				foreach ($messages_from_db as $message) 
	    				{	    					
	    					$created_at= $message['created_at'];
	    					$current_time= gettimeofday()['sec'];	
		    			    $created_time=strtotime($created_at);	
		    			    $lapsed_time=$current_time-$created_time;
 							$post_created_time=	new DateTime($message['created_at']);
		    				 echo "<div>";
		    				 echo "<h4>".$message['first_name']." ".$message['last_name'].
		    				            " on ".
		    					          $post_created_time->format('F j, Y, g:i a')
		    					          ."<h4>";
		    				echo "<p>".$message['content']."</p>";	    					
	    					if($_SESSION['user_id']==$message['user_id'] && 
	    						  $lapsed_time < 120000 ){
	    						?>
								<form action="process.php" method="post">
									<input type="hidden" name="delete_message"
											 value="<?=$message['id'] ?>">
									<input type="hidden" name="delete"
											 value="delete">
									<input type="image" value="delete message"
											src="images/delete.jpg" alt="delete"
											width="35px" height="35px">
								</form>
							</div>				    		 
	    					<?php
	    					 }
	    					?>	    					 
	    					<?php
	    					 if(isset($comments_from_db))
	    					 {?>
	    						<?php


	    					 	foreach ($comments_from_db as $comment) 
	    					 	{
	    					 		$created_at=new DateTime($comment['created_at']);

	    					 		 if($comment['message_id']==$message['id'])
	    					 		 {	  
	    					 		  echo "<div><div><h5>".$comment['first_name']." ".
	    					 		        $comment['last_name']." on "
	    					 		        . $created_at->format('F j, Y, g:i a')
	    					 		        ."</h3>"	;			 		  
	    					          echo "<p>".$comment['content']."</p></div></div>";
	    					 		 }
	    						 }
	    					}
	    					?>
	    				</div>		   
	    			 	<div class="row"> 
							<div>
								<div>
									<div>
										<h5>Post a comment</h5>
								    	<form action="process.php" method="post">
								    		<div class="form-group">
								    			<input type="text" name="text_comment" id="text_comment">
								    		</div>
								    		<input type="hidden" name="comment" 
								    		        value="<?=$message['id'] ?>">
								    		<input type="hidden" name="post_comment" 
								    		        value="post_comment">
								    		<div class="form-group">
								    			<input type="submit" name="comment_button" 
								    				   value="Post Comment" class="btn btn-success">
								    		</div>
								    	</form>	
									</div>
								</div>
							</div>
				         </div>			    	 
	    				<?php
	    				 }	
	    		}	    		
	    	?>
	</body>
</html>