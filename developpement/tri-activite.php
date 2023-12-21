<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test Rist</title>
    <style>
        .activite{
            border-style: dotted;
        }
    </style>
    <script> 
        function changerOrdre(){

            for (let score = 0; score < 10; score++) {
                let deplacer = document.getElementById(score);

                let parent = deplacer.parentElement
                let premier = parent.firstElementChild

                if (deplacer != premier) {
                    deplacer.remove()
                    parent.insertBefore(deplacer,premier) 
                }

            }
            
        }
    </script>
</head>
<body>
    <input id=button type=button value="Trier les activitées" onclick="changerOrdre();">
    <div>
<?php
    for ($i=0; $i < 10; $i++) { 
        echo("<div class='activite' id='$i'><p>Je suis l'activité $i</p></div>");
    }
?>
    </div>
</body>
</html>

