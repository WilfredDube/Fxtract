<?php
require_once('includes/initialize.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: public/myprojects.php'); }

//define page title
$title = 'FXtract';

//include header template
require('public/templates/indexheader.php');
?>

<div class="hero-image">

	    <!-- <div class="site-wrapper">

	      <div class="site-wrapper-inner">

	        <div class="cover-container">

	          <div class="masthead clearfix">
	            <div class="inner">
	              <h3 class="masthead-brand">FXtract</h3>
	              <nav>
	                <ul class="nav masthead-nav">
	                  <li class="active"><a href="#" marked="1">Home</a></li>
	                  <li><a href="#" marked="1">Features</a></li>
	                  <li><a href="#" marked="1">Contact</a></li>
	                </ul>
	              </nav>
	            </div>
	          </div>

	          	        </div>

	      </div>

	    </div> -->
 <div class="hero-text">
	 <h1 class="hero-title">Design. Plan. Collaborate. Manufacture.</h1>
	 <h2 class="hero-subtitle">The world's leading computer aided process planner</h2>
	 <button class="button" onclick="window.location.href='public/register.php'"><span>Get Started </span></button>
	 <!-- <span><input class="button type="button" value="Get Started" onclick="window.location.href='public/register.php'" /></span> -->

 </div>
</div>

<?php
// include header template
require('public/templates/footer.php');
?>
