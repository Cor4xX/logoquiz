<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title_for_layout ?></title>
        <meta charset="utf-8">

        <?php echo $meta_for_layout; ?>
        <?php echo $keywords_for_layout; ?>

       
        <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>assets/css/common.css" rel="stylesheet" media="screen">

        <link href="<?php echo base_url();?>assets/ico/favicon.png" rel="shortcut icon">
        
        <script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/js/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                if (navigator.userAgent.search("MSIE") >= 0 || navigator.userAgent.search(".NET") >= 0) {
                    alert("This application need Google Chrome or Mozilla Firefox to work perfectly.");
                }

                $('#quizContent').empty();
                $('#quizContent').load('<?php echo base_url(); ?>main/nav/home');        
            });

            /*----------- SCRIPT NAV -----------*/
            function nav(controller, method, params){
                $('#quizContent').fadeOut(100, function() {
                   $(this).empty();
                });
                $('#quizContent').fadeIn(100, function() {
                   $(this).load('<?php echo base_url(); ?>'+controller+'/'+method+'/'+params);
                });
            }
        </script>
    </head>
    <body>
        <input type="hidden" value="<?php echo base_url(); ?>" id="baseurl">
        <!-- OVERLAY -->
        <div id="overlay"></div>
        <!--////// MAIN CONTAINER \\\\\\-->
        <div class="container-fluid main">
            <!-- NAV -->
            <div class="row body">
            	<?php 
                /*---------------------------------------------------------------*/
                /*--------------------------- CONTENT ---------------------------*/
                /*---------------------------------------------------------------*/
                    echo $content_for_layout;
                ?>     
            </div>

            <!-- FOOTER -->
            <br>
            <div class="row footer">
                <p style="color: white; text-align:center;">This application need Google Chrome or Mozilla Firefox to work perfectly. </p>
            </div>
        </div> 

        <script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/js/function.js"></script>

        <script type="text/javascript">
        </script>
        <!-- Start Facebook SDK import -->
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                  appId      : '1488679854684116',
                  status     : true,
                  xfbml      : true
                });
            };
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }
            (document, 'script', 'facebook-jssdk'));
        </script>
        <!-- End Facebook SDK import -->
    </body>
</html>


