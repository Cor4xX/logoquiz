<?php 
    $this->view('Layout/summary');

    $indice = "";
    if($score != null){
        //foreach pour trouver et afficher la bonne image de la réponse
        foreach ($logos as $logo) {
            if($logo->response_correct == 1)
            {
                $indice = $logo->logo_indice;
                ?>
                <img id="goodAnswer" src="<?php echo base_url().$logo->logo_picture; ?>" alt="Logo<?php echo $logo->logo_id ?>"/>
                
                <div class="win">
                    <img src="<?php echo base_url(); ?>assets/images/win.png" alt="Win Icon" />
                    <h3><?php echo $logo->logo_name; ?></h3>
                </div>
                <?php
            }
        }        
    }
    else{
        //Mélange les réponses (random)
        shuffle($logos);

        //Jokers
        $joker1 = "";
        $joker2 = "";
        if(isset($played)){
            $joker1 = $played[0]->played_joker1;
            $joker2 = $played[0]->played_joker2;
        }

        //On stocke les ID des submit déjà données pour les comparer après avec les responses
        $temp_submit = array();

        foreach ($submit as $sub) {
            array_push($temp_submit, $sub->submit_id_response);
        }

        //foreach pour trouver et afficher la bonne image de la réponse
        foreach ($logos as $logo) {
            if($logo->response_correct == 1)
            {
                $indice = $logo->logo_indice;
                $logo_picture = $logo->logo_picture;
                ?>
                <img id="goodAnswer" src="<?php echo base_url().$logo->logo_picture; ?>" alt="Logo<?php echo $logo->logo_id ?>"/>
                <?php
            }
        }

        echo "<div class='listAnswer'>";
            echo "<div class='well'><h4>".$quiz[0]->quiz_name."</h4>Guess the brand !</div>";
            //on affiche toutes les réponses
            foreach ($logos as $logo) {
                if(in_array($logo->logo_id, $temp_submit)){
                    echo "<button class='answer btn-large btn-danger disabled' disabled='disabled'>".$logo->logo_name."</button>";
                }
                else{
                    echo "<button class='answer btn-large btn-info' onclick='check_response(".$logo->logo_id.", ".$id_level.", this)'>".$logo->logo_name."</button>";
                }
            };
            echo "<div class='btn-danger' id='locked' style='display: none;'>You loose ! Try again tomorrow. (2 chances / day)</div>";
        echo "</div>";
        ?>
        <hr>
        <div class="listJoker">
            <?php 
            // JOKER 1
            if($joker1 != "0"){
            ?>
                <button id="joker1" class='joker btn-large btn-success disabled' disabled="disabled">Joker 1 [POST FACEBOOK]</button>
            <?php
            }
            else{
            ?>    
                <button id="joker1" class='joker btn-large btn-success' onclick='<?php echo "check_joker(1, ".$id_level.")"; ?>'>Joker 1 [POST FACEBOOK]</button>
            <?php
            }
            // JOKER 2
            if($joker2 != "0"){
            ?>
                <button id="joker2" class='joker btn-large btn-warning disabled' disabled="disabled">Joker 2 [GET HELP]</button>
            <?php
            }
            else{
            ?>    
                <button id="joker2" class='joker btn-large btn-warning' onclick='<?php echo "check_joker(2, ".$id_level.")"; ?>'>Joker 2 [GET HELP]</button>
            <?php
            }
            ?>
        </div>
        <div id="indice">
            <p><?php echo $indice; ?></p>
        </div>

        <script type="text/javascript">
            function check_response(id, id_lvl, button) { 
                $.ajax({
                    url: "<?php echo base_url(); ?>level/check_response/",
                    type: 'POST',
                    data: {id_reponse: id, id_level: id_lvl},
                    success: function (result) {
                        if(result == "win"){
                            nav("level", "play", id_lvl);
                        }
                        else if(result == "loose"){
                            $(button).addClass('disabled');
                            $(button).addClass('btn-danger');
                            $(button).removeClass('btn-info');
                            $(button).attr('disabled', 'disabled');
                        }
                        else if(result == "locked"){
                            $('.answer').addClass('disabled');
                            $('.answer').addClass('btn-danger');
                            $('.answer').removeClass('btn-info');
                            $('.answer').attr('disabled', 'disabled');
                            $('#locked').fadeIn();
                        }
                    }            
                });  
            }

            function check_joker(joker, id_lvl) { 
                $.ajax({
                    url: "<?php echo base_url(); ?>level/check_joker/",
                    type: 'POST',
                    data: {num: joker, id_level: id_lvl},
                    success: function (result) {
                        if(result == "success1"){
                            //L'utilisateur n'a pas utilisé son joker, on post sur FACEBOOK !
                            facebook_publish(joker, id_lvl);
                        }
                        else if(result == "success2"){
                            window.open('http://www.wikidrunk.com/quiz/get_indice/'+id_lvl);
                            $('#joker'+joker).addClass('disabled');
                            $('#joker'+joker).attr('disabled', 'disabled');
                        }
                        else{
                            alert('You already used your joker.');
                        }
                    }
                });  
            }

            function facebook_publish(joker, id_lvl){ 
                FB.ui
                (
                    { 
                        method: 'feed', 
                        name: 'Who knows this Logo ?', 
                        link: 'https://apps.facebook.com/drink-logo-quiz/', 
                        picture: '<?php echo base_url().$logo_picture; ?>', 
                        description: 'Write a comment if you know which brand is it.' 
                    },
                    function(response) { 
                        if (response && response.post_id){ 
                            $.ajax({
                                url: "<?php echo base_url(); ?>level/update_joker/",
                                type: 'POST',
                                data: {num: joker, id_level: id_lvl},
                                success: function (result) {
                                    $('#joker'+joker).addClass('disabled');
                                    $('#joker'+joker).attr('disabled', 'disabled');
                                }
                            });  
                        } 
                        else{ 
                            alert('Problem : Post was not published.'); 
                        }
                    }
                ); 
            } 
        </script>
    <?php 
    } 
?>