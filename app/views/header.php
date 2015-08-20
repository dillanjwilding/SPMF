<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $title?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="<?php echo $author; ?>" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <!-- <?php echo ROOT ?>/Public/Scripts/Style.css -->
        <link rel="stylesheet" href="http://localhost/College/Framework/Public/Scripts/Style.css" type="text/css" media="screen">
    </head>
    <body>
        <header>
            <div id="banner">

            </div>
            <nav>
                <ol>
<?php 
    if(isset($_SESSION['loggedIn']))
    {
        if($_SESSION['loggedIn'])
        {
?>
                    <li><a href="../../index/view/">Home</a></li>
                    <li><a href="../../clazz/viewAll/">Classes</a></li>
                    <li><a href="../../student/viewAll/">Students</a></li>
                    <li><a href="../../assignment/viewAll/">Assignments</a></li>
                    <li><a href="../../index/logout/">Log Out</a></li>
<?php
        }
        else
        {
?>
                    <li><a href="../../index/view/">Home</a></li>
                    <li><a href="../../index/confirmation/">Re-Send Confirmation</a></li>
                    <li><a href="">LINK</a></li>
                    <li><a href="">LINK</a></li>
<?php
        }
    }
    else
    {
?>
                    <li><a href="../../index/view/">Home</a></li>
                    <li><a href="../../index/confirmation/">Re-Send Confirmation</a></li>
                    <li><a href="#">LINK</a></li>
                    <li><a href="#">LINK</a></li>
<?php
    }
?>
                </ol>
            </nav>
        </header>
        <div id="<?php echo $page; ?>">
		<h1><?php echo $title?></h1>